<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use App\Models\Event;

class FileController extends Controller
{
    private function formatDateTime($timeString, $dateString) {
        $time = sprintf('%04d', $timeString);
        $hours = substr($time, 0, 2);
        $minutes = substr($time, 2, 2);

        $dateString = explode(' ', trim($dateString))[1];
        $parsedDate = date('Y-m-d', strtotime("$dateString January 2022"));

        $datetime = $parsedDate . ' ' . $hours . ':' . $minutes . ':00';
        $datetimeFormatted = date('Y-m-d H:i:s', strtotime($datetime));

        return $datetimeFormatted;
    }

    public function uploadExcel(Request $request)
    {
        if ($request->input('deleteRecords')) {
            Event::truncate();
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,html,pdf'
        ]);
    
        $file = $request->file('file');
    
        $extension = $file->getClientOriginalExtension();

        $data = Excel::toArray([], $file);

        $date = '';
        foreach ($data[0] as $row) {
            $date = $row[0] ? $row[0] : $date;
            $activity = $row[7];
            $start_time_zulu = $row[12];
            $end_time_zulu = $row[16];
            $flight_number = $row[7];
            $location_start = $row[10];
            $location_end = $row[14];
            
            if ($activity == 'Activity') {
                continue;
            }

            $start_time_zulu = $this->formatDateTime($start_time_zulu, $date);
            $end_time_zulu = $this->formatDateTime($end_time_zulu, $date);
            
            if ($activity === 'OFF') {
                $event_type = 'OFF';
                var_dump([$event_type, $start_time_zulu]);
                DB::table('events')->insert([
                    'event_type' => $event_type,
                    'start_time_zulu' => $start_time_zulu,
                ]);
            } elseif ($activity === 'SBY') {
                $event_type = 'SBY';
                var_dump([$event_type, $start_time_zulu, $end_time_zulu]);
                DB::table('events')->insert([
                    'event_type' => $event_type,
                    'start_time_zulu' => $start_time_zulu,
                    'end_time_zulu' => $end_time_zulu,
                ]);
            } elseif (strpos($activity, 'DX') === 0) {
                $event_type = 'FLT';
                var_dump([$event_type, $flight_number, $location_start, $location_end, $start_time_zulu, $end_time_zulu]);
                DB::table('events')->insert([
                    'event_type' => $event_type,
                    'start_time_zulu' => $start_time_zulu,
                    'end_time_zulu' => $end_time_zulu,
                    'flight_number' => $flight_number,
                    'departure_airport' => $location_start,
                    'arrival_airport' => $location_end,
                ]);
            }
        }

        return 1;
    }
}
