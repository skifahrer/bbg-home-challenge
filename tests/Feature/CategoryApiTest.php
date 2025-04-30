<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to verify that the API can list all categories.
     *
     * This test creates 4 category records using a factory and then sends
     * a GET request to the '/api/categories' endpoint. It asserts that the
     * response status is 200 (OK) and that the JSON response contains exactly
     * 4 items.
     *
     * @return void
     */
    public function test_can_list_categories()
    {
        Category::factory(4)->create();

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
                 ->assertJsonCount(4);
    }
}
