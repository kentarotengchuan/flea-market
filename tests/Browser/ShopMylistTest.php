<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Like;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ShopMylistTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_get_shop_mylist()
    {
        $user = User::findOrFail(1);

        $testPublisher = User::create([
            'name' => 'publisher',
            'email' => 'publisher@test.com',
            'password' => 'hogehoge',
        ]);

        $likedProduct = Product::create([
            'user_id' => 2,
            'condition_id' => 1,
            'name' => 'test_product',
            'imgPath' => 'watch.jpg',
            'price' => 8000,
            'description' => 'test_description',
            'sold' => 'no',
        ]);

        Like::create([
            'product_id' => $likedProduct->id,
            'user_id' => $user->id,
        ]);

        $this->browse(function (Browser $browser) use ($user, $likedProduct) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'hogehoge')
                ->press('ログインする')
                ->waitForLocation('/')
                ->assertPathIs('/')
                ->clicklink('マイリスト')
                ->waitForLocation('/')
                ->assertPathIs('/')
                ->assertSee($likedProduct->name);
        });
    }

    public function test_express_sold()
    {
        $user = User::findOrFail(1);

        $testPublisher = User::create([
            'name' => 'publisher',
            'email' => 'publisher@test.com',
            'password' => 'hogehoge',
        ]);

        $likedAndSoldProduct = Product::create([
            'user_id' => 2,
            'condition_id' => 1,
            'name' => 'test_product',
            'imgPath' => 'watch.jpg',
            'price' => 8000,
            'description' => 'test_description',
            'sold' => 'yes', //売れた商品
        ]);

        Like::create([
            'product_id' => $likedAndSoldProduct->id,
            'user_id' => $user->id,
        ]);

        $this->browse(function (Browser $browser) use ($user, $likedAndSoldProduct) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'hogehoge')
                ->press('ログインする')
                ->waitForLocation('/')
                ->assertPathIs('/')
                ->clicklink('マイリスト')
                ->waitForLocation('/')
                ->assertPathIs('/')
                ->waitForText('sold', 5)
                ->assertSeeIn("#product-$likedAndSoldProduct->id", 'sold');
        });
    }

    public function test_not_expressed_self_products()
    {
        $user = User::findOrFail(1);

        $likedAndSelfProducts = Product::all();

        foreach ($likedAndSelfProducts as $product) {
            Like::create([
                'product_id' => $product->id,
                'user_id' => $user->id,
            ]);
        }

        $this->browse(function (Browser $browser) use ($user, $likedAndSelfProducts) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'hogehoge')
                ->press('ログインする')
                ->waitForLocation('/')
                ->assertPathIs('/')
                ->clicklink('マイリスト')
                ->waitForLocation('/')
                ->assertPathIs('/');

            foreach ($likedAndSelfProducts as $product) {
                $browser->assertDontSee($product->name);
            }
        });
    }

    public function test_not_authorized_mylist()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/mylist')
                ->waitForLocation('/')
                ->assertPathIs('/')
                ->assertMissing('.box__product');
                //商品を表示するタグが存在しない事を検証
        });
    }
}
