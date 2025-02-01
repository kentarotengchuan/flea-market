<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    
    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed');
    }

    public function testValidationForEmailNull(): void
    {
        $this->get('/login');

        $response = $this->post('/login',[
            'email' => '',
            'password' => 'hogehoge',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    public function testValidationForPasswordNull(): void
    {
        $this->get('/login');

        $response = $this->post('/login',[
            'email' => 'test@test.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
        'password' => 'パスワードを入力してください',
        ]);
    }

    public function testValidationAuthFailed(): void
    {
        $this->get('/login');

        $response = $this->post('/login',[
            'email' => 'notexist@test.com',
            'password' => 'hogehoge',
        ]);

        $response->assertSessionHasErrors([
            'email' => __('これらの認証情報は記録と一致しません。'),
        ]);
    }

    public function testLogin(): void
    {
        $this->get('/login');

        /*$user = User::factory()->create([
            'name' => 'test01',
            'email' => 'test01@test.com',
            'password' => bcrypt('hogehoge'),
            'first_login' => 'no',
        ]);*/

        $response = $this->post('/login',[
            'email' => 'test@test.com',
            'password' => 'hogehoge',
        ]);

        $this->assertAuthenticated();

        $response->assertStatus(302);

        $response->assertRedirect('/');
    }
}
