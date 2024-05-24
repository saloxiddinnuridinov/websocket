<?php

namespace Tests\Repositories;

use App\Models\LessonJoinMediaFile;
use App\Repositories\LessonJoinMediaFileRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class LessonJoinMediaFileRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected LessonJoinMediaFileRepository $lessonJoinMediaFileRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->lessonJoinMediaFileRepo = app(LessonJoinMediaFileRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_lesson_join_media_file()
    {
        $lessonJoinMediaFile = LessonJoinMediaFile::factory()->make()->toArray();

        $createdLessonJoinMediaFile = $this->lessonJoinMediaFileRepo->create($lessonJoinMediaFile);

        $createdLessonJoinMediaFile = $createdLessonJoinMediaFile->toArray();
        $this->assertArrayHasKey('id', $createdLessonJoinMediaFile);
        $this->assertNotNull($createdLessonJoinMediaFile['id'], 'Created LessonJoinMediaFile must have id specified');
        $this->assertNotNull(LessonJoinMediaFile::find($createdLessonJoinMediaFile['id']), 'LessonJoinMediaFile with given id must be in DB');
        $this->assertModelData($lessonJoinMediaFile, $createdLessonJoinMediaFile);
    }

    /**
     * @test read
     */
    public function test_read_lesson_join_media_file()
    {
        $lessonJoinMediaFile = LessonJoinMediaFile::factory()->create();

        $dbLessonJoinMediaFile = $this->lessonJoinMediaFileRepo->find($lessonJoinMediaFile->id);

        $dbLessonJoinMediaFile = $dbLessonJoinMediaFile->toArray();
        $this->assertModelData($lessonJoinMediaFile->toArray(), $dbLessonJoinMediaFile);
    }

    /**
     * @test update
     */
    public function test_update_lesson_join_media_file()
    {
        $lessonJoinMediaFile = LessonJoinMediaFile::factory()->create();
        $fakeLessonJoinMediaFile = LessonJoinMediaFile::factory()->make()->toArray();

        $updatedLessonJoinMediaFile = $this->lessonJoinMediaFileRepo->update($fakeLessonJoinMediaFile, $lessonJoinMediaFile->id);

        $this->assertModelData($fakeLessonJoinMediaFile, $updatedLessonJoinMediaFile->toArray());
        $dbLessonJoinMediaFile = $this->lessonJoinMediaFileRepo->find($lessonJoinMediaFile->id);
        $this->assertModelData($fakeLessonJoinMediaFile, $dbLessonJoinMediaFile->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_lesson_join_media_file()
    {
        $lessonJoinMediaFile = LessonJoinMediaFile::factory()->create();

        $resp = $this->lessonJoinMediaFileRepo->delete($lessonJoinMediaFile->id);

        $this->assertTrue($resp);
        $this->assertNull(LessonJoinMediaFile::find($lessonJoinMediaFile->id), 'LessonJoinMediaFile should not exist in DB');
    }
}
