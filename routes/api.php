<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\IntrestController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\api\UserSwapController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);
Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
Route::post('/send_email', [\App\Http\Controllers\API\AuthController::class, 'send_email']);
Route::post('/match_otp', [\App\Http\Controllers\API\AuthController::class, 'match_otp']);
Route::post('/match_otp_email', [\App\Http\Controllers\API\AuthController::class, 'match_otp_email']);

Route::middleware(['auth:api'])->group(function () {
    Route::post('logout', [\App\Http\Controllers\API\AuthController::class, 'logout']);
    Route::put("/reset_password", [\App\Http\Controllers\API\AuthController::class, 'reset_password']);
    Route::put("/change_password", [\App\Http\Controllers\API\AuthController::class, 'update_password']);
    // Intrest Route 
    Route::get('/intrest', [\App\Http\Controllers\API\IntrestController::class, 'get_intrests'])->name('get_intrests');
    //Categories Route
    Route::get('/category', [\App\Http\Controllers\API\CategoryController::class, 'get_categories'])->name('get_categories');

    //Product Routes
    Route::post('/product', [\App\Http\Controllers\API\ProductController::class, 'store_product'])->name('store_product');
    Route::get('/get_product', [\App\Http\Controllers\API\ProductController::class, 'get_product'])->name('get_product');
    Route::get('/get_myproduct', [\App\Http\Controllers\API\ProductController::class, 'get_myproduct'])->name('get_myproduct');
    //  get_discoverproduct
    Route::get('/get_discoverproduct', [\App\Http\Controllers\API\ProductController::class, 'get_discoverproduct'])->name('get_discoverproduct');
    Route::get("/search_myproduct", [\App\Http\Controllers\API\ProductController::class, 'search_myproduct'])->name('search_myproduct');
    Route::get("/search_allproduct", [\App\Http\Controllers\API\ProductController::class, 'search_allproduct'])->name('search_allproduct');
    Route::get('/product', [\App\Http\Controllers\API\ProductController::class, 'product'])->name('product');
    Route::post('/update', [\App\Http\Controllers\API\ProductController::class, 'update_product'])->name('update_product');
    Route::get('/destroy_product/{id}', [\App\Http\Controllers\API\ProductController::class, 'destroy_product'])->name('delete_product');
    Route::post('/product_image', [\App\Http\Controllers\API\ProductController::class, 'store_product_image'])->name('store_product_image');

    //update favourite product
    Route::post('/update_favourite', [\App\Http\Controllers\API\ProductController::class, 'update_favourite'])->name('update_favourite');
    Route::get('/get_favourite', [\App\Http\Controllers\API\ProductController::class, 'get_favourite'])->name('get_favourite');
    Route::post('/store_intrest', [\App\Http\Controllers\API\IntrestController::class, 'store_intrest'])->name('store_intrest');

    //update user profile

    Route::post('/update_user_profile', [\App\Http\Controllers\API\UserController::class, 'update_user_profile'])->name('update_user_profile');
    Route::get('/get_user', [\App\Http\Controllers\API\UserController::class, 'get_user'])->name('get_user');
    Route::get('/get_user_by_id', [\App\Http\Controllers\API\UserController::class, 'get_user_by_user'])->name('get_user_by_id');


    Route::post('/swap_products', [\App\Http\Controllers\API\UserController::class, 'swap_products'])->name('swap_products');

    //reject swap
    Route::post('/swap_reject', [\App\Http\Controllers\API\UserController::class, 'swap_reject'])->name('swap_reject');
    Route::get('/chats', [\App\Http\Controllers\API\MessageController::class, 'chats'])->name('chats');
    Route::post('/update_chat_status', [\App\Http\Controllers\API\MessageController::class, 'update_chat_status'])->name('update_chat_status');
    Route::post('/favourite_user', [\App\Http\Controllers\API\AcceptController::class, 'favourite_user'])->name('favourite_user');


    Route::post('/store', [\App\Http\Controllers\API\UserSwapController::class, 'store'])->name('store');
    Route::get('/get_request', [\App\Http\Controllers\API\UserSwapController::class, 'get_request'])->name('get_request');
    Route::post('/delete_request', [\App\Http\Controllers\API\UserSwapController::class, 'delete'])->name('delete_request');

    Route::get('get/auth/interest', [IntrestController::class, 'authInterests']);
    Route::get('get/outgoing/swaps', [UserSwapController::class, 'getOutgoingSwaps']);
});

//FAQ
Route::get('/get_faqs', [\App\Http\Controllers\API\FAQController::class, 'get_faqs'])->name('get_faqs');
Route::post('/store_contact', [\App\Http\Controllers\API\ContactController::class, 'store_contact'])->name('store_contact');



Route::get('/email', [App\Http\Controllers\API\AuthController::class, 'email']);
