<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Product;

class ChangeAddressTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');
    }

    public function test_changed_address_expressed()
    {
        $user = User::findOrFail(1);
        $this->browse(function (Browser $browser) use ($user) {           
            $browser->loginAs($user)
                ->visit('/purchase/1')               
                ->clicklink('変更する')
                ->assertPathIs('/purchase/address/1')
                ->clear('#postnumber')
                ->type('#postnumber','666-6666')
                ->clear('#address')
                ->type('#address','changing-address')
                ->clear('#building')
                ->type('#building','changing-building')
                ->press('更新する')
                ->assertPathIs('/purchase/1')
                ->assertSeeIn('.content__postnumber',"666-6666")
                ->assertSeeIn('.content__address',"changing-address")
                ->assertSeeIn('.content__address',"changing-building");
                // 住所が反映されていることの確認
        });
    }

    public function test_changed_address_reflected_on_bought_products()
    {
        $testBuyer = User::findOrFail(1);
        
        $product = Product::create([
            'user_id' => 1,
            'condition_id' => 1,
            'name' => 'test_product',
            'img_path' => 'watch.jpg',
            'price' => 5000,
            'description' => 'test_description',
            'sold' => 'no',
        ]);

        $this->browse(function (Browser $browser) use ($testBuyer,$product) 
        { 
            $browser->loginAs($testBuyer)
                ->visit("/purchase/$product->id")
                ->clicklink('変更する')
                ->assertPathIs("/purchase/address/$product->id")
                ->clear('#postnumber')
                ->type('#postnumber','666-6666')
                ->clear('#address')
                ->type('#address','changing-address')
                ->clear('#building')
                ->type('#building','changing-building')
                ->press('更新する')
                ->assertPathIs("/purchase/$product->id")
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
                'buyer_id' => $testBuyer->id,
            ]); //商品が購入されたことを確認

            $this->assertDatabaseHas('users', [
                'id' => $testBuyer->id,
                'postnumber' => '666-6666',
                'address' => 'changing-address',
                'building' => 'changing-building'
            ]); //購入者の住所の変更が反映されていることの確認
        });
    }
}
