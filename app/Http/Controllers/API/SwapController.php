<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Swap;
use App\Models\SwapItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class SwapController extends Controller
{
    //

    public function store(Request $request)
{
    $request->validate([
        'recipient_id' => 'required|exists:users,id',
        'offered_items' => 'required|array',
        'offered_items.*' => 'exists:products,id',
        'requested_items' => 'required|array',
        'requested_items.*' => 'exists:products,id',
    ]);

    try {
        // 1. Check for existing swap request (same items, same users or reversed)

        $existingSwap = Swap::where(function ($query) use ($request) {
            $query->where('sender_id', auth()->id())
                  ->where('recipient_id', $request->recipient_id)
                  ->whereHas('items', function ($query) use ($request) {
                      $query->whereIn('product_id', $request->offered_items)
                            ->where('type', 'offered');
                  })
                  ->whereHas('items', function ($query) use ($request) {
                      $query->whereIn('product_id', $request->requested_items)
                            ->where('type', 'requested');
                  });
        })->orWhere(function ($query) use ($request) {
            $query->where('sender_id', $request->recipient_id)
                  ->where('recipient_id', auth()->id())
                  ->whereHas('items', function ($query) use ($request) {
                      $query->whereIn('product_id', $request->offered_items)
                            ->where('type', 'requested');
                  })
                  ->whereHas('items', function ($query) use ($request) {
                      $query->whereIn('product_id', $request->requested_items)
                            ->where('type', 'offered');
                  });
        })->first();


        if ($existingSwap) {
            return response()->json(['message' => 'Swap request already exists'], 400);
        }

        DB::beginTransaction();

        $swap = Swap::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $request->recipient_id,
            'status' => 'pending',
        ]);

        foreach ($request->offered_items as $itemId) {
            SwapItem::create([
                'swap_id' => $swap->id,
                'product_id' => $itemId,
                'type' => 'offered',
            ]);
        }

        foreach ($request->requested_items as $itemId) {
            SwapItem::create([
                'swap_id' => $swap->id,
                'product_id' => $itemId,
                'type' => 'requested',
            ]);
        }

        // 2. Check for existing chat (between the two users)
        $message = Message::where(function ($query) use ($request) {
            $query->where('sender_id', auth()->id())
                  ->where('reciever_id', $request->recipient_id);
        })->orWhere(function ($query) use ($request) {
            $query->where('reciever_id', auth()->id())
                  ->where('sender_id', $request->recipient_id);
        })->first();

        if (!$message) {
            $message = Message::create([
                'user_swap_id' => $swap->id,
                'sender_id' => min(auth()->id(), $request->recipient_id),
                'reciever_id' => max(auth()->id(), $request->recipient_id),
                'chat_id' => $this->unique_code(16),
            ]);
        }

        DB::commit();

        return response()->json(['swap_id' => $swap->id, 'chat_id' => $message->chat_id], 201);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => $e->getMessage()], 400);
    }
}

    public function update(Request $request, $swapId)
{
    $request->validate([
        'status' => 'required|in:accepted,rejected,cancelled',
    ]);

    $swap = Swap::find($swapId);

    if (!$swap) {
        return response()->json(['message' => 'Swap not found'], 404);
    }

    if ($swap->status !== 'pending' && $request->status != 'cancelled') { // Only allow status changes from pending or cancel from any status
        return response()->json(['message' => 'Swap cannot be updated'], 400);
    }

    if ($request->status == 'accepted' && $swap->recipient_id != Auth::id()) {
         return response()->json(['message' => 'You are not authorized to accept this swap'], 403);
    }

     if ($request->status == 'rejected' && $swap->recipient_id != Auth::id()) {
         return response()->json(['message' => 'You are not authorized to reject this swap'], 403);
    }

    if ($request->status == 'cancelled' && $swap->sender_id != Auth::id() && $swap->recipient_id != Auth::id()) {
         return response()->json(['message' => 'You are not authorized to cancel this swap'], 403);
    }

    $swap->status = $request->status;
    $swap->save();


    if ($swap->status == 'accepted') {
        // ... (item transfer logic)
        // Logic to transfer items (update ownership, etc.) goes here.
        // send notification to both users
        self::sendSwapNotification($swap->sender_id, 'swap_accepted', $swap->id, ['swap_id' => $swap->id]);
        self::sendSwapNotification($swap->recipient_id, 'swap_accepted', $swap->id, ['swap_id' => $swap->id]);

    }
    if ($swap->status == 'rejected') {
        self::sendSwapNotification($swap->sender_id, 'swap_rejected', $swap->id, ['swap_id' => $swap->id]);
    }
    if ($swap->status == 'cancelled') {
        self::sendSwapNotification($swap->recipient_id, 'swap_cancelled', $swap->id, ['swap_id' => $swap->id]);
        self::sendSwapNotification($swap->sender_id, 'swap_cancelled', $swap->id, ['swap_id' => $swap->id]);
    }
    //send notification
    return response()->json([], 204);
}


public function index(Request $request)
{
    $query = Swap::with('items.product.images'); // Eager load items and product images

    if ($request->has('sender_id')) {
        $query->where('sender_id', $request->sender_id);
    }

    if ($request->has('recipient_id')) {
        $query->where('recipient_id', $request->recipient_id);
    }

    if ($request->has('status')) {
        $query->where('status', $request->status);
    }
     if ($request->has('type')) {
        if ($request->type == 'sent') {
            $query->where('sender_id', Auth::id());
        } else if ($request->type == 'received') {
            $query->where('recipient_id', Auth::id());
        }
    }
    $swaps = $query->get();

    return response()->json($swaps, $swaps->isEmpty() ? 204 : 200);
}


// In a NotificationsService.php or helper function:

    public static function sendSwapNotification($recipientId, $type, $swapId, $extraData = [])
    {
        $user = User::find($recipientId);
        if (!$user) {
            return; // Or throw an exception if you prefer
        }
    
        $message = self::getNotificationMessage($type, $extraData);
    
        foreach ($user->devices as $device) {
            OneSignal::sendNotificationToUser(
                $message,
                $device->player_id,
                null, // URL
                $extraData, // Data for the app
                null, // Buttons
                null  // Schedule
            );
        }
    }
    
    private static function getNotificationMessage($type, $data) {
        switch ($type) {
            case 'swap_accepted':
                return "Your swap has been accepted!";
            case 'swap_rejected':
                return "Your swap has been rejected.";
            case 'swap_initiated':
                return "You have a new swap request!";
            case 'swap_cancelled':
                return "A swap has been cancelled.";
            default:
                return "You have a new notification";
        }
    }
    
    // Example usage in SwapController@update (when accepting a swap):
    
    
    function unique_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }
}
