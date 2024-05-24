<?php

namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Event;

class CalendarApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_calendar()
    {
        $calendar = Event::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/v1/events', $calendar
        );

        $this->assertApiResponse($calendar);
    }

    /**
     * @test
     */
    public function test_read_calendar()
    {
        $calendar = Event::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/v1/events/'.$calendar->id
        );

        $this->assertApiResponse($calendar->toArray());
    }

    /**
     * @test
     */
    public function test_update_calendar()
    {
        $calendar = Event::factory()->create();
        $editedCalendar = Event::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/v1/events/'.$calendar->id,
            $editedCalendar
        );

        $this->assertApiResponse($editedCalendar);
    }

    /**
     * @test
     */
    public function test_delete_calendar()
    {
        $calendar = Event::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/v1/events/'.$calendar->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/v1/events/'.$calendar->id
        );

        $this->response->assertStatus(404);
    }
}
