<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ShopAllTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function test_get_shop_all()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/all')
            ->assertSee('おすすめ','マイリスト');

            $products = Product::all();
            foreach ($products as $product) {
                $browser->assertSee($product->name);
            }
        });
    }

    public function test_express_sold()
    {
        //5番目の商品をsoldに変更
        $soldProduct = Product::findOrFail(5);
        $soldProduct->update(['sold' => 'yes']);

        $this->browse(function (Browser $browser) use ($soldProduct) {
            $browser->visit('/all')
                ->assertSeeIn('#product-5', 'sold');
                //soldの表示を確認
        });
    }

    public function test_not_expressed_published_products()
    {
        //テストユーザー作成（すべてのダミー商品の出品者）
        $user = User::findOrFail(1);

        $this->browse(function (Browser $browser) use ($user) {
            //ログイン
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'hogehoge')
                ->press('ログインする')
                ->waitForLocation('/')
                ->assertPathIs('/');

            //出品者のため、自分の商品が表示されないことを確認
            $products = Product::all();
            foreach ($products as $product) {
                $browser->assertDontSee($product->name);
            }
        });
    }
}
