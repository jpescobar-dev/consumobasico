<?php

// database/seeders/UsersTableSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class UsersTableSeeder extends Seeder
{
    public function run():void
    {
        //User::create([
        DB::table('users')->insert([
            [
            'name' => 'Super Admin',
            'email' => 'sadmin@gmail.com',
            'password' => Hash::make('12345678'),
            'role'=>'admin',
            ],
            [
                    'name' => 'Admin',
                    'email' => 'admin@gmail.com',
                    'password' => Hash::make('12345678'),
                     'role'=>'admin',
            ],
            [
                'name' => 'jpescobar',
                'email' => 'jpescobar@pjud.cl',
                'password' => Hash::make('12345678'),
                 'role'=>'admin'
            ]
        ]);
    }
}
