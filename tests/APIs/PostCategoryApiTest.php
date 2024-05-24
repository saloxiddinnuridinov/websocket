<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\PostCategory;

class PostCategoryApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_post_category()
    {
        $postCategory = PostCategory::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/post-categories', $postCategory
        );

        $this->assertApiResponse($postCategory);
    }

    /**
     * @test
     */
    public function test_read_post_category()
    {
        $postCategory = PostCategory::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/post-categories/'.$postCategory->id
        );

        $this->assertApiResponse($postCategory->toArray());
    }

    /**
     * @test
     */
    public function test_update_post_category()
    {
        $postCategory = PostCategory::factory()->create();
        $editedPostCategory = PostCategory::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/post-categories/'.$postCategory->id,
            $editedPostCategory
        );

        $this->assertApiResponse($editedPostCategory);
    }

    /**
     * @test
     */
    public function test_delete_post_category()
    {
        $postCategory = PostCategory::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/post-categories/'.$postCategory->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/post-categories/'.$postCategory->id
        );

        $this->response->assertStatus(404);
    }
}
