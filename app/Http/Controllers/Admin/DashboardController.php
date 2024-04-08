<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Intrest;
use App\Models\Product;
use App\Models\Category;


class DashboardController extends Controller
{
    public function index()
    {
        $total_users = User::all()->count();
        $total_Intrest = Intrest::all()->count();
        $total_Product = Product::all()->count();
        $total_Category = Category::all()->count();

        return view('dashboard', compact('total_users', 'total_Intrest', 'total_Product', 'total_Category'));
    }
}
