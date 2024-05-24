<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\PostLike;

class PostLikeApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_post_like()
    {
        $postLike = PostLike::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/post-likes', $postLike
        );

        $this->assertApiResponse($postLike);
    }

    /**
     * @test
     */
    public function test_read_post_like()
    {
        $postLike = PostLike::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/post-likes/'.$postLike->id
        );

        $this->assertApiResponse($postLike->toArray());
    }

    /**
     * @test
     */
    public function test_update_post_like()
    {
        $postLike = PostLike::factory()->create();
        $editedPostLike = PostLike::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/post-likes/'.$postLike->id,
            $editedPostLike
        );

        $this->assertApiResponse($editedPostLike);
    }

    /**
     * @test
     */
    public function test_delete_post_like()
    {
        $postLike = PostLike::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/post-likes/'.$postLike->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/post-likes/'.$postLike->id
        );

        $this->response->assertStatus(404);
    }
}
