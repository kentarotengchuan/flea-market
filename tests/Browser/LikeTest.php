<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Product;
use App\Models\Like;
use Illuminate\Support\Facades\Artisan;

class LikeTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function testLike(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@test.com')
                ->type('password', 'hogehoge')
                ->press('ログイン')
                ->assertPathIs('/');

            $browser->visit('/item/1')
                ->waitFor('.count__favorite')
                ->assertSeeIn('.count__favorite', '0') //いいね数の初期値は0
                ->click('#like') //いいねボタンをクリック
                ->pause(1000)
                ->assertSeeIn('.count__favorite', '1'); //いいね数が増えているかの検証
        });
    }

    public function testChangeColorAfterLike(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@test.com')
                ->type('password', 'hogehoge')
                ->press('ログイン')
                ->assertPathIs('/');

            $browser->visit('/item/1')
                ->waitFor('#like')
                ->assertMissing('.liked') //初期状態で色を付与する'liked'クラスがないことを確認
                ->click('#like') //いいねボタンをクリック
                ->pause(1000)
                ->assertPresent('.liked'); //いいねボタンに'liked'クラスが付くことを確認
        });
    }

    public function testRepostLike(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@test.com')
                ->type('password', 'hogehoge')
                ->press('ログイン')
                ->assertPathIs('/');

            $browser->visit('/item/1')
                ->waitFor('.count__favorite')
                ->assertSeeIn('.count__favorite', '0') //いいね数の初期値は0
                ->click('#like') //いいねボタンをクリック
                ->pause(1000)
                ->assertSeeIn('.count__favorite', '1') //いいね数が増えているかの検証
                ->click('#like') //もう一度いいねボタンをクリック（取り消し）
                ->pause(1000)
                ->assertSeeIn('.count__favorite', '0'); //いいね数が減っているかの検証
        });
    }
}
