<?php

namespace Tests\Repositories;

use App\Models\ModuleJoinFile;
use App\Repositories\ModuleJoinFileRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class ModuleJoinFileRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected ModuleJoinFileRepository $moduleJoinFileRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->moduleJoinFileRepo = app(ModuleJoinFileRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_module_join_file()
    {
        $moduleJoinFile = ModuleJoinFile::factory()->make()->toArray();

        $createdModuleJoinFile = $this->moduleJoinFileRepo->create($moduleJoinFile);

        $createdModuleJoinFile = $createdModuleJoinFile->toArray();
        $this->assertArrayHasKey('id', $createdModuleJoinFile);
        $this->assertNotNull($createdModuleJoinFile['id'], 'Created ModuleJoinFile must have id specified');
        $this->assertNotNull(ModuleJoinFile::find($createdModuleJoinFile['id']), 'ModuleJoinFile with given id must be in DB');
        $this->assertModelData($moduleJoinFile, $createdModuleJoinFile);
    }

    /**
     * @test read
     */
    public function test_read_module_join_file()
    {
        $moduleJoinFile = ModuleJoinFile::factory()->create();

        $dbModuleJoinFile = $this->moduleJoinFileRepo->find($moduleJoinFile->id);

        $dbModuleJoinFile = $dbModuleJoinFile->toArray();
        $this->assertModelData($moduleJoinFile->toArray(), $dbModuleJoinFile);
    }

    /**
     * @test update
     */
    public function test_update_module_join_file()
    {
        $moduleJoinFile = ModuleJoinFile::factory()->create();
        $fakeModuleJoinFile = ModuleJoinFile::factory()->make()->toArray();

        $updatedModuleJoinFile = $this->moduleJoinFileRepo->update($fakeModuleJoinFile, $moduleJoinFile->id);

        $this->assertModelData($fakeModuleJoinFile, $updatedModuleJoinFile->toArray());
        $dbModuleJoinFile = $this->moduleJoinFileRepo->find($moduleJoinFile->id);
        $this->assertModelData($fakeModuleJoinFile, $dbModuleJoinFile->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_module_join_file()
    {
        $moduleJoinFile = ModuleJoinFile::factory()->create();

        $resp = $this->moduleJoinFileRepo->delete($moduleJoinFile->id);

        $this->assertTrue($resp);
        $this->assertNull(ModuleJoinFile::find($moduleJoinFile->id), 'ModuleJoinFile should not exist in DB');
    }
}
