<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegisterTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_validation_for_name_null()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('email', 'test01@test.com')
                ->type('password', 'hogehoge')
                ->type('password_confirmation', 'hogehoge')
                ->press('登録する')
                ->waitForText('名前を入力してください', 5)
                ->assertSee('名前を入力してください');
        });
    }

    public function test_validation_for_email_null()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', 'test01')
                ->type('password', 'hogehoge')
                ->type('password_confirmation', 'hogehoge')
                ->press('登録する')
                ->waitForText('メールアドレスを入力してください', 5)
                ->assertSee('メールアドレスを入力してください');
        });
    }

    public function test_validation_for_password_null()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', 'test01')
                ->type('email', 'test01@test.com')
                ->type('password', '') //パスワード未入力
                ->type('password_confirmation', 'hogehoge')
                ->press('登録する')
                ->waitForText('パスワードを入力してください', 5)
                ->assertSee('パスワードを入力してください');
        });
    }

    public function test_validation_for_password_min()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', 'test01')
                ->type('email', 'test01@test.com')
                ->type('password', 'hoge') //4文字のパスワード
                ->type('password_confirmation', 'hogehoge')
                ->press('登録する')
                ->waitForText('パスワードは最低8文字以上で入力してください', 5)
                ->assertSee('パスワードは最低8文字以上で入力してください');
        });
    }

    public function test_validation_for_password_confirm()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', 'test01')
                ->type('email', 'test01@test.com')
                ->type('password', 'hogehoge')
                ->type('password_confirmation', 'hogehogehoge')
                //確認用パスワードが異なる
                ->press('登録')
                ->waitForText('パスワードが一致しません', 5)
                ->assertSee('パスワードが一致しません');
        });
    }

    public function test_registration_successful()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                ->type('name', 'test01')
                ->type('email', 'test01@test.com')
                ->type('password', 'hogehoge')
                ->type('password_confirmation', 'hogehoge')
                ->press('登録する')
                ->waitForLocation('/login')
                ->assertPathIs('/login');
            
            $this->assertDatabaseHas('users', [
                'email' => 'test01@test.com',
            ]);
        });
    }
}