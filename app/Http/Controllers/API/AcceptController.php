<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AcceptController extends Controller
{
    public function favourite_user(Request $request)
    {
        if (auth()->user()->favourite_users->contains($request->id)) {
            auth()->user()->favourite_users()->detach($request->id);
            return response()->json(['message' => 'unfavourite successfully!'], 200);
        } else {
            auth()->user()->favourite_users()->attach($request->id);
            return response()->json(['message' => 'favourite successfully!'], 200);
        }
    }
}
