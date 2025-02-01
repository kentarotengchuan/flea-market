<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use Illuminate\Support\Facades\Artisan;

class ShopAllTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed');
    }

    public function testGetShopAll(): void
    {
        $response = $this->followingRedirects()->get('/all');

        //dd($response->getContent());

        $response->assertStatus(200);

        //$response->assertSeeInOrder(['<div class="content">', 'sold', '</div>']);
        $products = Product::all();

        foreach ($products as $product) {
            $response->assertSee($product->name);
        }
    }

    public function testExpressSold(): void
    {
        $soldProduct = Product::findOrFail(5)
        ->update([
            'sold' => 'yes',
        ]);

        $response = $this->followingRedirects()->get('/all');

        $response->assertStatus(200);

        $response->assertSeeInOrder(['<div class="box__img" id=5', 'sold', '</div>']);     
    }

    public function testNotExpressedBoughtProducts(): void
    {
        $this->post('/login',[
            'email' => 'test@test.com',
            'password' => 'hogehoge',
        ]); //テストユーザーは全てのダミー商品の出品者

        $response = $this->followingRedirects()->get('/all');

        $response->assertStatus(200);

        $products = Product::all(); //ダミー商品の全件取得

        foreach ($products as $product) {
            $response->assertDontSee($product->name);
        }
    }
}
