<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwapItem extends Model
{
    use HasFactory;

    protected $table = 'swap_items'; // Important: Define the table name
    protected $guarded = [];

    public function swap()
    {
        return $this->belongsTo(Swap::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}