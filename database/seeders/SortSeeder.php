<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('sorts')->delete();
        \DB::statement("ALTER TABLE sorts AUTO_INCREMENT = 1");
        \DB::table('sorts')->insert([
            'product_id' => 1,
            'category_id' => 5,
        ]);
        \DB::table('sorts')->insert([
            'product_id' => 2,
            'category_id' => 2,
        ]);
        \DB::table('sorts')->insert([
            'product_id' => 3,
            'category_id' => 10,
        ]);
        \DB::table('sorts')->insert([
            'product_id' => 4,
            'category_id' => 1,
        ]);
        \DB::table('sorts')->insert([
            'product_id' => 4,
            'category_id' => 5,
        ]);
        \DB::table('sorts')->insert([
            'product_id' => 5,
            'category_id' => 2,
        ]);
        \DB::table('sorts')->insert([
            'product_id' => 6,
            'category_id' => 2,
        ]);
        \DB::table('sorts')->insert([
            'product_id' => 7,
            'category_id' => 1,
        ]);
        \DB::table('sorts')->insert([
            'product_id' => 7,
            'category_id' => 6,
        ]);
        \DB::table('sorts')->insert([
            'product_id' => 8,
            'category_id' => 10,
        ]);
        \DB::table('sorts')->insert([
            'product_id' => 9,
            'category_id' => 10,
        ]);
        \DB::table('sorts')->insert([
            'product_id' => 10,
            'category_id' => 4,
        ]);
        \DB::table('sorts')->insert([
            'product_id' => 10,
            'category_id' => 6,
        ]);
    }
}
