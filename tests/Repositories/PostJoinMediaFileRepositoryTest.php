<?php

namespace Tests\Repositories;

use App\Models\PostJoinMediaFile;
use App\Repositories\PostJoinMediaFileRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class PostJoinMediaFileRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected PostJoinMediaFileRepository $postJoinMediaFileRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->postJoinMediaFileRepo = app(PostJoinMediaFileRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_post_join_media_file()
    {
        $postJoinMediaFile = PostJoinMediaFile::factory()->make()->toArray();

        $createdPostJoinMediaFile = $this->postJoinMediaFileRepo->create($postJoinMediaFile);

        $createdPostJoinMediaFile = $createdPostJoinMediaFile->toArray();
        $this->assertArrayHasKey('id', $createdPostJoinMediaFile);
        $this->assertNotNull($createdPostJoinMediaFile['id'], 'Created PostJoinMediaFile must have id specified');
        $this->assertNotNull(PostJoinMediaFile::find($createdPostJoinMediaFile['id']), 'PostJoinMediaFile with given id must be in DB');
        $this->assertModelData($postJoinMediaFile, $createdPostJoinMediaFile);
    }

    /**
     * @test read
     */
    public function test_read_post_join_media_file()
    {
        $postJoinMediaFile = PostJoinMediaFile::factory()->create();

        $dbPostJoinMediaFile = $this->postJoinMediaFileRepo->find($postJoinMediaFile->id);

        $dbPostJoinMediaFile = $dbPostJoinMediaFile->toArray();
        $this->assertModelData($postJoinMediaFile->toArray(), $dbPostJoinMediaFile);
    }

    /**
     * @test update
     */
    public function test_update_post_join_media_file()
    {
        $postJoinMediaFile = PostJoinMediaFile::factory()->create();
        $fakePostJoinMediaFile = PostJoinMediaFile::factory()->make()->toArray();

        $updatedPostJoinMediaFile = $this->postJoinMediaFileRepo->update($fakePostJoinMediaFile, $postJoinMediaFile->id);

        $this->assertModelData($fakePostJoinMediaFile, $updatedPostJoinMediaFile->toArray());
        $dbPostJoinMediaFile = $this->postJoinMediaFileRepo->find($postJoinMediaFile->id);
        $this->assertModelData($fakePostJoinMediaFile, $dbPostJoinMediaFile->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_post_join_media_file()
    {
        $postJoinMediaFile = PostJoinMediaFile::factory()->create();

        $resp = $this->postJoinMediaFileRepo->delete($postJoinMediaFile->id);

        $this->assertTrue($resp);
        $this->assertNull(PostJoinMediaFile::find($postJoinMediaFile->id), 'PostJoinMediaFile should not exist in DB');
    }
}
