<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Product;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Sort;
use App\Models\Category;
use Illuminate\Support\Facades\Artisan;
use Mockery;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function testPurchase(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@test.com')
                ->type('password', 'hogehoge')
                ->press('ログイン')
                ->assertPathIs('/');

            $product = Product::create([
                'user_id' => 1,
                'condition_id' => 1,
                'name' => 'test_product',
                'imgPath' => 'watch.jpg',
                'price' => 5000,
                'description' => 'test_description',
                'sold' => 'no',
            ]);

            $browser->visit("/purchase/$product->id")
                ->assertSee($product->name)
                ->assertSee($product->price)
                ->select('#method', 'カード払い')
                ->press('購入する')
                ->pause(3000)
                ->type('#email','test@test.com')
                ->type('#cardNumber','4242424242424242')
                ->type('#cardExpiry','1225')
                ->type('#cardCvc','666')
                ->type('#billingName','testuser')
                ->click('.SubmitButton')
                ->pause(5000)
                ->assertPathIs("/item/$product->id");

            $this->assertDatabaseHas('products', [
                'id' => $product->id,
                'sold' => 'yes',
                'buyer_id' => 1,
            ]); //商品が購入されたことを確認
        });
    }

    public function testExpressedSold(): void
    {
        User::create([
            'name' => 'test_publisher',
            'email' => 'publisher@test.com',
            'password' => 'hogehoge',
        ]);
        //テスト用の出品者を作成

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@test.com')
                ->type('password', 'hogehoge')
                ->press('ログイン')
                ->assertPathIs('/');

            $product = Product::create([
                'user_id' => 2,
                'condition_id' => 1,
                'name' => 'test_product',
                'imgPath' => 'watch.jpg',
                'price' => 5000,
                'description' => 'test_description',
                'sold' => 'no',
            ]);

            $browser->visit("/purchase/$product->id")
                ->assertSee($product->name)
                ->assertSee($product->price)
                ->select('#method', 'カード払い')
                ->press('購入する')
                ->pause(3000)
                ->type('#email','test@test.com')
                ->type('#cardNumber','4242424242424242')
                ->type('#cardExpiry','1225')
                ->type('#cardCvc','666')
                ->type('#billingName','testuser')
                ->click('.SubmitButton')
                ->pause(5000)
                ->assertPathIs("/item/$product->id");

            $this->assertDatabaseHas('products', [
                'id' => $product->id,
                'sold' => 'yes',
                'buyer_id' => 1,
            ]); //商品が購入されたことを確認
        

        $browser->visit('/all')
            ->assertSeeIn("#product-$product->id", 'sold'); // "sold" の表示を確認
    });
    }

    public function testAddProductOnMypage(): void
    {
        User::create([
            'name' => 'test_publisher',
            'email' => 'publisher@test.com',
            'password' => 'hogehoge',
        ]);
        //テスト用の出品者を作成

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@test.com')
                ->type('password', 'hogehoge')
                ->press('ログイン')
                ->assertPathIs('/');

            $product = Product::create([
                'user_id' => 2,
                'condition_id' => 1,
                'name' => 'test_product',
                'imgPath' => 'watch.jpg',
                'price' => 5000,
                'description' => 'test_description',
                'sold' => 'no',
            ]);

            $browser->visit("/purchase/$product->id")
                ->assertSee($product->name)
                ->assertSee($product->price)
                ->select('#method', 'カード払い')
                ->press('購入する')
                ->pause(3000)
                ->type('#email','test@test.com')
                ->type('#cardNumber','4242424242424242')
                ->type('#cardExpiry','1225')
                ->type('#cardCvc','666')
                ->type('#billingName','testuser')
                ->click('.SubmitButton')
                ->pause(5000)
                ->assertPathIs("/item/$product->id");

            $this->assertDatabaseHas('products', [
                'id' => $product->id,
                'sold' => 'yes',
                'buyer_id' => 1,
            ]); //商品が購入されたことを確認

        $browser->visit('/mypage/buy')
            ->assertAttribute("#product-$product->id", 'src', asset("storage/product_images/$product->imgPath"));
    });
    }
}
