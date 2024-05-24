<?php

namespace Tests\Repositories;

use App\Models\Time;
use App\Repositories\TimeRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TimeRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected TimeRepository $timeRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->timeRepo = app(TimeRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_time()
    {
        $time = Time::factory()->make()->toArray();

        $createdTime = $this->timeRepo->create($time);

        $createdTime = $createdTime->toArray();
        $this->assertArrayHasKey('id', $createdTime);
        $this->assertNotNull($createdTime['id'], 'Created Time must have id specified');
        $this->assertNotNull(Time::find($createdTime['id']), 'Time with given id must be in DB');
        $this->assertModelData($time, $createdTime);
    }

    /**
     * @test read
     */
    public function test_read_time()
    {
        $time = Time::factory()->create();

        $dbTime = $this->timeRepo->find($time->id);

        $dbTime = $dbTime->toArray();
        $this->assertModelData($time->toArray(), $dbTime);
    }

    /**
     * @test update
     */
    public function test_update_time()
    {
        $time = Time::factory()->create();
        $fakeTime = Time::factory()->make()->toArray();

        $updatedTime = $this->timeRepo->update($fakeTime, $time->id);

        $this->assertModelData($fakeTime, $updatedTime->toArray());
        $dbTime = $this->timeRepo->find($time->id);
        $this->assertModelData($fakeTime, $dbTime->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_time()
    {
        $time = Time::factory()->create();

        $resp = $this->timeRepo->delete($time->id);

        $this->assertTrue($resp);
        $this->assertNull(Time::find($time->id), 'Time should not exist in DB');
    }
}
