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

class DetailTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function testDetail(): void
    {
        $this->browse(function (Browser $browser) {
            // ログイン処理
            $browser->visit('/login')
                ->type('email', 'test@test.com')
                ->type('password', 'hogehoge')
                ->press('ログイン')
                ->assertPathIs('/');

            $products = Product::all();

            foreach ($products as $product) {
                $comment = Comment::create([
                    "user_id" => 1,
                    "product_id" => $product->id,
                    "content" => 'test_content',
                ]);

                $firstCategory = $product->getCategories()->first()->content;

                $browser->visit("/item/{$product->id}")
                    ->assertAttribute('.img__inner img', 'src', asset("storage/product_images/$product->imgPath"))
                    ->assertSeeIn('.name', $product->name);
                if ($product->brand) {
                    $browser->assertSeeIn('.brand', $product->brand);
                }else{
                    $browser->assertMissing('.brand');
                }
                $browser->assertSeeIn('.price', $product->price)
                    ->assertSeeIn('.count__favorite', $product->countLike())
                    ->assertSeeIn('.count__comment', $product->countComment())
                    ->assertSeeIn('.content__description', $product->description)
                    ->assertSeeIn('.box__category', $firstCategory)
                    ->assertSeeIn('.content__condition', $product->condition->content)
                    ->assertAttribute('.img__admin img', 'src',asset('storage/user_images/emp.png'))
                    ->assertSeeIn('.name__admin', 'test')
                    ->assertSeeIn('.content__comment', $comment->content);
            }
        });
    }

    public function testExpressedSomeCategory(): void
    {
        $this->browse(function (Browser $browser) {
            // ログイン処理
            $browser->visit('/login')
                ->type('email', 'test@test.com')
                ->type('password', 'hogehoge')
                ->press('ログイン')
                ->assertPathIs('/');

            $someCategoriesProduct = Product::create([
                'user_id' => 1,
                'condition_id' => 1,
                'name' => 'test_product',
                'imgPath' => 'watch.jpg',
                'price' => '666',
                'description' => 'test_description',
                'sold' => 'no',
            ]); // IDは11

            $allCategories = Category::all();

            foreach ($allCategories as $category) {
                Sort::create([
                    'product_id' => $someCategoriesProduct->id,
                    'category_id' => $category->id,
                ]);
            }

            $browser->visit("/item/{$someCategoriesProduct->id}")
                ->waitFor('.box__category');

            foreach ($allCategories as $category) {
                $browser->assertSeeIn('.box__category', $category->content);
            }
        });
    }
}
