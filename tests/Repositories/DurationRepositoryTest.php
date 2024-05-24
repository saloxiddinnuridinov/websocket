<?php

namespace Tests\Repositories;

use App\Models\Duration;
use App\Repositories\DurationRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class DurationRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected DurationRepository $durationRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->durationRepo = app(DurationRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_duration()
    {
        $duration = Duration::factory()->make()->toArray();

        $createdDuration = $this->durationRepo->create($duration);

        $createdDuration = $createdDuration->toArray();
        $this->assertArrayHasKey('id', $createdDuration);
        $this->assertNotNull($createdDuration['id'], 'Created Duration must have id specified');
        $this->assertNotNull(Duration::find($createdDuration['id']), 'Duration with given id must be in DB');
        $this->assertModelData($duration, $createdDuration);
    }

    /**
     * @test read
     */
    public function test_read_duration()
    {
        $duration = Duration::factory()->create();

        $dbDuration = $this->durationRepo->find($duration->id);

        $dbDuration = $dbDuration->toArray();
        $this->assertModelData($duration->toArray(), $dbDuration);
    }

    /**
     * @test update
     */
    public function test_update_duration()
    {
        $duration = Duration::factory()->create();
        $fakeDuration = Duration::factory()->make()->toArray();

        $updatedDuration = $this->durationRepo->update($fakeDuration, $duration->id);

        $this->assertModelData($fakeDuration, $updatedDuration->toArray());
        $dbDuration = $this->durationRepo->find($duration->id);
        $this->assertModelData($fakeDuration, $dbDuration->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_duration()
    {
        $duration = Duration::factory()->create();

        $resp = $this->durationRepo->delete($duration->id);

        $this->assertTrue($resp);
        $this->assertNull(Duration::find($duration->id), 'Duration should not exist in DB');
    }
}
