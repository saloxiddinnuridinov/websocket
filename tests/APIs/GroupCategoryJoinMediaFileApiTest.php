<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\GroupCategoryJoinMediaFile;

class GroupCategoryJoinMediaFileApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_group_category_join_media_file()
    {
        $groupCategoryJoinMediaFile = GroupCategoryJoinMediaFile::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/group-category-join-media-files', $groupCategoryJoinMediaFile
        );

        $this->assertApiResponse($groupCategoryJoinMediaFile);
    }

    /**
     * @test
     */
    public function test_read_group_category_join_media_file()
    {
        $groupCategoryJoinMediaFile = GroupCategoryJoinMediaFile::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/group-category-join-media-files/'.$groupCategoryJoinMediaFile->id
        );

        $this->assertApiResponse($groupCategoryJoinMediaFile->toArray());
    }

    /**
     * @test
     */
    public function test_update_group_category_join_media_file()
    {
        $groupCategoryJoinMediaFile = GroupCategoryJoinMediaFile::factory()->create();
        $editedGroupCategoryJoinMediaFile = GroupCategoryJoinMediaFile::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/group-category-join-media-files/'.$groupCategoryJoinMediaFile->id,
            $editedGroupCategoryJoinMediaFile
        );

        $this->assertApiResponse($editedGroupCategoryJoinMediaFile);
    }

    /**
     * @test
     */
    public function test_delete_group_category_join_media_file()
    {
        $groupCategoryJoinMediaFile = GroupCategoryJoinMediaFile::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/group-category-join-media-files/'.$groupCategoryJoinMediaFile->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/group-category-join-media-files/'.$groupCategoryJoinMediaFile->id
        );

        $this->response->assertStatus(404);
    }
}
