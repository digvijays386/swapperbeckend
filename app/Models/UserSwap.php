<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSwap extends Model
{
    protected $table ="user_swap";
    protected $guarded = [];
    use HasFactory;

    public function sender_product_id()
    {
        return $this->belongsTo(Product::class,'sender_product_id');
    }
    public function product_images()
    {
        return $this->hasMany(Product_Image::class);
    }

    public function reciever_product_id()
    {
        return $this->belongsTo(Product::class,'reciever_product_id');
    }
    public function message()
    {
        return $this->hasOne(Message::class);
    }

}

