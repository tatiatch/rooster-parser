<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventController extends Controller
{
    // Get all events between date x and y
    public function getEventsBetweenDates(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $events = DB::table('events')
            ->whereBetween('start_time_zulu', [$startDate, $endDate])
            ->get();

        return response()->json($events);
    }

    // Get all flights for the next week (from 14 Jan 2022)
    public function getFlightsNextWeek()
    {
        $startDate = '2022-01-14';
        $endDate = '2022-01-21';

        $flights = DB::table('events')
            ->where('event_type', 'FLT')
            ->whereBetween('start_time_zulu', [$startDate, $endDate])
            ->get();

        return response()->json($flights);
    }

    // Get all standby events for the next week (from 14 Jan 2022)
    public function getStandbyNextWeek()
    {
        $startDate = '2022-01-14';
        $endDate = '2022-01-21';

        $standbys = DB::table('events')
            ->where('event_type', 'SBY')
            ->whereBetween('start_time_zulu', [$startDate, $endDate])
            ->get();

        return response()->json($standbys);
    }

    // Get all flights that start at the given location
    public function getFlightsByLocation(Request $request)
    {
        $location = $request->input('location');

        $flights = DB::table('events')
            ->where('event_type', 'FLT')
            ->where('departure_airport', $location)
            ->get();

        return response()->json($flights);
    }
}
