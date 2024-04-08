<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intrest extends Model
{
    use HasFactory;
    protected $table = "intrests";

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_intrest_pivot_table','intrest_id' , 'user_id');
    }
}
