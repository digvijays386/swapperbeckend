<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;   /** USE HasApiTokens **/


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;  /** USE HasApiTokens **/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'lat',
        'lng',
        'surname',
        'age',
        'country',
        'otp',
        'email_otp',
        'status',
        'profile_image'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function products(){
        return $this->hasMany(Product::class);
    }
    public function product_images(){
        return $this->hasMany(Product_Image::class);
    }
    
    public function swap_users()
    {
        return $this->belongsToMany(User::class,'swaps', 'swap_id', 'user_id');
    }

    public function swap_by_users()
    {
        return $this->belongsToMany(User::class,'swaps', 'user_id', 'swap_id');
    }
    public function reject_users()
    {
        return $this->belongsToMany(User::class,'rejects', 'rejecter_id', 'user_id');
    }

    public function rejected_by_users()
    {
        return $this->belongsToMany(User::class,'rejects', 'user_id', 'rejecter_id');
    }
    public function favourite_users()
    {
        return $this->belongsToMany(User::class,'favourites', 'user_id', 'favourite_id');
    }

    public function favourite_by_users()
    {
        return $this->belongsToMany(User::class,'favourites', 'favourite_id', 'user_id');
    }

    public function intrests()
    {
        return $this->belongsToMany(Intrest::class, 'user_intrest_pivot_table' , 'user_id' , 'intrest_id');
    }

    public function user_intrest_pivot_tables(){
        return $this->hasMany(user_intrest_pivot_table::class,'user_id');

    }
    public function sender()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function reciever()
    {
        return $this->hasMany(Message::class, 'reciever_id');
    }

    public function devices()
    {
        return $this->hasMany(UserDevice::class, 'user_id');
    }
}
