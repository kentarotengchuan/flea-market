<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Sort;
use App\Models\Category;
use Illuminate\Support\Facades\Artisan;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed');
    }

    public function testCommentByAdmin(): void
    {
        $this->post('/login',[
            'email' => 'test@test.com',
            'password' => 'hogehoge',
        ]);

        $response = $this->get("/item/1");
        $response->assertStatus(200);

        $response->assertDontSee('<div class="content__comment">');
        //初期値ではコメントがないことを検証

        $response = $this->followingRedirects()->post('/item/comment',[
            'id' => 1,
            'content' => 'test_comment',
        ]);

        $response->assertStatus(200);

        $response->assertSeeInOrder(['<div class="content__comment">','test_comment', '</div>']); //送信したコメントの存在を検証
    }

    public function testCantCommentByGuest(): void
    {
        $response = $this->get("/item/1");
        $response->assertStatus(200);

        $response->assertDontSee('<div class="content__comment">');
        //初期値ではコメントがないことを検証

        $response = $this->post('/item/comment',[
            'id' => 1,
            'content' => 'test_comment',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        //未認証ユーザのログイン画面遷移を検証

        $response = $this->get("/item/1");
        $response->assertStatus(200);

        $response->assertDontSee('<div class="content__comment">');
        //コメントが追加されていない事を検証
    }

    public function testValidationEmpty(): void
    {
        $this->post('/login',[
            'email' => 'test@test.com',
            'password' => 'hogehoge',
        ]);

        $response = $this->get("/item/1");
        $response->assertStatus(200);

        $response = $this->post('/item/comment',[
            'id' => 1,
            'content' => '',
        ]);

        $response->assertSessionHasErrors([
            'content' => 'コメントを入力してください',
        ]);
    }

    public function testValidationMax(): void
    {
        $this->post('/login',[
            'email' => 'test@test.com',
            'password' => 'hogehoge',
        ]);

        $response = $this->get("/item/1");
        $response->assertStatus(200);

        $response = $this->post('/item/comment',[
            'id' => 1,
            'content' => bin2hex(random_bytes(256)),
            //ランダムな256文字の文字列を作成
        ]);

        $response->assertSessionHasErrors([
            'content' => 'コメントは255文字以下で入力してください',
        ]);
    }
}
