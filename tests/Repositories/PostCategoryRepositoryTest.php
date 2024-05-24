<?php

namespace Tests\Repositories;

use App\Models\PostCategory;
use App\Repositories\PostCategoryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class PostCategoryRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected PostCategoryRepository $postCategoryRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->postCategoryRepo = app(PostCategoryRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_post_category()
    {
        $postCategory = PostCategory::factory()->make()->toArray();

        $createdPostCategory = $this->postCategoryRepo->create($postCategory);

        $createdPostCategory = $createdPostCategory->toArray();
        $this->assertArrayHasKey('id', $createdPostCategory);
        $this->assertNotNull($createdPostCategory['id'], 'Created PostCategory must have id specified');
        $this->assertNotNull(PostCategory::find($createdPostCategory['id']), 'PostCategory with given id must be in DB');
        $this->assertModelData($postCategory, $createdPostCategory);
    }

    /**
     * @test read
     */
    public function test_read_post_category()
    {
        $postCategory = PostCategory::factory()->create();

        $dbPostCategory = $this->postCategoryRepo->find($postCategory->id);

        $dbPostCategory = $dbPostCategory->toArray();
        $this->assertModelData($postCategory->toArray(), $dbPostCategory);
    }

    /**
     * @test update
     */
    public function test_update_post_category()
    {
        $postCategory = PostCategory::factory()->create();
        $fakePostCategory = PostCategory::factory()->make()->toArray();

        $updatedPostCategory = $this->postCategoryRepo->update($fakePostCategory, $postCategory->id);

        $this->assertModelData($fakePostCategory, $updatedPostCategory->toArray());
        $dbPostCategory = $this->postCategoryRepo->find($postCategory->id);
        $this->assertModelData($fakePostCategory, $dbPostCategory->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_post_category()
    {
        $postCategory = PostCategory::factory()->create();

        $resp = $this->postCategoryRepo->delete($postCategory->id);

        $this->assertTrue($resp);
        $this->assertNull(PostCategory::find($postCategory->id), 'PostCategory should not exist in DB');
    }
}
