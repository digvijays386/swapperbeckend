<?php

namespace App\Http\Controllers\api;

use Berkayk\OneSignal\OneSignalFacade as OneSignal;
// use Ladumor\OneSignal\OneSignal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserSwap;
use App\Models\Product;
use App\Models\Message;

class UserController extends Controller
{
    //
    public function get_user(Request $request)
    {

        $product = User::where('id', auth()->id())->first();
        //  $product = User::with('products')->where('id',auth()->id())->get();
        $count = Product::where('user_id', '=', auth()->id())->count();
        $Productcount = Product::where('user_id', '=', auth()->id())->count();
        $UserSwapcount = UserSwap::where('reciever_id', '=', auth()->id())->count();
        $outgoingSwapcount = UserSwap::where('sender_id', '=', auth()->id())->count();

        if ($product) {
            return response()->json([
                'data' => $product,
                'number_of_product' => $Productcount,
                'received_swap_requsts' => $UserSwapcount,
                'sent_swap_requsts' => $outgoingSwapcount,
                'message' => 'User Found',
                'error' => FALSE
            ]);
        } else {
            return response()->json([
                'data' => $product,
                'message' => 'No Product found',
                'error' => TRUE
            ]);
        }
    }

    public function get_user_by_user(Request $request)
    {
        // $user = User::with('products','product_images')->where('id',$request->id)->get();
        $user = User::where('id', $request->id)->first();
        $product = Product::with('images')->where('user_id', $request->id)->get();

        if ($user) {
            return response()->json([
                'user' => $user,
                'product' => $product,
                'message' => 'User Found',
                'error' => FALSE
            ]);
        } else {
            return response()->json([

                'message' => 'No Product found',
                'error' => TRUE
            ]);
        }
    }
    public function update_user_profile(Request $request)
    {
        $data = User::where('id', auth()->id())->first();
        if (isset($request->name)) {
            $data->update(['name' => $request->name]);
        }
        if (isset($request->address)) {
            $data->update(['address' => $request->address]);
        }
        if (isset($request->lat)) {
            $data->update(['lat' => $request->lat]);
        }
        if (isset($request->lng)) {
            $data->update(['lng' => $request->lng]);
        }
        if (isset($request->surname)) {
            $data->update(['surname' => $request->surname]);
        }
        if (isset($request->age)) {
            $data->update(['age' => $request->age]);
        }
        if (isset($request->country)) {
            $data->update(['country' => $request->country]);
        }
        if ($request->hasfile('avatar')) {
            $file = $request->file('avatar');
            $file_name = $file->store('public/profiles');
            Storage::delete($file_name);
            $destinationPath = public_path() . '/profiles/';
            $file->move($destinationPath, $file_name);
            $data->profile_image = str_replace("public/profiles", "", $file_name);
        }
        $data->save();
        if ($data) {
            return response()->json([
                'data' => $data,
                'message' => 'Profile Updated successfully',
                'error' => FALSE
            ]);
        } else {
            return response()->json([
                'message' => 'Profile Not Update',
                'error' => TRUE
            ]);
        }
    }
    public function swap_products(Request $request)
    {
        auth()->user()->swap_users()->syncWithoutDetaching($request->id);
        if (auth()->user()->swap_by_users->contains($request->id)) {
            $user = User::whereId($request->id)->first();
            $message = Message::where(function ($r) use ($request) {
                return $r->where('sender_id', auth()->id())->where('reciever_id', $request->id);
            })
                ->orWhere(function ($r) use ($request) {
                    return $r->where('sender_id', $request->id)->where('reciever_id', auth()->id());
                })->first();
            if ($message) {
                return response()->json(['message' => 'swap successfully!', 'match' => true, 'chat_id' => $message->chat_id], 200);
            } else {
                // dd(12345678);
                $message = Message::create(['sender_id' => auth()->id(), 'reciever_id' => $request->id, 'chat_id' => $this->unique_code(16)]);

                foreach (auth()->user()->devices as $device) {
                    OneSignal::sendNotificationToUser(
                        "A new chat with " . $user->name . " created",
                        $device->player_id,
                        $url = null,
                        $data = ['chat_id' => $message->chat_id, 'user_id' => $user->id],
                        $buttons = null,
                        $schedule = null
                    );
                }
                foreach ($user->devices as $device) {
                    OneSignal::sendNotificationToUser(
                        "A new chat with " . Auth()->user()->name . " created",
                        $device->player_id,
                        $url = null,
                        $data = ['chat_id' => $message->chat_id, 'user_id' => auth()->id()],
                        $buttons = null,
                        $schedule = null
                    );
                }
                return response()->json(['message' => 'swaping successfully!', 'match' => true, 'chat_id' => $message->chat_id], 200);
            }
        } else {
            return response()->json(['message' => 'swaped successfully!', 'match' => false], 200);
        }
    }
    function unique_code($limit)
    {
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
    }

    public function swap_reject(Request $request)
    {
        auth()->user()->reject_users()->syncWithoutDetaching($request->id);
        return response()->json(['message' => 'reject successfully!'], 200);
    }
}
