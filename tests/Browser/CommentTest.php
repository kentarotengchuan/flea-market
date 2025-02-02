<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CommentTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    /** @test */
    public function test_admin_can_comment()
    {
        $user = User::findOrFail(1);
        $product = Product::findOrFail(1);

        $this->browse(function (Browser $browser) use ($user, $product) {
            $browser->loginAs($user)
                ->visit("/item/$product->id")
                ->assertDontSee('<div class="content__comment">')

                //コメントを入力して送信
                ->type('#content', 'test_comment')
                ->press('コメントを送信する') 
                ->waitForText('test_comment', 5)
                ->assertSeeIn('.box__comments', 'test_comment');
        });
    }

    public function test_guest_cannot_comment()
    {
        $product = Product::findOrFail(1);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visit("/item/{$product->id}")
                ->assertDontSee('<div class="content__comment">')

                //コメントを入力して送信
                ->type('#content', 'test_comment')
                ->press('コメントを送信する') 
                ->waitForLocation('/login')
                ->assertPathIs('/login')
                ->visit("/item/{$product->id}")
                ->assertDontSee('<div class="content__comment">');
        });
    }

    public function test_comment_validation_empty()
    {
        $user = User::findOrFail(1);
        $product = Product::findOrFail(1);

        $this->browse(function (Browser $browser) use ($user, $product) {
            $browser->loginAs($user)
                ->visit("/item/{$product->id}")

                //空のコメントを送信
                ->type('#content', '')
                ->press('コメントを送信する')
                ->waitForText('コメントを入力してください', 5)
                ->assertSee('コメントを入力してください');
        });
    }

    public function test_comment_validation_max()
    {
        $user = User::findOrFail(1);
        $product = Product::findOrFail(1);

        $longComment = bin2hex(random_bytes(256)); //ランダムな256文字の文字列を生成

        $this->browse(function (Browser $browser) use ($user, $product, $longComment) {
            $browser->loginAs($user)
                ->visit("/item/{$product->id}")

                //256文字のコメントを送信
                ->type('#content', $longComment)
                ->press('コメントを送信する')
                ->waitForText('コメントは255文字以下で入力してください', 5)
                ->assertSee('コメントは255文字以下で入力してください');
        });
    }
}