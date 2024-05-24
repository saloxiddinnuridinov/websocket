<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\GroupCategory;

class GroupCategoryApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_group_category()
    {
        $groupCategory = GroupCategory::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/group-categories', $groupCategory
        );

        $this->assertApiResponse($groupCategory);
    }

    /**
     * @test
     */
    public function test_read_group_category()
    {
        $groupCategory = GroupCategory::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/group-categories/'.$groupCategory->id
        );

        $this->assertApiResponse($groupCategory->toArray());
    }

    /**
     * @test
     */
    public function test_update_group_category()
    {
        $groupCategory = GroupCategory::factory()->create();
        $editedGroupCategory = GroupCategory::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/group-categories/'.$groupCategory->id,
            $editedGroupCategory
        );

        $this->assertApiResponse($editedGroupCategory);
    }

    /**
     * @test
     */
    public function test_delete_group_category()
    {
        $groupCategory = GroupCategory::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/group-categories/'.$groupCategory->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/group-categories/'.$groupCategory->id
        );

        $this->response->assertStatus(404);
    }
}
