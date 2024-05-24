<?php

namespace Tests\Repositories;

use App\Models\Set;
use App\Repositories\SetRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class SetRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected SetRepository $setRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->setRepo = app(SetRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_set()
    {
        $set = Set::factory()->make()->toArray();

        $createdSet = $this->setRepo->create($set);

        $createdSet = $createdSet->toArray();
        $this->assertArrayHasKey('id', $createdSet);
        $this->assertNotNull($createdSet['id'], 'Created Set must have id specified');
        $this->assertNotNull(Set::find($createdSet['id']), 'Set with given id must be in DB');
        $this->assertModelData($set, $createdSet);
    }

    /**
     * @test read
     */
    public function test_read_set()
    {
        $set = Set::factory()->create();

        $dbSet = $this->setRepo->find($set->id);

        $dbSet = $dbSet->toArray();
        $this->assertModelData($set->toArray(), $dbSet);
    }

    /**
     * @test update
     */
    public function test_update_set()
    {
        $set = Set::factory()->create();
        $fakeSet = Set::factory()->make()->toArray();

        $updatedSet = $this->setRepo->update($fakeSet, $set->id);

        $this->assertModelData($fakeSet, $updatedSet->toArray());
        $dbSet = $this->setRepo->find($set->id);
        $this->assertModelData($fakeSet, $dbSet->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_set()
    {
        $set = Set::factory()->create();

        $resp = $this->setRepo->delete($set->id);

        $this->assertTrue($resp);
        $this->assertNull(Set::find($set->id), 'Set should not exist in DB');
    }
}
