<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIntrest_pivot extends Model
{
    // use HasFactory;

     //

     protected $fillable = [
        'user_id',
        'intrest_id',
    ];


    use HasFactory;
    protected $table = "user_intrest_pivot_table";

    public function users()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
