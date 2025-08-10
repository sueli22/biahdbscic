<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::create([
            'image' => 'default.jpg',
            'position_id' => null,  // optional
            'eid' => 'E0001',     // employee ID, must be unique
            'name' => 'Wyine',
            'email' => 'w@gmail.com',
            'dob' => '1990-01-01',
            'currentaddress' => 'Yangon, Myanmar',
            'phno' => '0912345678',
            'super_user' => true,
            'department' => 'IT',
            'married_status' => false,
            'gender' => 1,  // e.g. 1 = male
            'password' => Hash::make('123'),
        ]);

         User::create([
            'image' => 'default.jpg',
            'position_id' => null,  // optional
            'eid' => 'E0002',     // employee ID, must be unique
            'name' => 'မမ',
            'email' => 'm@gmail.com',
            'dob' => '1990-01-01',
            'currentaddress' => 'Yangon, Myanmar',
            'phno' => '0912345678',
            'super_user' => false,
            'department' => 'IT',
            'married_status' => false,
            'gender' => 1,  // e.g. 1 = male
            'password' => Hash::make('123'),
        ]);

        User::create([
            'image' => 'default.jpg',
            'position_id' => null,  // optional
            'eid' => 'E0003',     // employee ID, must be unique
            'name' => 'လှလှ',
            'email' => 'z@gmail.com',
            'dob' => '1990-01-01',
            'currentaddress' => 'Yangon, Myanmar',
            'phno' => '0912345678',
            'super_user' => false,
            'department' => 'IT',
            'married_status' => false,
            'gender' => 1,  // e.g. 1 = male
            'password' => Hash::make('123'),
        ]);
    }
}
