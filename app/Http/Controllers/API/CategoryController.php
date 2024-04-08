<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Exception;
use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{

    public function get_categories()
    {
        try {
            $categories = Category::all();
            if (count($categories) > 0) {
                return response()->json([
                    'data' => $categories,
                    'message' => 'Category Found',
                    'error' => FALSE
                ]);
            } else {
                return response()->json([
                    'data' => $categories,
                    'message' => 'No Category found',
                    'error' => TRUE
                ]);
            }
        } catch (\Throwable $th) {
            return withError($exception->getMessage());
        }
    }
}
