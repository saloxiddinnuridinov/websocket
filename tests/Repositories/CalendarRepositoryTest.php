<?php

namespace Tests\Repositories;

use App\Models\Event;
use App\Repositories\EventRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class CalendarRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    protected EventRepository $calendarRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->calendarRepo = app(EventRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_calendar()
    {
        $calendar = Event::factory()->make()->toArray();

        $createdCalendar = $this->calendarRepo->create($calendar);

        $createdCalendar = $createdCalendar->toArray();
        $this->assertArrayHasKey('id', $createdCalendar);
        $this->assertNotNull($createdCalendar['id'], 'Created Event must have id specified');
        $this->assertNotNull(Event::find($createdCalendar['id']), 'Event with given id must be in DB');
        $this->assertModelData($calendar, $createdCalendar);
    }

    /**
     * @test read
     */
    public function test_read_calendar()
    {
        $calendar = Event::factory()->create();

        $dbCalendar = $this->calendarRepo->find($calendar->id);

        $dbCalendar = $dbCalendar->toArray();
        $this->assertModelData($calendar->toArray(), $dbCalendar);
    }

    /**
     * @test update
     */
    public function test_update_calendar()
    {
        $calendar = Event::factory()->create();
        $fakeCalendar = Event::factory()->make()->toArray();

        $updatedCalendar = $this->calendarRepo->update($fakeCalendar, $calendar->id);

        $this->assertModelData($fakeCalendar, $updatedCalendar->toArray());
        $dbCalendar = $this->calendarRepo->find($calendar->id);
        $this->assertModelData($fakeCalendar, $dbCalendar->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_calendar()
    {
        $calendar = Event::factory()->create();

        $resp = $this->calendarRepo->delete($calendar->id);

        $this->assertTrue($resp);
        $this->assertNull(Event::find($calendar->id), 'Event should not exist in DB');
    }
}
