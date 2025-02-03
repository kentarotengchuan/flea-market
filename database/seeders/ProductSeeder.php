<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('products')->delete();
        \DB::statement("ALTER TABLE products AUTO_INCREMENT = 1");
        \DB::table('products')->insert([
            'name' => '腕時計',
            'user_id' => 1,
            'condition_id' => 1,
            'imgPath' => 'watch.jpg',
            'brand' => '',
            'price' => '15000',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'sold' => 'no',
        ]);
        \DB::table('products')->insert([
            'name' => 'HDD',
            'user_id' => 1,
            'condition_id' => 2,
            'imgPath' => 'HDD.jpg',
            'brand' => '',
            'price' => '5000',
            'description' => '高速で信頼性の高いハードディスク',
            'sold' => 'no',
        ]);
        \DB::table('products')->insert([
            'name' => '玉ねぎ3束',
            'user_id' => 1,
            'condition_id' => 3,
            'imgPath' => 'onion.jpg',
            'brand' => '',
            'price' => '300',
            'description' => '新鮮な玉ねぎ3束のセット',
            'sold' => 'no',
        ]);
        \DB::table('products')->insert([
            'name' => '革靴',
            'user_id' => 1,
            'condition_id' => 4,
            'imgPath' => 'shoes.jpg',
            'brand' => '',
            'price' => '4000',
            'description' => 'クラシックなデザインの革靴',
            'sold' => 'no',
        ]);
        \DB::table('products')->insert([
            'name' => 'ノートPC',
            'user_id' => 1,
            'condition_id' => 1,
            'imgPath' => 'laptop.jpg',
            'brand' => '',
            'price' => '45000',
            'description' => '高性能なノートパソコン',
            'sold' => 'no',
        ]);
        \DB::table('products')->insert([
            'name' => 'マイク',
            'user_id' => 1,
            'condition_id' => 2,
            'imgPath' => 'microphone.jpg',
            'brand' => '',
            'price' => '8000',
            'description' => '高音質のレコーディング用マイク',
            'sold' => 'no',
        ]);
        \DB::table('products')->insert([
            'name' => 'ショルダーバッグ',
            'user_id' => 1,
            'condition_id' => 3,
            'imgPath' => 'bag.jpg',
            'brand' => '',
            'price' => '3500',
            'description' => 'おしゃれなショルダーバッグ',
            'sold' => 'no',
        ]);
        \DB::table('products')->insert([
            'name' => 'タンブラー',
            'user_id' => 1,
            'condition_id' => 4,
            'imgPath' => 'tumbler.jpg',
            'brand' => '',
            'price' => '500',
            'description' => '使いやすいタンブラー',
            'sold' => 'no',
        ]);
        \DB::table('products')->insert([
            'name' => 'コーヒーミル',
            'user_id' => 1,
            'condition_id' => 1,
            'imgPath' => 'coffee.jpg',
            'brand' => '',
            'price' => '4000',
            'description' => '手動のコーヒーミル',
            'sold' => 'no',
        ]);
        \DB::table('products')->insert([
            'name' => 'メイクセット',
            'user_id' => 1,
            'condition_id' => 2,
            'imgPath' => 'make-set.jpg',
            'brand' => '',
            'price' => '2500',
            'description' => '便利なメイクアップセット',
            'sold' => 'no',
        ]);
    }
}
