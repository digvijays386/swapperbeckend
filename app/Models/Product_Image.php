<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_Image extends Model
{
    use HasFactory;

    protected $table ="product_images";
    protected $guarded = [];


    public function products()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // public function UserSwap(){
    //     return $this->hasMany(UserSwap::class);
    // }

    public function UserSwaps(){
        return $this->belongsTo(UserSwap::class);
    }
    public function image(): Attribute
    {
        return new Attribute(
            get: fn ($value) =>asset('storage/'.$value),
        );
    }
}
