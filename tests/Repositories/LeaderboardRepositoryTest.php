<?php

namespace Tests\Repositories;

use App\Models\Leaderboard;
use App\Repositories\LeaderboardRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class LeaderboardRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected LeaderboardRepository $leaderboardRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->leaderboardRepo = app(LeaderboardRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_leaderboard()
    {
        $leaderboard = Leaderboard::factory()->make()->toArray();

        $createdLeaderboard = $this->leaderboardRepo->create($leaderboard);

        $createdLeaderboard = $createdLeaderboard->toArray();
        $this->assertArrayHasKey('id', $createdLeaderboard);
        $this->assertNotNull($createdLeaderboard['id'], 'Created Leaderboard must have id specified');
        $this->assertNotNull(Leaderboard::find($createdLeaderboard['id']), 'Leaderboard with given id must be in DB');
        $this->assertModelData($leaderboard, $createdLeaderboard);
    }

    /**
     * @test read
     */
    public function test_read_leaderboard()
    {
        $leaderboard = Leaderboard::factory()->create();

        $dbLeaderboard = $this->leaderboardRepo->find($leaderboard->id);

        $dbLeaderboard = $dbLeaderboard->toArray();
        $this->assertModelData($leaderboard->toArray(), $dbLeaderboard);
    }

    /**
     * @test update
     */
    public function test_update_leaderboard()
    {
        $leaderboard = Leaderboard::factory()->create();
        $fakeLeaderboard = Leaderboard::factory()->make()->toArray();

        $updatedLeaderboard = $this->leaderboardRepo->update($fakeLeaderboard, $leaderboard->id);

        $this->assertModelData($fakeLeaderboard, $updatedLeaderboard->toArray());
        $dbLeaderboard = $this->leaderboardRepo->find($leaderboard->id);
        $this->assertModelData($fakeLeaderboard, $dbLeaderboard->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_leaderboard()
    {
        $leaderboard = Leaderboard::factory()->create();

        $resp = $this->leaderboardRepo->delete($leaderboard->id);

        $this->assertTrue($resp);
        $this->assertNull(Leaderboard::find($leaderboard->id), 'Leaderboard should not exist in DB');
    }
}
