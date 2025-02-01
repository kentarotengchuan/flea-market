<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    
    public function testValidationForNameNull(): void
    {
        $this->get('/register');

        $response = $this->post('/register',[
            'name' => '',
            'email' => 'test01@test.com',
            'password' => 'hogehoge',
            'password_confirmation' => 'hogehoge',
        ]);

        $response->assertSessionHasErrors([
        'name' => '名前を入力してください',
        ]);
    }

    public function testValidationForEmailNull(): void
    {
        $this->get('/register');

        $response = $this->post('/register',[
            'name' => 'test01',
            'email' => '',
            'password' => 'hogehoge',
            'password_confirmation' => 'hogehoge',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    public function testValidationForPasswordNull(): void
    {
        $this->get('/register');

        $response = $this->post('/register',[
            'name' => 'test01',
            'email' => 'test01@test.com',
            'password' => '',
            'password_confirmation' => 'hogehoge',
        ]);

        $response->assertSessionHasErrors([
        'password' => 'パスワードを入力してください',
        ]);
    }

    public function testValidationForPasswordMin(): void
    {
        $this->get('/register');

        $response = $this->post('/register',[
            'name' => 'test01',
            'email' => 'test01@test.com',
            'password' => 'hoge',
            'password_confirmation' => 'hogehoge',
        ]);

        $response->assertSessionHasErrors([
        'password' => 'パスワードは最低8文字以上で入力してください',
        ]);
    }

    public function testValidationForPasswordConfirm(): void
    {
        $this->get('/register');

        $response = $this->post('/register',[
            'name' => 'test01',
            'email' => 'test01@test.com',
            'password' => 'hogehoge',
            'password_confirmation' => 'hogehogehoge',
        ]);

        $response->assertSessionHasErrors([
        'password' => 'パスワードが一致しません',
        ]);
    }

    public function testRegisteration(): void
    {
        $this->get('/register');

        $response = $this->post('/register',[
            'name' => 'test01',
            'email' => 'test01@test.com',
            'password' => 'hogehoge',
            'password_confirmation' => 'hogehoge',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test01@test.com',
        ]);

        $response->assertStatus(302);

        $response->assertRedirect('/login');
    }
}
