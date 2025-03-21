<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Jobs\ProcessExcelFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UploadControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate --seed');
    }

    /** @test */
    public function show_form_returns_upload_view()
    {
        $this->withBasicAuth('test@example.com', 'password');

        $response = $this->get(route('upload.form'));

        $response->assertStatus(200);

        $response->assertViewIs('upload');
    }

    /** @test */
    public function test_upload_file_dispatches_job_and_redirects_with_success()
    {
        $this->withBasicAuth('test@example.com', 'password');

        Storage::fake('imports');

        Queue::fake();

        $file = UploadedFile::fake()->create('test.xlsx');

        $response = $this->post(route('upload.submit'), [
            'file' => $file,
        ]);

        $response->assertRedirect()
            ->assertSessionHas('success', 'Файл успешно загружен! Обработка начата.');

        Storage::assertExists('imports/' . $file->hashName());

        Queue::assertPushed(ProcessExcelFile::class, function ($job) use ($file) {
            return str_contains($job->file_path, $file->hashName());
        });
    }
}
