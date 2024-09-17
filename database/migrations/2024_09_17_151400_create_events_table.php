<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            
            // Event type: 'CI', 'CO', 'FLT', 'SBY', 'DO', or 'UNK'
            $table->string('event_type'); 
            
            // Related fields for flights
            $table->string('flight_number')->nullable(); // Flight number if the event is a flight
            $table->string('departure_airport')->nullable(); // Departure airport (for flights)
            $table->string('arrival_airport')->nullable(); // Arrival airport (for flights)
            
            // Start and end times in Zulu (UTC) format
            $table->dateTime('start_time_zulu')->nullable(); // Start time (C/I(Z) for Check-In, STD(Z) for Flights)
            $table->dateTime('end_time_zulu')->nullable(); // End time (STA(Z) for Flights, CO(Z) for Check-Out)

            // Remarks and other details
            $table->text('remarks')->nullable(); // For additional remarks or metadata

            // Created and updated timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
