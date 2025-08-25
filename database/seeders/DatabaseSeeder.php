<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Web;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Web::create([
            'color'   => '#082a45ff',   // Bootstrap blue
            'logoimg' => 'default-logo.png',
            'bgimg'   => 'default-bg.jpg',
            'footer'  => 'စီမံကိန်းနှင့်ဘဏ္ဍာရေးဝန်ကြီးဌာန',
        ]);
        // \App\Models\User::factory(10)->create();

        User::create([
            'image' => 'default.jpg',
            'position_id' => null,  // optional
            'eid' => 'E0001',     // employee ID, must be unique
            'name' => 'OOKyawKhaing',
            'email' => 'admin@gmail.com',
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

        $locations = [
            'ကျိုက်ထို',
            'ပဲခူး',
            'မြောင်းမ',
            'ဒဂုံ',
            'ဘားအံ',
            'မုံရွာ',
            'ငပိ',
            'ထန်းတပင်',
            'လေးပင်',
            'ဓမ္မစကြာ'
        ];

        $departments = [
            'စိုက်ပျိုးရေး ဌာန',
            'ပင်လယ်စာရေးဌာန',
            'လယ်ယာစီမံခန့်ခွဲရေးဌာန',
            'အမျိုးသားစိုက်ပျိုးရေးစီမံကိန်းဌာန',
        ];

        for ($i = 1; $i <= 30; $i++) {
            $from = rand(2000, 2022); // max 2022, so that 'to' can be at least 1 year later
        $to = rand($from + 1, 2023);
            DB::table('yearly_reports')->insert([
                'from' => $from,
                'to' => $to,
                'name' => 'စီမံကိန်း ' . $i,
                'location' => $locations[array_rand($locations)],
                'start_month' => rand(1, 12) . '.2020',
                'end_month' => rand(1, 12) . '.2020',
                'department' => $departments[array_rand($departments)],
                'operation_year' => rand(2000, 2023),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
