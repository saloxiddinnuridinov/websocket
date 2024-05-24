<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Duration;

class DurationApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_duration()
    {
        $duration = Duration::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/durations', $duration
        );

        $this->assertApiResponse($duration);
    }

    /**
     * @test
     */
    public function test_read_duration()
    {
        $duration = Duration::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/durations/'.$duration->id
        );

        $this->assertApiResponse($duration->toArray());
    }

    /**
     * @test
     */
    public function test_update_duration()
    {
        $duration = Duration::factory()->create();
        $editedDuration = Duration::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/durations/'.$duration->id,
            $editedDuration
        );

        $this->assertApiResponse($editedDuration);
    }

    /**
     * @test
     */
    public function test_delete_duration()
    {
        $duration = Duration::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/durations/'.$duration->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/durations/'.$duration->id
        );

        $this->response->assertStatus(404);
    }
}
