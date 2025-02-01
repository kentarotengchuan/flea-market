<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Sort;
use App\Models\Category;
use Illuminate\Support\Facades\Artisan;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed');
    }

    public function test_example(): void
    {
        $this->post('/login',[
            'email' => 'test@test.com',
            'password' => 'hogehoge',
        ]);

        $response = $this->get(("/purchase/1"));
        $response->assertStatus(200);

        
    }
}
