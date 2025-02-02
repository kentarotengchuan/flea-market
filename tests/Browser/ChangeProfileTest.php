<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Artisan;

class ChangeProfileTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
    }

    public function test_change_profile(): void
    {
        $user = User::findOrFail(1);

        $this->browse(function (Browser $browser) use($user){
            // ログイン処理
            $browser->visit('/login')
                ->type('email', 'test@test.com')
                ->type('password', 'hogehoge')
                ->press('ログイン')
                ->assertPathIs('/')
                ->visit('/mypage/profile')
                ->assertPathIs('/mypage/profile')
                ->assertAttribute('.box__image img', 'src',asset("storage/user_images/$user->img_path"))
                ->assertValue('.form__name',"$user->name")
                ->assertValue('.form__postnumber',"$user->postnumber")
                ->assertValue('.form__address',"$user->address")
                ->assertValue('.form__building',"$user->building")
                // ログインユーザの情報の出力を検証
                ->visit('/');
                

            $user->img_path = 'test.png';
            $user->save();

            $browser->visit('/mypage/profile')
                ->clear('.form__name')
                ->type('.form__name','changing-name')
                ->clear('.form__postnumber')
                ->type('.form__postnumber','666-6666')
                ->clear('.form__address')
                ->type('.form__address','changing-address')
                ->clear('.form__building')
                ->type('.form__building','changing-building')
                ->press('更新する')
                ->visit('/mypage/profile')
                ->assertAttribute('.box__image img', 'src',asset("storage/user_images/test.png"))
                ->assertValue('.form__name',"changing-name")
                ->assertValue('.form__postnumber',"666-6666")
                ->assertValue('.form__address',"changing-address")
                ->assertValue('.form__building',"changing-building");
        });
    }
}
