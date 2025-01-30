<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('conditions')->delete();
        \DB::statement("ALTER TABLE conditions AUTO_INCREMENT = 1");
        \DB::table('conditions')->insert([
            'content' => '良好',
        ]);
        \DB::table('conditions')->insert([
            'content' => '目立った傷や汚れなし',
        ]);
        \DB::table('conditions')->insert([
            'content' => 'やや傷や汚れあり',
        ]);
        \DB::table('conditions')->insert([
            'content' => '状態が悪い',
        ]);
    }
}
