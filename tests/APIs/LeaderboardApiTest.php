<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Leaderboard;

class LeaderboardApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_leaderboard()
    {
        $leaderboard = Leaderboard::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/leaderboards', $leaderboard
        );

        $this->assertApiResponse($leaderboard);
    }

    /**
     * @test
     */
    public function test_read_leaderboard()
    {
        $leaderboard = Leaderboard::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/leaderboards/'.$leaderboard->id
        );

        $this->assertApiResponse($leaderboard->toArray());
    }

    /**
     * @test
     */
    public function test_update_leaderboard()
    {
        $leaderboard = Leaderboard::factory()->create();
        $editedLeaderboard = Leaderboard::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/leaderboards/'.$leaderboard->id,
            $editedLeaderboard
        );

        $this->assertApiResponse($editedLeaderboard);
    }

    /**
     * @test
     */
    public function test_delete_leaderboard()
    {
        $leaderboard = Leaderboard::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/leaderboards/'.$leaderboard->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/leaderboards/'.$leaderboard->id
        );

        $this->response->assertStatus(404);
    }
}
