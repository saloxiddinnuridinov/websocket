<?php

namespace Tests\Repositories;

use App\Models\PostComment;
use App\Repositories\PostCommentRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class PostCommentRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected PostCommentRepository $postCommentRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->postCommentRepo = app(PostCommentRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_post_comment()
    {
        $postComment = PostComment::factory()->make()->toArray();

        $createdPostComment = $this->postCommentRepo->create($postComment);

        $createdPostComment = $createdPostComment->toArray();
        $this->assertArrayHasKey('id', $createdPostComment);
        $this->assertNotNull($createdPostComment['id'], 'Created PostComment must have id specified');
        $this->assertNotNull(PostComment::find($createdPostComment['id']), 'PostComment with given id must be in DB');
        $this->assertModelData($postComment, $createdPostComment);
    }

    /**
     * @test read
     */
    public function test_read_post_comment()
    {
        $postComment = PostComment::factory()->create();

        $dbPostComment = $this->postCommentRepo->find($postComment->id);

        $dbPostComment = $dbPostComment->toArray();
        $this->assertModelData($postComment->toArray(), $dbPostComment);
    }

    /**
     * @test update
     */
    public function test_update_post_comment()
    {
        $postComment = PostComment::factory()->create();
        $fakePostComment = PostComment::factory()->make()->toArray();

        $updatedPostComment = $this->postCommentRepo->update($fakePostComment, $postComment->id);

        $this->assertModelData($fakePostComment, $updatedPostComment->toArray());
        $dbPostComment = $this->postCommentRepo->find($postComment->id);
        $this->assertModelData($fakePostComment, $dbPostComment->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_post_comment()
    {
        $postComment = PostComment::factory()->create();

        $resp = $this->postCommentRepo->delete($postComment->id);

        $this->assertTrue($resp);
        $this->assertNull(PostComment::find($postComment->id), 'PostComment should not exist in DB');
    }
}
