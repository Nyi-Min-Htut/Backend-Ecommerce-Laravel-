<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Customer::create([
            'name' => 'Nyi Min Htut',
            'email' => 'nyiminhtut2003@gmail.com',
            'nrc' => null,
            'phone_number' => '09443',
            'date_of_birth' => '2003-12-17',
            'password' => 'password',
            'address' => 'random',
            'remark' => 'remark',
            'gender' => 'male',
            'image_url' => null,
            'image_path' => null,
            'is_verified' => 1,
            'is_ban'=>1
        ]);
    }
}
