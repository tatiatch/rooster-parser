<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;
use DB;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        DB::statement('CREATE TABLE events (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            event_type VARCHAR(255),
            start_time_zulu DATETIME,
            departure_airport VARCHAR(255)
        )');

        DB::table('events')->insert([
            ['event_type' => 'FLT', 'start_time_zulu' => '2022-01-15 10:00:00', 'departure_airport' => 'CPH'],
            ['event_type' => 'SBY', 'start_time_zulu' => '2022-01-17 12:00:00', 'departure_airport' => 'JFK'],
            ['event_type' => 'FLT', 'start_time_zulu' => '2022-01-19 14:00:00', 'departure_airport' => 'CPH'],
        ]);
    }

    public function testGetEventsBetweenDates()
    {
        $response = $this->get('/events-between?start_date=2022-01-14&end_date=2022-01-18');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    public function testGetFlightsNextWeek()
    {
        $response = $this->get('/flights-next-week');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    public function testGetStandbyNextWeek()
    {
        $response = $this->get('/standby-next-week');

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    public function testGetFlightsByLocation()
    {
        $response = $this->get('/flights-by-location?location=CPH');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }
}
