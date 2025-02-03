<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('users')->delete();
        \DB::statement("ALTER TABLE users AUTO_INCREMENT = 1");
        \DB::table('users')->insert([
            'name' => 'test',
            'email' => 'test@test.com',
            'email_verified_at' => now(),
            'password' => Hash::make('hogehoge'),
            'postnumber' => '000-0000',
            'address' => 'テスト県テスト市1丁目1-1',
            'building' => 'テストビル111',
            'img_path' => 'emp.png',
        ]);
    }
}
