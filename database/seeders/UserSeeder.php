<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'role_id' => '1',
            'name' => 'Md.Masum',
            'phone' => '01750752781',
            'email' => 'mdmasum.uv@gmail.com',
            'present_address' => 'Mohammadpur',
            'password' => Hash::make('masum2781'),
        ]);
        DB::table('users')->insert([
            'role_id' => '1',
            'name' => 'Projanmo IT',
            'phone' => '01873992222',
            'email' => 'admin@projanmoit.com',
            'present_address' => 'Mohammadpur',
            'password' => Hash::make('Proit@2021.com'),
        ]);

        DB::table('users')->insert([
            'role_id' => '2',
            'name' => 'Md.Admin',
            'phone' => '01892273250',
            'email' => 'admin@gmail.com',
            'present_address' => 'Mohammadpur',
            'password' => Hash::make('123456'),
        ]);

        DB::table('users')->insert([
            'role_id' => '3',
            'name' => 'Md.User',
            'phone' => '01890346271',
            'email' => 'user@gmail.com',
            'present_address' => 'Mohammadpur',
            'password' => Hash::make('123456'),
        ]);
    }
}
