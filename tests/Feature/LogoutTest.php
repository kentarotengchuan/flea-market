<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class LogoutTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed');
    }

    public function testLogout(): void
    {
        $this->post('/login',[
            'email' => 'test@test.com',
            'password' => 'hogehoge',
        ]);

        $this->assertAuthenticated();

        $response = $this->post('/logout');

        $this->assertGuest();

        $response->assertStatus(302);

        $response->assertRedirect('/');
    }
}
