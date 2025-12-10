<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class Employee extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable=[
        'name','email','role_id','phone_number','dob','password','address','remark','gender','remark','image_url','image_path','is_verified','is_ban'
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
