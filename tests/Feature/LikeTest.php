<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Like;
use Illuminate\Support\Facades\Artisan;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed');
    }

    public function testLike(): void
    {
        $this->post('/login',[
            'email' => 'test@test.com',
            'password' => 'hogehoge',
        ]);

        $response = $this->get("/item/1");
        $response->assertStatus(200);

        $response->assertSeeInOrder(['<span class="count__favorite">',0, '</span>']); //いいね数の初期値は0

        $response = $this->followingRedirects()->post('/detail/like/1');

        $response->assertStatus(200);

        $response->assertSeeInOrder(['<span class="count__favorite">',1, '</span>']); //いいね数が増えているかの検証
    }

    public function testChangeColorAfterLike(): void
    {
        $this->post('/login',[
            'email' => 'test@test.com',
            'password' => 'hogehoge',
        ]);

        $response = $this->get("/item/1");
        $response->assertStatus(200);

        $response->assertDontSee('class="liked"', false);
        //いいねボタンに色を付与するCSSがないことを確認

        $response = $this->followingRedirects()->post('/detail/like/1');

        $response->assertStatus(200);

        $response->assertSee('class="liked"', false);
        //いいねボタンに色を付与するCSSがあることを確認

    }

    public function testRepostLike(): void
    {
        $this->post('/login',[
            'email' => 'test@test.com',
            'password' => 'hogehoge',
        ]);

        $response = $this->get("/item/1");
        $response->assertStatus(200);

        $response->assertSeeInOrder(['<span class="count__favorite">',0, '</span>']); //いいね数の初期値は0

        $response = $this->followingRedirects()->post('/detail/like/1');

        $response->assertStatus(200);

        $response->assertSeeInOrder(['<span class="count__favorite">',1, '</span>']); //いいね数が増えているかの検証

        $response = $this->followingRedirects()->post('/detail/like/1');

        $response->assertSeeInOrder(['<span class="count__favorite">',0, '</span>']); //いいね数が減っているかの検証
    }
}
