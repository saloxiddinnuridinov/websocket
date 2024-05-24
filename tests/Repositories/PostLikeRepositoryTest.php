<?php

namespace Tests\Repositories;

use App\Models\PostLike;
use App\Repositories\PostLikeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class PostLikeRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected PostLikeRepository $postLikeRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->postLikeRepo = app(PostLikeRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_post_like()
    {
        $postLike = PostLike::factory()->make()->toArray();

        $createdPostLike = $this->postLikeRepo->create($postLike);

        $createdPostLike = $createdPostLike->toArray();
        $this->assertArrayHasKey('id', $createdPostLike);
        $this->assertNotNull($createdPostLike['id'], 'Created PostLike must have id specified');
        $this->assertNotNull(PostLike::find($createdPostLike['id']), 'PostLike with given id must be in DB');
        $this->assertModelData($postLike, $createdPostLike);
    }

    /**
     * @test read
     */
    public function test_read_post_like()
    {
        $postLike = PostLike::factory()->create();

        $dbPostLike = $this->postLikeRepo->find($postLike->id);

        $dbPostLike = $dbPostLike->toArray();
        $this->assertModelData($postLike->toArray(), $dbPostLike);
    }

    /**
     * @test update
     */
    public function test_update_post_like()
    {
        $postLike = PostLike::factory()->create();
        $fakePostLike = PostLike::factory()->make()->toArray();

        $updatedPostLike = $this->postLikeRepo->update($fakePostLike, $postLike->id);

        $this->assertModelData($fakePostLike, $updatedPostLike->toArray());
        $dbPostLike = $this->postLikeRepo->find($postLike->id);
        $this->assertModelData($fakePostLike, $dbPostLike->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_post_like()
    {
        $postLike = PostLike::factory()->create();

        $resp = $this->postLikeRepo->delete($postLike->id);

        $this->assertTrue($resp);
        $this->assertNull(PostLike::find($postLike->id), 'PostLike should not exist in DB');
    }
}
