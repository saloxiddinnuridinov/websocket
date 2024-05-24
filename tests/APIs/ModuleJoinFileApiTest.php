<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\ModuleJoinFile;

class ModuleJoinFileApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_module_join_file()
    {
        $moduleJoinFile = ModuleJoinFile::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/module-join-files', $moduleJoinFile
        );

        $this->assertApiResponse($moduleJoinFile);
    }

    /**
     * @test
     */
    public function test_read_module_join_file()
    {
        $moduleJoinFile = ModuleJoinFile::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/module-join-files/'.$moduleJoinFile->id
        );

        $this->assertApiResponse($moduleJoinFile->toArray());
    }

    /**
     * @test
     */
    public function test_update_module_join_file()
    {
        $moduleJoinFile = ModuleJoinFile::factory()->create();
        $editedModuleJoinFile = ModuleJoinFile::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/module-join-files/'.$moduleJoinFile->id,
            $editedModuleJoinFile
        );

        $this->assertApiResponse($editedModuleJoinFile);
    }

    /**
     * @test
     */
    public function test_delete_module_join_file()
    {
        $moduleJoinFile = ModuleJoinFile::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/module-join-files/'.$moduleJoinFile->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/module-join-files/'.$moduleJoinFile->id
        );

        $this->response->assertStatus(404);
    }
}
