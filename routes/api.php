<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\EventController;


Route::post('/upload-excel', [FileController::class, 'uploadExcel']);

Route::get('/events-between', [EventController::class, 'getEventsBetweenDates']);
Route::get('/flights-next-week', [EventController::class, 'getFlightsNextWeek']);
Route::get('/standby-next-week', [EventController::class, 'getStandbyNextWeek']);
Route::get('/flights-by-location', [EventController::class, 'getFlightsByLocation']);
