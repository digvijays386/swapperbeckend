<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;


class CategoryController extends Controller
{
    //

    public function categories()
    {
        $data = Category::all();
        return view('category')->with(['data' => $data]);;
    }

    public function show_category()
    {
        return view('add_category');
    }


    public function categoryadd(Request $request)
    {

        $this->validate($request, [
            'category_name' => 'required',

        ]);

        $User = new Category;
        $User->category_name = $request->category_name;
        $User->save();
        return redirect('categories');
    }

    public function  categorydel($id)
    {

        $user = Category::where('id', $id)->firstorfail()->delete();

        return redirect('categories');
    }


    function categoryedit($id)
    {
        $data = Category::find($id);

        return view('categoryedit', ['data' => $data]);
    }


    public function editcategory(Request $request)
    {

        $this->validate($request, [
            'category_name' => 'required',

        ]);

        $User = Category::find($request->id);
        $User->category_name = $request->category_name;
        $User->save();
        return redirect('categories');
    }
}
