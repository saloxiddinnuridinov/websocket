<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\UserJoinGroup;

class UserJoinGroupApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_user_join_group()
    {
        $userJoinGroup = UserJoinGroup::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/user-join-groups', $userJoinGroup
        );

        $this->assertApiResponse($userJoinGroup);
    }

    /**
     * @test
     */
    public function test_read_user_join_group()
    {
        $userJoinGroup = UserJoinGroup::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/user-join-groups/'.$userJoinGroup->id
        );

        $this->assertApiResponse($userJoinGroup->toArray());
    }

    /**
     * @test
     */
    public function test_update_user_join_group()
    {
        $userJoinGroup = UserJoinGroup::factory()->create();
        $editedUserJoinGroup = UserJoinGroup::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/user-join-groups/'.$userJoinGroup->id,
            $editedUserJoinGroup
        );

        $this->assertApiResponse($editedUserJoinGroup);
    }

    /**
     * @test
     */
    public function test_delete_user_join_group()
    {
        $userJoinGroup = UserJoinGroup::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/user-join-groups/'.$userJoinGroup->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/user-join-groups/'.$userJoinGroup->id
        );

        $this->response->assertStatus(404);
    }
}
