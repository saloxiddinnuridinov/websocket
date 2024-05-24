<?php

namespace Tests\Repositories;

use App\Models\GroupCategoryJoinMediaFile;
use App\Repositories\GroupCategoryJoinMediaFileRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class GroupCategoryJoinMediaFileRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected GroupCategoryJoinMediaFileRepository $groupCategoryJoinMediaFileRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->groupCategoryJoinMediaFileRepo = app(GroupCategoryJoinMediaFileRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_group_category_join_media_file()
    {
        $groupCategoryJoinMediaFile = GroupCategoryJoinMediaFile::factory()->make()->toArray();

        $createdGroupCategoryJoinMediaFile = $this->groupCategoryJoinMediaFileRepo->create($groupCategoryJoinMediaFile);

        $createdGroupCategoryJoinMediaFile = $createdGroupCategoryJoinMediaFile->toArray();
        $this->assertArrayHasKey('id', $createdGroupCategoryJoinMediaFile);
        $this->assertNotNull($createdGroupCategoryJoinMediaFile['id'], 'Created GroupCategoryJoinMediaFile must have id specified');
        $this->assertNotNull(GroupCategoryJoinMediaFile::find($createdGroupCategoryJoinMediaFile['id']), 'GroupCategoryJoinMediaFile with given id must be in DB');
        $this->assertModelData($groupCategoryJoinMediaFile, $createdGroupCategoryJoinMediaFile);
    }

    /**
     * @test read
     */
    public function test_read_group_category_join_media_file()
    {
        $groupCategoryJoinMediaFile = GroupCategoryJoinMediaFile::factory()->create();

        $dbGroupCategoryJoinMediaFile = $this->groupCategoryJoinMediaFileRepo->find($groupCategoryJoinMediaFile->id);

        $dbGroupCategoryJoinMediaFile = $dbGroupCategoryJoinMediaFile->toArray();
        $this->assertModelData($groupCategoryJoinMediaFile->toArray(), $dbGroupCategoryJoinMediaFile);
    }

    /**
     * @test update
     */
    public function test_update_group_category_join_media_file()
    {
        $groupCategoryJoinMediaFile = GroupCategoryJoinMediaFile::factory()->create();
        $fakeGroupCategoryJoinMediaFile = GroupCategoryJoinMediaFile::factory()->make()->toArray();

        $updatedGroupCategoryJoinMediaFile = $this->groupCategoryJoinMediaFileRepo->update($fakeGroupCategoryJoinMediaFile, $groupCategoryJoinMediaFile->id);

        $this->assertModelData($fakeGroupCategoryJoinMediaFile, $updatedGroupCategoryJoinMediaFile->toArray());
        $dbGroupCategoryJoinMediaFile = $this->groupCategoryJoinMediaFileRepo->find($groupCategoryJoinMediaFile->id);
        $this->assertModelData($fakeGroupCategoryJoinMediaFile, $dbGroupCategoryJoinMediaFile->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_group_category_join_media_file()
    {
        $groupCategoryJoinMediaFile = GroupCategoryJoinMediaFile::factory()->create();

        $resp = $this->groupCategoryJoinMediaFileRepo->delete($groupCategoryJoinMediaFile->id);

        $this->assertTrue($resp);
        $this->assertNull(GroupCategoryJoinMediaFile::find($groupCategoryJoinMediaFile->id), 'GroupCategoryJoinMediaFile should not exist in DB');
    }
}
