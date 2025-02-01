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

class DetailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed');
    }

    public function testDetail(): void
    {
        $this->post('/login',[
            'email' => 'test@test.com',
            'password' => 'hogehoge',
        ]);

        $products = Product::all();

        foreach($products as $product){
            $comment = Comment::create([
                "user_id" => 1,
                "product_id" => $product->id,
                "content" => 'test_content',
            ]);

            $response = $this->get("/item/$product->id");$response->assertStatus(200);
            //dd($response->getContent());

            $response->assertSeeInOrder(['<div class="img__inner">', $product->img_path, '</div>']);
            $response->assertSeeInOrder(['<h2 class="name">', $product->name, '</h2>']);
            $response->assertSeeInOrder(['<span class="brand">', $product->brand, '</span>']);
            $response->assertSeeInOrder(['<span class="price">', $product->price, '</span>']);
            $response->assertSeeInOrder(['<span class="count__favorite">', $product->countLike(), '</span>']);
            $response->assertSeeInOrder(['<span class="count__comment">', $product->countComment(), '</span>']);
            $response->assertSeeInOrder(['<p class="content__description">', $product->description, '</p>']);
            $response->assertSeeInOrder(['<div class="box__category">','<span class="content__category">', '</div>']);
            $response->assertSeeInOrder(['<span class="content__condition">',$product->condition->content, '</span>']);
            $response->assertSeeInOrder(['<div class="img__admin">','emp.png', '</div>']);
            $response->assertSeeInOrder(['<span class="name__admin">','test', '</span>']);
            $response->assertSeeInOrder(['<div class="content__comment">',$comment->content, '</div>']);
        }
    }

    public function testExpressedSomeCategory(): void
    {
        $this->post('/login',[
            'email' => 'test@test.com',
            'password' => 'hogehoge',
        ]);

        $someCategoriesProduct = Product::create([
            'user_id' => 1,
            'condition_id' => 1,
            'name' => 'test_product',
            'img_path' => 'watch.jpg',
            'price' => '666',
            'description' => 'test_description',
            'sold' => 'no',
        ]); //IDは11

        $allCategories = Category::all();

        foreach($allCategories as $category){
            Sort::create([
                'product_id' => 11,
                'category_id' => $category->id,
            ]);
        } //全てのカテゴリを商品に付与

        $response = $this->get("/item/$someCategoriesProduct->id");$response->assertStatus(200);

        foreach($allCategories as $category){
            $response->assertSeeInOrder(['<div class="box__category">', $category->content, '</div>']);
        }
    }
}
