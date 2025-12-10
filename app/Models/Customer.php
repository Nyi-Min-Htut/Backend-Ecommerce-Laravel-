<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; 

    protected $fillable=[
        'name','email','nrc','phone_number','date_of_birth','address','remark','gender',
        'image_url','image_path','is_verified','is_ban','password'
    ];

    protected $hidden = ['password'];

    public function setPasswordAttribute($value)
    {
        if(!empty($value))
        {
            $this->attributes['password'] = Hash::make($value);
        }
    }


}
