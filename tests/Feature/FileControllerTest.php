<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class FileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('uploads'); // Fake storage to test file uploads
    }

    /** @test */
    public function it_can_upload_an_excel_file_and_insert_data_into_database()
    {
        Excel::fake();

        $file = UploadedFile::fake()->create('test.xlsx', 100, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $response = $this->postJson('/api/upload-excel', [
            'file' => $file,
            'deleteRecords' => false // We're not deleting records in this case
        ]);

        Excel::assertImported('uploads/test.xlsx');

        $response->assertStatus(200);

        $response->assertJsonStructure(['message']);

        $this->assertDatabaseHas('events', [
            'event_type' => 'OFF',
            'start_time_zulu' => '2022-01-01 08:45:00',
        ]);
    }

    /** @test */
    public function it_can_truncate_records_when_deleteRecords_is_true()
    {
        Excel::fake();
        Event::factory()->count(5)->create();

        $file = UploadedFile::fake()->create('test.xlsx');
        $response = $this->postJson('/api/upload-excel', [
            'file' => $file,
            'deleteRecords' => true
        ]);

        Excel::assertImported('uploads/test.xlsx');

        $this->assertDatabaseCount('events', 0);

        $response->assertStatus(200)->assertJson([
            'message' => 'File processed successfully.',
        ]);
    }

    /** @test */
    public function it_validates_file_mimes_correctly()
    {
        $file = UploadedFile::fake()->create('test.txt', 100);

        $response = $this->postJson('/api/upload-excel', [
            'file' => $file,
            'deleteRecords' => false
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors('file');
    }
}
