<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products()
    {
        Category::factory()->hasProducts(5)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                 ->assertJsonStructure(['data', 'links', 'meta']);
    }

    public function test_can_filter_products_by_category()
    {
        $catA = Category::factory()->create();
        $catB = Category::factory()->create();

        Product::factory(3)->create(['category_id' => $catA->id]);
        Product::factory(2)->create(['category_id' => $catB->id]);

        $response = $this->getJson('/api/products?category=' . $catB->id);

        $response->assertStatus(200);
        $this->assertCount(2, $response->json('data'));
    }

    public function test_can_show_single_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson('/api/products/' . $product->id);

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $product->id]);
    }
}
