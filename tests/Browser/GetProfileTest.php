<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Artisan;

class GetProfileTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_get_profile(): void
    {
        $user = User::findOrFail(1);

        $exhibitedProducts = Product::all();

        $boughtProducts = Product::whereIn('id',[1,3,5])
            ->get()
            ->each(function ($product) {
                $product->sold = 'yes';
                $product->buyer_id = 1;
                $product->save();
            });

        $this->browse(function (Browser $browser) use($user,$exhibitedProducts,$boughtProducts){
            // ログイン処理
            $browser->visit('/login')
                ->type('email', 'test@test.com')
                ->type('password', 'hogehoge')
                ->press('ログイン')
                ->assertPathIs('/')
                ->visit('/mypage')
                ->assertPathIs('/mypage')
                ->assertAttribute('.img__inner img', 'src',asset("storage/user_images/$user->img_path"))
                ->assertSeeIn('.name__profile',"$user->name");
                // ログインユーザのプロフィール画像と名前の出力を検証
            
            foreach ($exhibitedProducts as $product) {
                $browser->assertPresent(".box__products #box-$product->id");
            } //出品した商品の出力を検証

            $browser->clicklink('購入した商品')
                ->assertPathIs('/mypage');

            foreach ($boughtProducts as $product) {
                $browser->assertPresent(".box__products #box-$product->id");
            } //購入した商品の出力を検証

        });
    }
}
