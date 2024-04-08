<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlockedUser;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use App\Models\UserSwap;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    //

    public function chats()
    {
        $chats = Message::where('sender_id', auth()->id())->orWhere('reciever_id', auth()->id(), 'is_accepted', 1)->get()
            ->map(function ($obj) {
                $user_id = '';
                if (auth()->id() != $obj->sender_id) {
                    $user_id = $obj->sender_id;
                } else {
                    $user_id = $obj->reciever_id;
                }
                return [
                    'user' => User::where(['id' => $user_id])->first(),
                    'id' => $obj->id,
                    'chat_id' => $obj->chat_id
                ];
            });
        return response()->json(['chats' => $chats]);
    }

    public function update_chat_status(Request $request)
    {
        try {
            DB::beginTransaction();
            // $useswap = UserSwap::findOrFail($request->id);
            // $useswap->status = $request->status;
            // $useswap->save();
            // $update_user;
            $chat = Message::where('user_swap_id', $request->id)->first();
            if ($request->status  == 1) {
                $useswap = UserSwap::find($request->id);
                $senderproduct = Product::find($useswap->sender_product_id);
                $reciever_product = Product::find($useswap->reciever_product_id);


                $swaps = UserSwap::where('sender_product_id', $useswap->sender_product_id)
                    ->orWhere('sender_product_id', $useswap->reciever_product_id)
                    ->orWhere('reciever_product_id', $useswap->sender_product_id)
                    ->orWhere('reciever_product_id', $useswap->reciever_product_id)
                    ->get();
                $senderproduct->delete();
                $reciever_product->delete();
                foreach ($swaps as $ss) {
                    $ss->delete();
                }
                // $useswap->delete();
                DB::commit();
                return response()->json([
                    'message' => 'Swap Accepted Successfully',
                    'error' => FALSE
                ]);
            } elseif ($request->status  == 2) {                                 // negotiate
                return response()->json([
                    'message' => 'Swap Asked for Negotiation',
                    'error' => FALSE
                ]);
            } elseif ($request->status  == 3) {                                  // delte
                $useswap = UserSwap::where('id', $request->id)->delete();
                DB::commit();
                return response()->json([
                    'message' => 'Swap Rejected Successfully',
                    'error' => FALSE
                ]);
            } elseif ($request->status  == 4) {                            //block user
                $useswap = UserSwap::find($request->id);
                $swaps = UserSwap::where(function ($query) use ($useswap) {
                    $query->where('sender_id', $useswap->sender_id)
                        ->where('reciever_id', $useswap->reciever_id);
                })
                    ->orWhere(function ($query) use ($useswap) {
                        $query->where('sender_id', $useswap->reciever_id)
                            ->where('reciever_id', $useswap->sender_id);
                    })
                    ->get();
                BlockedUser::create([
                    'user_id' => $useswap->reciever_id,
                    'blocked_user_id' => $useswap->sender_id
                ]);
                foreach ($swaps as $ss) {
                    $ss->delete();
                }
                DB::commit();
                return response()->json([
                    'message' => 'User Blocked Successfully',
                    'error' => FALSE
                ]);
            } else {
                return response()->json([
                    'message' => 'Something Went Wrong',
                    'error' => TRUE
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => $e->getMessage(),
                'error' => TRUE
            ]);
        }
    }
}
