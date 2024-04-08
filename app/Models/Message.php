<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function type(){
        return $this->belongsTo(MessageType::class,'type_id');
    }

    public function sender()
    {
        return $this->belongsToMany(User::class, 'messages', 'sender_id', 'reciever_id', 'id', 'id');
    }

    public function reciever()
    {
        return $this->belongsToMany(User::class, 'messages', 'reciever_id', 'sender_id', 'id', 'id');
    }
    public function userswap()
    {
        return $this->belongsTo(UserSwap::class);
    }
}
