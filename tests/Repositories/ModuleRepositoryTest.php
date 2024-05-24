<?php

namespace Tests\Repositories;

use App\Models\Module;
use App\Repositories\ModuleRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ModuleRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected ModuleRepository $moduleRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->moduleRepo = app(ModuleRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_module()
    {
        $module = Module::factory()->make()->toArray();

        $createdModule = $this->moduleRepo->create($module);

        $createdModule = $createdModule->toArray();
        $this->assertArrayHasKey('id', $createdModule);
        $this->assertNotNull($createdModule['id'], 'Created Module must have id specified');
        $this->assertNotNull(Module::find($createdModule['id']), 'Module with given id must be in DB');
        $this->assertModelData($module, $createdModule);
    }

    /**
     * @test read
     */
    public function test_read_module()
    {
        $module = Module::factory()->create();

        $dbModule = $this->moduleRepo->find($module->id);

        $dbModule = $dbModule->toArray();
        $this->assertModelData($module->toArray(), $dbModule);
    }

    /**
     * @test update
     */
    public function test_update_module()
    {
        $module = Module::factory()->create();
        $fakeModule = Module::factory()->make()->toArray();

        $updatedModule = $this->moduleRepo->update($fakeModule, $module->id);

        $this->assertModelData($fakeModule, $updatedModule->toArray());
        $dbModule = $this->moduleRepo->find($module->id);
        $this->assertModelData($fakeModule, $dbModule->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_module()
    {
        $module = Module::factory()->create();

        $resp = $this->moduleRepo->delete($module->id);

        $this->assertTrue($resp);
        $this->assertNull(Module::find($module->id), 'Module should not exist in DB');
    }
}
