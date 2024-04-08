<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Product_Image;
use Illuminate\Support\Facades\Storage;



class ProductController extends Controller
{
  //

  public function product()
  {
    $data = Product::all();

    return view('product', compact('data'));
  }
  public function  productdel($id)
  {

    $user = Product::where('id', $id)->firstorfail()->delete();

    return redirect('product');
  }
}
