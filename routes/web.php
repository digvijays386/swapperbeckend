<?php

use App\Http\Controllers\Admin\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('', function () {
    return view('auth.login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
Route::middleware(['auth'])->group(function(){
Route::get('/dashboard',[\App\Http\Controllers\Admin\DashboardController::class,'index'])->name('dashboard');

//about user
Route::get('/users',[\App\Http\Controllers\Admin\UserController::class,'index'])->name('users');
Route::get('/show',[\App\Http\Controllers\Admin\UserController::class,'show_all'])->name('usersindex');
Route::get('/userindex',[\App\Http\Controllers\Admin\UserController::class,'userindex'])->name('userindex');
Route::post('/useradd',[\App\Http\Controllers\Admin\UserController::class,'useradd'])->name('useradd');
Route::get('/useredel/{id}',[\App\Http\Controllers\Admin\UserController::class,'useredel'])->name('user.del');
Route::get('/edit/{id}',[\App\Http\Controllers\Admin\UserController::class,'usersedit'])->name('users_edit');
Route::post('/editaction',[\App\Http\Controllers\Admin\UserController::class,'editaction'])->name('editaction');
Route::get('/user/status/{id}/{status_code}',[\App\Http\Controllers\Admin\UserController::class,'updatestatus'])->name('update_status');

// about interest
Route::get('/intrest',[\App\Http\Controllers\Admin\IntrestController::class,'intrest'])->name('intrest');
Route::get('/show_intrests',[\App\Http\Controllers\Admin\IntrestController::class,'show_intrest'])->name('intrestindex');
Route::post('/intrestadd',[\App\Http\Controllers\Admin\IntrestController::class,'intrestadd'])->name('intrestadd');
Route::get('/intrestedel/{id}',[\App\Http\Controllers\Admin\IntrestController::class,'intrestdel'])->name('intrest.del');
Route::get('/intrestsedit/{id}',[\App\Http\Controllers\Admin\IntrestController::class,'intrestsedit'])->name('intrests_edit');
Route::post('/editintrestaction',[\App\Http\Controllers\Admin\IntrestController::class,'editintrestaction'])->name('editintrestaction');


// Route::get('products', [ProductController::class, 'product']);

//product
Route::get('/product',[\App\Http\Controllers\Admin\ProductController::class,'product'])->name('product');
Route::get('/productdel/{id}',[\App\Http\Controllers\Admin\ProductController::class,'productdel'])->name('product.del');


//categories
Route::get('/categories',[\App\Http\Controllers\Admin\CategoryController::class,'categories'])->name('categories');
Route::get('/show_category',[\App\Http\Controllers\Admin\CategoryController::class,'show_category'])->name('categoryindex');
Route::post('/categoryadd',[\App\Http\Controllers\Admin\CategoryController::class,'categoryadd'])->name('categoryadd');
Route::get('/categoryedel/{id}',[\App\Http\Controllers\Admin\CategoryController::class,'categorydel'])->name('category.del');
Route::get('/categoryedit/{id}',[\App\Http\Controllers\Admin\CategoryController::class,'categoryedit'])->name('category_edit');
Route::post('/editcategory',[\App\Http\Controllers\Admin\CategoryController::class,'editcategory'])->name('editcategory');

//FAQ
Route::get('/faq',[\App\Http\Controllers\Admin\FaqController::class,'faq'])->name('faq');
Route::get('/show_faq',[\App\Http\Controllers\Admin\FaqController::class,'show_faq'])->name('faqindex');
Route::post('/faqadd',[\App\Http\Controllers\Admin\FaqController::class,'faqadd'])->name('faqadd');
Route::get('/faqdel/{id}',[\App\Http\Controllers\Admin\FaqController::class,'faqdel'])->name('faq.del');
Route::get('/faqedit/{id}',[\App\Http\Controllers\Admin\FaqController::class,'faqedit'])->name('faq_edit');
Route::post('/editfaq',[\App\Http\Controllers\Admin\FaqController::class,'editfaq'])->name('editfaq');


});

Route::get('/uriImage', [ProductController::class, 'new']);
Route::get('/run-migration', function(){

    Artisan::call('optimize:clear');
    Artisan::call('migrate:refresh -seed');
    
    return "Migrated Successfully";
});