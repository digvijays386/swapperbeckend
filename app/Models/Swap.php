<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Swap extends Model
{
    use HasFactory;

    protected $table = 'swaps'; // Important: Define the table name
    protected $guarded = [];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function items()
    {
        return $this->hasMany(SwapItem::class);
    }
    public function message()
    {
        return $this->hasOne(Message::class);
    }
}