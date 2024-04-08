<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use DataTables;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

// use DataTables;



class UserController extends Controller
{
    //

    public function index()
    {
        $data = User::all();
        return view('user', compact('data'));
    }


    public function userindex()
    {
        return view('add_user');
    }

    public function useradd(Request $request)
    {


        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users,email,$id',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'password' => 'required',
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif'
        ]);

        $User = new User;
        $User->name = $request->name;
        $User->email = $request->email;
        $User->address = $request->address;
        $User->lat = $request->lat;
        $User->lng = $request->lng;
        $User->password = Hash::make($request->password);

        if ($request->hasfile('avatar')) {
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename = $file->store('public/product/profile_images');
            $filename = str_replace('public/', '', $filename);
            $User->avatar = $filename;
        }
        $User->save();
        return redirect('users');
    }

    function usersedit($id)
    {
        $data = User::find($id);
        //    dd( $data);
        return view('edit', ['data' => $data]);
    }

    public function  useredel($id)
    {

        $user = User::where('id', $id)->firstorfail()->delete();

        return redirect('users');
    }

    public function editaction(Request $request)
    {

        $User = User::find($request->id);
        $User->name = $request->name;
        $User->email = $request->email;
        $User->address = $request->address;
        $User->lat = $request->lat;
        $User->lng = $request->lng;
        if ($request->hasfile('avatar')) {
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();
            $filename = $file->store('public/product/profile_images');
            $filename = str_replace('public/', '', $filename);

            $User->avatar = $filename;
        }
        $User->save();
        return redirect('users');
    }

    public function updatestatus($id, $status_code)
    {
        $update_user = User::whereId($id)->update([
            'status' => $status_code
        ]);
        return redirect('users');
    }
}
