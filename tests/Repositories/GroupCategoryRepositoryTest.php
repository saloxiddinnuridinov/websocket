<?php

namespace Tests\Repositories;

use App\Models\GroupCategory;
use App\Repositories\GroupCategoryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class GroupCategoryRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected GroupCategoryRepository $groupCategoryRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->groupCategoryRepo = app(GroupCategoryRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_group_category()
    {
        $groupCategory = GroupCategory::factory()->make()->toArray();

        $createdGroupCategory = $this->groupCategoryRepo->create($groupCategory);

        $createdGroupCategory = $createdGroupCategory->toArray();
        $this->assertArrayHasKey('id', $createdGroupCategory);
        $this->assertNotNull($createdGroupCategory['id'], 'Created GroupCategory must have id specified');
        $this->assertNotNull(GroupCategory::find($createdGroupCategory['id']), 'GroupCategory with given id must be in DB');
        $this->assertModelData($groupCategory, $createdGroupCategory);
    }

    /**
     * @test read
     */
    public function test_read_group_category()
    {
        $groupCategory = GroupCategory::factory()->create();

        $dbGroupCategory = $this->groupCategoryRepo->find($groupCategory->id);

        $dbGroupCategory = $dbGroupCategory->toArray();
        $this->assertModelData($groupCategory->toArray(), $dbGroupCategory);
    }

    /**
     * @test update
     */
    public function test_update_group_category()
    {
        $groupCategory = GroupCategory::factory()->create();
        $fakeGroupCategory = GroupCategory::factory()->make()->toArray();

        $updatedGroupCategory = $this->groupCategoryRepo->update($fakeGroupCategory, $groupCategory->id);

        $this->assertModelData($fakeGroupCategory, $updatedGroupCategory->toArray());
        $dbGroupCategory = $this->groupCategoryRepo->find($groupCategory->id);
        $this->assertModelData($fakeGroupCategory, $dbGroupCategory->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_group_category()
    {
        $groupCategory = GroupCategory::factory()->create();

        $resp = $this->groupCategoryRepo->delete($groupCategory->id);

        $this->assertTrue($resp);
        $this->assertNull(GroupCategory::find($groupCategory->id), 'GroupCategory should not exist in DB');
    }
}
