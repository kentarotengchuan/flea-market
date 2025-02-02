<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');
    }

    public function test_validation_for_email_null()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('password', 'hogehoge') //パスワードのみ入力
                ->press('ログインする')
                ->assertSee('メールアドレスを入力してください');
        });
    }

    public function test_validation_for_password_null()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'test@test.com') //メールアドレスのみ入力
                ->press('ログインする')
                ->assertSee('パスワードを入力してください');
        });
    }

    public function test_validation_auth_failed()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'notexist@test.com') //存在しないユーザー
                ->type('password', 'hogehoge')
                ->press('ログインする')
                ->assertSee('ログイン情報が登録されていません。');
        });
    }

    public function test_successful_login()
    {
        $user = User::findOrFail(1);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                ->type('email', $user->email)
                ->type('password', 'hogehoge')
                ->press('ログインする')
                ->waitForLocation('/')
                ->assertPathIs('/')
                ->assertAuthenticated();
        });
    }
}
