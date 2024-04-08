<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;


class FaqController extends Controller
{
    //

    public function faq()
    {
        $data = Faq::all();
        return view('faq')->with(['data' => $data]);;
    }
    public function show_faq()
    {
        return view('add_faq');
    }

    public function faqadd(Request $request)
    {

        $this->validate($request, [
            'description' => 'required',

        ]);

        $User = new Faq;
        $User->description = $request->description;
        $User->save();
        return redirect('faq');
    }

    public function  faqdel($id)
    {

        $user = Faq::where('id', $id)->firstorfail()->delete();

        return redirect('faq');
    }

    function faqedit($id)
    {
        $data = Faq::find($id);

        return view('faqedit', ['data' => $data]);
    }
    public function editfaq(Request $request)
    {

        $this->validate($request, [
            'description' => 'required',

        ]);

        $User = Faq::find($request->id);
        $User->description = $request->description;
        $User->save();
        return redirect('faq');
    }
}
