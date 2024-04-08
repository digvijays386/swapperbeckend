<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductCategories extends Model
{
    protected $table = "product_categories";
    use HasFactory;
    protected $fillable = [
        'product_id',
        'category_id',
    ];

 
}
