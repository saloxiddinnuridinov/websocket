<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Set;

class SetApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_set()
    {
        $set = Set::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/sets', $set
        );

        $this->assertApiResponse($set);
    }

    /**
     * @test
     */
    public function test_read_set()
    {
        $set = Set::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/sets/'.$set->id
        );

        $this->assertApiResponse($set->toArray());
    }

    /**
     * @test
     */
    public function test_update_set()
    {
        $set = Set::factory()->create();
        $editedSet = Set::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/sets/'.$set->id,
            $editedSet
        );

        $this->assertApiResponse($editedSet);
    }

    /**
     * @test
     */
    public function test_delete_set()
    {
        $set = Set::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/sets/'.$set->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/sets/'.$set->id
        );

        $this->response->assertStatus(404);
    }
}
