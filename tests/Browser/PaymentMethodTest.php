<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class PaymentMethodTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');
    }

    public function test_selected_method_expressed()
    {
        $user = User::findOrFail(1);
        $this->browse(function (Browser $browser) use ($user) {           
            $browser->loginAs($user)
                ->visit('/purchase/1')               
                ->select('#method', 'コンビニ払い')
                ->waitForText('コンビニ払い',5)
                ->assertSeeIn('#output-method', 'コンビニ払い');
        });
    }
}
