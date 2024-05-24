<?php

namespace Tests\Repositories;

use App\Models\UserJoinGroup;
use App\Repositories\UserJoinGroupRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class UserJoinGroupRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected UserJoinGroupRepository $userJoinGroupRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->userJoinGroupRepo = app(UserJoinGroupRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_user_join_group()
    {
        $userJoinGroup = UserJoinGroup::factory()->make()->toArray();

        $createdUserJoinGroup = $this->userJoinGroupRepo->create($userJoinGroup);

        $createdUserJoinGroup = $createdUserJoinGroup->toArray();
        $this->assertArrayHasKey('id', $createdUserJoinGroup);
        $this->assertNotNull($createdUserJoinGroup['id'], 'Created UserJoinGroup must have id specified');
        $this->assertNotNull(UserJoinGroup::find($createdUserJoinGroup['id']), 'UserJoinGroup with given id must be in DB');
        $this->assertModelData($userJoinGroup, $createdUserJoinGroup);
    }

    /**
     * @test read
     */
    public function test_read_user_join_group()
    {
        $userJoinGroup = UserJoinGroup::factory()->create();

        $dbUserJoinGroup = $this->userJoinGroupRepo->find($userJoinGroup->id);

        $dbUserJoinGroup = $dbUserJoinGroup->toArray();
        $this->assertModelData($userJoinGroup->toArray(), $dbUserJoinGroup);
    }

    /**
     * @test update
     */
    public function test_update_user_join_group()
    {
        $userJoinGroup = UserJoinGroup::factory()->create();
        $fakeUserJoinGroup = UserJoinGroup::factory()->make()->toArray();

        $updatedUserJoinGroup = $this->userJoinGroupRepo->update($fakeUserJoinGroup, $userJoinGroup->id);

        $this->assertModelData($fakeUserJoinGroup, $updatedUserJoinGroup->toArray());
        $dbUserJoinGroup = $this->userJoinGroupRepo->find($userJoinGroup->id);
        $this->assertModelData($fakeUserJoinGroup, $dbUserJoinGroup->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_user_join_group()
    {
        $userJoinGroup = UserJoinGroup::factory()->create();

        $resp = $this->userJoinGroupRepo->delete($userJoinGroup->id);

        $this->assertTrue($resp);
        $this->assertNull(UserJoinGroup::find($userJoinGroup->id), 'UserJoinGroup should not exist in DB');
    }
}
