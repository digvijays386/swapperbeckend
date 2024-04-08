<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Intrest;
use Illuminate\Support\Facades\Storage;

class IntrestController extends Controller
{

    public function intrest()
    {
        $data = Intrest::all();
        return view('intrest', compact('data'));
    }


    public function show_intrest()
    {
        return view('add_intrest');
    }

    public function intrestadd(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif'
        ]);

        $User = new Intrest;
        $User->name = $request->name;

        if ($request->hasfile('icon')) {
            $file = $request->file('icon');
            $extension = $file->getClientOriginalExtension();
            $filename = $file->store('public');
            $filename = str_replace('public', '', $filename);
            Storage::delete($filename);
            $destinationPath = public_path() . '/interests/';
            $file->move($destinationPath, $filename);
            $User->icon = $filename;
        }
        $User->save();
        // return redirect()->back()->with('status','uaer added successfully');
        return redirect('intrest');
    }

    public function  intrestdel($id)
    {

        $user = Intrest::where('id', $id)->firstorfail()->delete();

        return redirect('intrest');
    }

    function intrestsedit($id)
    {
        $data = Intrest::find($id);

        return view('intrestsedit', ['data' => $data]);
    }

    public function editintrestaction(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif'
        ]);

        $User = Intrest::find($request->id);
        $User->name = $request->name;

        if ($request->hasfile('icon')) {
            $file = $request->file('icon');
            $extension = $file->getClientOriginalExtension();
            $filename = $file->store('public/product/icon');
            $filename = str_replace('public/', '', $filename);

            $User->icon = $filename;
        }
        $User->save();
        // return redirect()->back()->with('status','uaer added successfully');
        return redirect('intrest');
    }
}
