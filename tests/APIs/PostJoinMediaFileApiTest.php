<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\PostJoinMediaFile;

class PostJoinMediaFileApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_post_join_media_file()
    {
        $postJoinMediaFile = PostJoinMediaFile::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/post-join-media-files', $postJoinMediaFile
        );

        $this->assertApiResponse($postJoinMediaFile);
    }

    /**
     * @test
     */
    public function test_read_post_join_media_file()
    {
        $postJoinMediaFile = PostJoinMediaFile::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/post-join-media-files/'.$postJoinMediaFile->id
        );

        $this->assertApiResponse($postJoinMediaFile->toArray());
    }

    /**
     * @test
     */
    public function test_update_post_join_media_file()
    {
        $postJoinMediaFile = PostJoinMediaFile::factory()->create();
        $editedPostJoinMediaFile = PostJoinMediaFile::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/post-join-media-files/'.$postJoinMediaFile->id,
            $editedPostJoinMediaFile
        );

        $this->assertApiResponse($editedPostJoinMediaFile);
    }

    /**
     * @test
     */
    public function test_delete_post_join_media_file()
    {
        $postJoinMediaFile = PostJoinMediaFile::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/post-join-media-files/'.$postJoinMediaFile->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/post-join-media-files/'.$postJoinMediaFile->id
        );

        $this->response->assertStatus(404);
    }
}
