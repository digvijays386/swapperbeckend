<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "product";
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

    public function intrest()
    {
        return $this->belongsTo(Intrest::class, 'category_id');
    }

    public function images()
    {
        return $this->hasMany(Product_Image::class, 'product_id', 'id');
    }

    public function offeredInSwaps()
    {
        return $this->hasMany(SwapItem::class, 'product_id')->where('type', 'offered');
    }

    public function requestedInSwaps()
    {
        return $this->hasMany(SwapItem::class, 'product_id')->where('type', 'requested');
    }

    public function favorite()
    {
        return $this->hasMany(Favorite::class, 'product_id', 'id');
    }
}