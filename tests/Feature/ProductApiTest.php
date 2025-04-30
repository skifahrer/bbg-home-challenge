<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;

class ProductApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to ensure the API can list products.
     *
     * This test creates a category with 5 associated products using a factory,
     * then sends a GET request to the `/api/products` endpoint. It verifies
     * that the response has a status code of 200 and contains the expected
     * JSON structure for paginated data.
     *
     * @return void
     */
    public function test_can_list_products()
    {
        Category::factory()->hasProducts(5)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                    'current_page',
                    'data',
                    'first_page_url',
                    'from',
                    'last_page',
                    'last_page_url', 
                    'links',
                    'next_page_url',
                    'path',
                    'per_page',
                    'prev_page_url',
                    'to',
                    'total',
                ]);
    }

    /**
     * Test to ensure products can be filtered by category.
     *
     * This test creates two categories and assigns products to each category.
     * It then sends a GET request to the API endpoint with a category filter
     * and verifies that the response contains only the products belonging
     * to the specified category.
     *
     * Steps:
     * - Create two categories using factories.
     * - Create three products for the first category and two for the second.
     * - Send a GET request to the `/api/products` endpoint with a category filter.
     * - Assert that the response status is 200 (OK).
     * - Assert that the response contains the correct number of products for the filtered category.
     * 
     * @return void
     */
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

    /**
     * Test to verify that a single product can be retrieved successfully.
     *
     * This test creates a product using the factory, sends a GET request
     * to the API endpoint for retrieving a single product, and asserts
     * that the response has a 200 HTTP status code and contains the
     * expected product ID in the JSON response.
     *
     * @return void
     */
    public function test_can_show_single_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson('/api/products/' . $product->id);

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $product->id]);
    }
}
