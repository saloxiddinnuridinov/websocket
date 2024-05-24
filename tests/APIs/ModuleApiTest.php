<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Module;

class ModuleApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_module()
    {
        $module = Module::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/modules', $module
        );

        $this->assertApiResponse($module);
    }

    /**
     * @test
     */
    public function test_read_module()
    {
        $module = Module::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/modules/'.$module->id
        );

        $this->assertApiResponse($module->toArray());
    }

    /**
     * @test
     */
    public function test_update_module()
    {
        $module = Module::factory()->create();
        $editedModule = Module::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/modules/'.$module->id,
            $editedModule
        );

        $this->assertApiResponse($editedModule);
    }

    /**
     * @test
     */
    public function test_delete_module()
    {
        $module = Module::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/modules/'.$module->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/modules/'.$module->id
        );

        $this->response->assertStatus(404);
    }
}
