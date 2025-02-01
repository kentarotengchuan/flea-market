<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Like;
use Illuminate\Support\Facades\Artisan;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed');
    }

    public function testSearch(): void
    {
        $HDD = Product::findOrFail(2); //「HDD」という商品

        $butHDD = Product::where('id','!=',2)->get();

        $response = $this->followingRedirects()->get('/all?search=HD'); //「HD」で検索（テストケースで部分一致するのは「HDD」のみ）

        $response->assertStatus(200);

        
        $response->assertSee($HDD->name);

        foreach ($butHDD as $product) {
            $response->assertDontSee($product->name);
        }
    }

    public function testKeepValueOnMypage(): void
    {
        $user = User::create([
            'name' => 'test01',
            'email' => 'test01@test.com',
            'password' => 'hogehoge',
            'first_login' => 'no',
        ]);
        
        $this->post('/login',[
            'email' => 'test01@test.com',
            'password' => 'hogehoge',
        ]);

        $products = Product::all();

        foreach($products as $product){
            Like::create([
                'product_id' => $product->id,
                'user_id' => 2,
            ]);
        }

        $HDD = Product::findOrFail(2); //「HDD」という商品

        $butHDD = Product::where('id','!=',2)->get();

        $searchQuery = 'search=HD'; //「HD」で検索（テストケースで部分一致するのは「HDD」のみ）

        $response = $this->followingRedirects()->get("/all?$searchQuery"); 

        $response->assertStatus(200); 
        
        $response->assertSee($HDD->name);

        foreach ($butHDD as $product) {
            $response->assertDontSee($product->name);
        }

        $response = $this->followingRedirects()->get("/mylist/?$searchQuery");

        $response->assertStatus(200); 
        
        $response->assertSee($HDD->name);

        foreach ($butHDD as $product) {
            $response->assertDontSee($product->name);
        }   
    }
}
