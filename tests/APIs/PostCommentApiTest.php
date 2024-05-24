<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\PostComment;

class PostCommentApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_post_comment()
    {
        $postComment = PostComment::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/post-comments', $postComment
        );

        $this->assertApiResponse($postComment);
    }

    /**
     * @test
     */
    public function test_read_post_comment()
    {
        $postComment = PostComment::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/post-comments/'.$postComment->id
        );

        $this->assertApiResponse($postComment->toArray());
    }

    /**
     * @test
     */
    public function test_update_post_comment()
    {
        $postComment = PostComment::factory()->create();
        $editedPostComment = PostComment::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/post-comments/'.$postComment->id,
            $editedPostComment
        );

        $this->assertApiResponse($editedPostComment);
    }

    /**
     * @test
     */
    public function test_delete_post_comment()
    {
        $postComment = PostComment::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/post-comments/'.$postComment->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/post-comments/'.$postComment->id
        );

        $this->response->assertStatus(404);
    }
}
