<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('categories')->delete();
        \DB::statement("ALTER TABLE categories AUTO_INCREMENT = 1");
        \DB::table('categories')->insert([
            'content' => 'ファッション',
        ]);
        \DB::table('categories')->insert([
            'content' => '家電',
        ]);
        \DB::table('categories')->insert([
            'content' => 'インテリア',
        ]);
        \DB::table('categories')->insert([
            'content' => 'レディース',
        ]);
        \DB::table('categories')->insert([
            'content' => 'メンズ',
        ]);
        \DB::table('categories')->insert([
            'content' => 'コスメ',
        ]);
        \DB::table('categories')->insert([
            'content' => '本',
        ]);
        \DB::table('categories')->insert([
            'content' => 'ゲーム',
        ]);
        \DB::table('categories')->insert([
            'content' => 'スポーツ',
        ]);
        \DB::table('categories')->insert([
            'content' => 'キッチン',
        ]);
        \DB::table('categories')->insert([
            'content' => 'ハンドメイド',
        ]);
        \DB::table('categories')->insert([
            'content' => 'アクセサリー',
        ]);
        \DB::table('categories')->insert([
            'content' => 'おもちゃ',
        ]);
        \DB::table('categories')->insert([
            'content' => 'ベビー・グッズ',
        ]);
    }
}
