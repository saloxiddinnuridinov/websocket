<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\LessonJoinMediaFile;

class LessonJoinMediaFileApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_lesson_join_media_file()
    {
        $lessonJoinMediaFile = LessonJoinMediaFile::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/lesson-join-media-files', $lessonJoinMediaFile
        );

        $this->assertApiResponse($lessonJoinMediaFile);
    }

    /**
     * @test
     */
    public function test_read_lesson_join_media_file()
    {
        $lessonJoinMediaFile = LessonJoinMediaFile::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/lesson-join-media-files/'.$lessonJoinMediaFile->id
        );

        $this->assertApiResponse($lessonJoinMediaFile->toArray());
    }

    /**
     * @test
     */
    public function test_update_lesson_join_media_file()
    {
        $lessonJoinMediaFile = LessonJoinMediaFile::factory()->create();
        $editedLessonJoinMediaFile = LessonJoinMediaFile::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/lesson-join-media-files/'.$lessonJoinMediaFile->id,
            $editedLessonJoinMediaFile
        );

        $this->assertApiResponse($editedLessonJoinMediaFile);
    }

    /**
     * @test
     */
    public function test_delete_lesson_join_media_file()
    {
        $lessonJoinMediaFile = LessonJoinMediaFile::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/lesson-join-media-files/'.$lessonJoinMediaFile->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/lesson-join-media-files/'.$lessonJoinMediaFile->id
        );

        $this->response->assertStatus(404);
    }
}
