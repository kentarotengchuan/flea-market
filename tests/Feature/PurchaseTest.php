<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Sort;
use App\Models\Category;
use Illuminate\Support\Facades\Artisan;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Mockery;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed');
    }

    public function testPurchase(): void
    {
        $this->post('/login',[
            'email' => 'test@test.com',
            'password' => 'hogehoge',
        ]);

        $product = Product::create([
            'user_id' => 1,
            'condition_id' => 1,
            'name' => 'test_product',
            'img_path' => 'watch.jpg',
            'price' => 5000,
            'description' => 'test_description',
            'sold' => 'no',
        ]);

        $response = $this->get(("/purchase/$product->id"));
        $response->assertStatus(200);

        Stripe::setApiKey('sk_test_51QELQqLvLuvRaavwAziU8FwpNwQyEUESav9Kp7NQ6Zzx1Qqba0i8gY67Rfv0WSjOuWHXJlsTGgRSZev4y2eGVk7G005Pe4sVIy');  

        $stripeMock = Mockery::mock('overload:' . Session::class);
        $stripeMock->shouldReceive('create')->andReturn((object)[
            'id' => 'pi_test_666666',
        ]);
        //購入機能をモック化

        $response = $this->postJson('/purchase/product/sold', [
            'id' => $product->id,
            'amount' => $product->price,
            'name' => $product->name,
            'method' => 'カード払い',
        ]);
        $response->assertStatus(200);
        $response->assertJson(['id' => 'pi_test_666666']);
        //購入機能が呼び出されることを確認

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'sold' => 'no',
            'buyer_id' => null,
        ]);
        //商品が購入されていないことを確認

        $response = $this->get("/purchase/product/success/$product->id");

        $response->assertStatus(302);
        $response->assertRedirect("/item/$product->id");

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'sold' => 'yes',
            'buyer_id' => 1,
        ]);
        //商品が購入されたことを確認
    }

    public function testExpressedSold(): void
    {
        $this->post('/login',[
            'email' => 'test@test.com',
            'password' => 'hogehoge',
        ]);

        User::create([
            'name' => 'test_publisher',
            'email' => 'publisher@test.com',
            'password' => 'hogehoge',
            'first_login' => 'no',
        ]);
        //テスト用の出品者を作成

        $product = Product::create([
            'user_id' => 2,
            'condition_id' => 1,
            'name' => 'test_product',
            'img_path' => 'watch.jpg',
            'price' => 5000,
            'description' => 'test_description',
            'sold' => 'no',
        ]);

        $response = $this->get(("/purchase/address/$product->id"));
        $response->assertStatus(200);

        Stripe::setApiKey('sk_test_51QELQqLvLuvRaavwAziU8FwpNwQyEUESav9Kp7NQ6Zzx1Qqba0i8gY67Rfv0WSjOuWHXJlsTGgRSZev4y2eGVk7G005Pe4sVIy');  

        $stripeMock = Mockery::mock('overload:' . Session::class);
        $stripeMock->shouldReceive('create')->andReturn((object)[
            'id' => 'pi_test_666666',
        ]); //購入機能をモック化

        $response = $this->postJson('/purchase/product/sold', [
            'id' => $product->id,
            'amount' => $product->price,
            'name' => $product->name,
            'method' => 'カード払い',
        ]);
        $response->assertStatus(200);
        $response->assertJson(['id' => 'pi_test_666666']);
        //購入機能が呼び出されることを確認

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'sold' => 'no',
            'buyer_id' => null,
        ]); //商品が購入されていないことを確認

        $response = $this->get("/purchase/product/success/$product->id");

        $response->assertStatus(302);
        $response->assertRedirect("/item/$product->id");

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'sold' => 'yes',
            'buyer_id' => 1,
        ]); //商品が購入されたことを確認

        $response = $this->followingRedirects()->get("/all");
        $response->assertStatus(200);

        $response->assertSeeInOrder([`<div class="box__img" id=$product->id>`,'sold', '</div>']);
    }

    public function testAddProductOnMypage(): void
    {
        $this->post('/login',[
            'email' => 'test@test.com',
            'password' => 'hogehoge',
        ]);

        User::create([
            'name' => 'test_publisher',
            'email' => 'publisher@test.com',
            'password' => 'hogehoge',
            'first_login' => 'no',  
        ]);
        //テスト用の出品者を作成

        $product = Product::create([
            'user_id' => 2,
            'condition_id' => 1,
            'name' => 'test_product',
            'img_path' => 'watch.jpg',
            'price' => 5000,
            'description' => 'test_description',
            'sold' => 'no',
        ]);

        $response = $this->get(("/purchase/address/$product->id"));
        $response->assertStatus(200);

        Stripe::setApiKey('sk_test_51QELQqLvLuvRaavwAziU8FwpNwQyEUESav9Kp7NQ6Zzx1Qqba0i8gY67Rfv0WSjOuWHXJlsTGgRSZev4y2eGVk7G005Pe4sVIy');

        $stripeMock = Mockery::mock('overload:' . Session::class);
        $stripeMock->shouldReceive('create')->andReturn((object)[
            'id' => 'pi_test_666666',
        ]);
        //購入機能をモック化

        $response = $this->postJson('/purchase/product/sold', [
            'id' => $product->id,
            'amount' => $product->price,
            'name' => $product->name,
            'method' => 'カード払い',
        ]);
        $response->assertStatus(200);
        $response->assertJson(['id' => 'pi_test_666666']);
        //購入機能が呼び出されることを確認

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'sold' => 'no',
            'buyer_id' => null,
        ]);
        //商品が購入されていないことを確認

        $response = $this->get("/purchase/product/success/$product->id");

        $response->assertStatus(302);
        $response->assertRedirect("/item/$product->id");

        $this->assertDatabaseHas('products',[
            'id' => $product->id,
            'sold' => 'yes',
            'buyer_id' => 1,
        ]);
        //商品が購入されたことを確認

        $response = $this->followingRedirects()->get('/mypage/buy');
        $response->assertStatus(200);

        $response->assertSeeInOrder(['<div class="box__products">',"$product->img_path",'</div>']);
    }
}
