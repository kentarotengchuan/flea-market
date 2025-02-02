<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Like;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SearchTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    /** @test */
    public function test_search()
    {
        $HDD = Product::findOrFail(2); //「HDD」という商品
        $butHDD = Product::where('id', '!=', 2)->get(); //HDD以外の商品を取得

        $this->browse(function (Browser $browser) use ($HDD, $butHDD) {
            $browser->visit('/all?search=H') //「H」で検索
                ->waitForText('HDD', 5)
                ->assertSeeIn('.box__products',$HDD->name);

            foreach ($butHDD as $product) {
                $browser->assertDontSeeIn('.box__products',$product->name);
            }     
        });
    }

    public function test_keep_value_on_mypage()
    {
        $HDD = Product::findOrFail(2); //「HDD」という商品
        $butHDD = Product::where('id', '!=', 2)->get(); //HDD以外の商品を取得

        $testSearcher = User::create([
            'name' => 'seacher',
            'email' => 'searcher@test.com',
            'password' => 'hogehoge',
            'first_login' => 'no',
        ]);

        $likedProducts = Product::all();

        foreach ($likedProducts as $product) {
            Like::create([
                'product_id' => $product->id,
                'user_id' => $testSearcher->id,
            ]);
        }

        $this->browse(function (Browser $browser) use ($testSearcher,$HDD,$butHDD) {
            $browser->visit('/login')
                ->type('email', $testSearcher->email)
                ->type('password', 'hogehoge')
                ->press('ログインする')
                ->waitForLocation('/')
                ->assertPathIs('/')
                ->visit('/all?search=H') //「H」で検索
                ->waitForText('HDD', 5)
                ->assertSee($HDD->name); //「HDD」が表示されることを確認

            foreach ($butHDD as $product) {
                $browser->assertDontSee($product->name); // HDD以外が表示されないことを確認
            } 
       
            $browser->clicklink('マイリスト')
                ->waitForLocation('/')
                ->assertPathIs('/')
                ->assertSeeIn('.box__products',$HDD->name); //マイリストでも「HDD」が表示されることを確認
                
            foreach ($butHDD as $product) {
                $browser->assertDontSee($product->name); // HDD以外が表示されないことを確認
            }         
        });
    }
}
