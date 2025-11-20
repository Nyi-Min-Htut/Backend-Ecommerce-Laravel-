<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Employee extends Model
{
    use HasFactory;

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
