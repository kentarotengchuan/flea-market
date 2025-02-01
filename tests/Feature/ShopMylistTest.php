<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Like;
use Illuminate\Support\Facades\Artisan;

class ShopMylistTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed');
    }

    public function testGetShopMylist(): void
    {
        $this->post('/login',[
            'email' => 'test@test.com',
            'password' => 'hogehoge',
        ]);

        $user = User::create([
            'name' => 'test01',
            'email' => 'test01@test.com',
            'password' => 'hogehoge',
            'first_login' => 'yes',
        ]);

        $product = Product::create([
            'user_id' => 2,
            'condition_id' => 1,
            'name' => 'test_product',
            'img_path' => 'watch.jpg',
            'price' => 8000,
            'description' => 'test_description',
            'sold' => 'no',
        ]);

        Like::create([
            'product_id' => 11,
            'user_id' => 1,
        ]);

        $likedProduct = Product::findOrFail(11);
        $notLikedProducts = Product::where('id','!=',11)
        ->get();

        $response = $this->followingRedirects()->get('/mylist');

        //dd($response->getContent());

        $response->assertStatus(200);

        $response->assertSee($likedProduct->name);

        foreach($notLikedProducts as $product){
            $response->assertDontSee($product->name);
        }
    }

    public function testExpressSold(): void
    {
        $this->post('/login',[
            'email' => 'test@test.com',
            'password' => 'hogehoge',
        ]);

        $user = User::create([
            'name' => 'test01',
            'email' => 'test01@test.com',
            'password' => 'hogehoge',
            'first_login' => 'yes',
        ]);

        $product = Product::create([
            'user_id' => 2,
            'condition_id' => 1,
            'name' => 'test_product',
            'img_path' => 'watch.jpg',
            'price' => 8000,
            'description' => 'test_description',
            'sold' => 'yes',
        ]);

        Like::create([
            'product_id' => 11,
            'user_id' => 1,
        ]);

        $response = $this->followingRedirects()->get('/mylist');

        //dd($response->getContent());

        $response->assertStatus(200);

        $response->assertSeeInOrder(['<div class="box__img" id=11', 'sold', '</div>']); 
    }

    public function testNotExpressedBoughtProducts(): void
    {
        $this->post('/login',[
            'email' => 'test@test.com',
            'password' => 'hogehoge',
        ]);

        $products = Product::all();

        foreach($products as $product){
            Like::create([
                'product_id' => $product->id,
                'user_id' => 1,
            ]);
        }

        $response = $this->followingRedirects()->get('/mylist');

        $response->assertStatus(200);

        foreach ($products as $product) {
            $response->assertDontSee($product->name);
        } 
    }

    public function testNotAuthorizedMylist(): void
    {
        $response = $this->followingRedirects()->get('/mylist');

        $response->assertStatus(200);

        $response->assertSeeInOrder(['<div class="box__products">', '</div>']); 
    }
}
