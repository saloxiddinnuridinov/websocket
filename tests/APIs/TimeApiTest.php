<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Time;

class TimeApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_time()
    {
        $time = Time::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/times', $time
        );

        $this->assertApiResponse($time);
    }

    /**
     * @test
     */
    public function test_read_time()
    {
        $time = Time::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/times/'.$time->id
        );

        $this->assertApiResponse($time->toArray());
    }

    /**
     * @test
     */
    public function test_update_time()
    {
        $time = Time::factory()->create();
        $editedTime = Time::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/times/'.$time->id,
            $editedTime
        );

        $this->assertApiResponse($editedTime);
    }

    /**
     * @test
     */
    public function test_delete_time()
    {
        $time = Time::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/times/'.$time->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/times/'.$time->id
        );

        $this->response->assertStatus(404);
    }
}
