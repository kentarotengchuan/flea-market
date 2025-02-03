<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Artisan;

class ExhibitTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_exhibit()
    {
        $user = User::findOrfail(1);

        $this->browse(function (Browser $browser) use($user){
            $browser->loginAs($user)
                ->visit('/sell')
                ->assertPathIs('/sell')
                ->pause(2000);

            $browser->script("document.querySelector('#image').removeAttribute('hidden');
            document.querySelector('.input__category').style.display = 'block';"); //CSSで非表示の要素を表示

            $browser->waitFor('#image')
                ->pause(1000)             
                ->check('#check-1')
                ->attach('#image', storage_path('app/public/test/test-exhibit.png')) 
                ->select('#condition',1)
                ->type('#name','test-product')
                ->type('#description','test-description')
                ->type('#price',800)
                ->press('出品する')
                ->assertPathIs('/');
            
            $createdProduct = Product::where('name','test-product')->first();
            $firstCategory = Product::where('name','test-product')->first()->getCategories()->first()->content;

            $browser->visit("/item/$createdProduct->id")
                ->assertSeeIn('.price', $createdProduct->price)
                ->assertSeeIn('.content__description', $createdProduct->description)
                ->assertSeeIn('.box__category', $firstCategory)
                ->assertSeeIn('.content__condition', $createdProduct->condition->content)
                ->assertAttribute('.img__inner img', 'src',asset("storage/product_images/$createdProduct->imgPath"))
                ->assertSeeIn('.name', "$createdProduct->name");
        });
    }
}