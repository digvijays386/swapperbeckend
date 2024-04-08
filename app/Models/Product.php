<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "products";
    protected $guarded = [];

    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function category()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }
    // public function category()
    // {
    //     return  $this->hasMany(ProductCategories::class,  'product_id', 'id');
    // }
    public function intrest()
    {
        return $this->belongsTo(Intrest::class, 'category_id');
    }
    public function images()
    {
        return $this->hasMany(Product_Image::class, 'product_id', 'id');
    }


    public function swap_products()
    {
        return $this->belongsToMany(Product::class, 'swaps', 'swap_id', 'user_id');
    }
    public function swap_by_products()
    {
        return $this->belongsToMany(Product::class, 'swaps', 'user_id', 'swap_id');
    }
    // public function reject_users()
    // {
    //     return $this->belongsToMany(Product::class,'rejects', 'rejecter_id', 'user_id');
    // }

    public function sender()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function reciever()
    {
        return $this->hasMany(Message::class, 'reciever_id');
    }

    public function UserSwap()
    {
        return $this->hasMany(UserSwap::class);
    }

    public function UserSwaps()
    {
        return $this->hasMany(UserSwap::class);
    }

    public function favorite()
    {
        return $this->hasMany(Favorite::class, 'product_id', 'id');
    }
}
