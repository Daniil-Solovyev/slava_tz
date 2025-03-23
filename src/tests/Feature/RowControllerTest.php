<?php

namespace Tests\Feature;

use App\Models\Row;
use Tests\TestCase;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RowControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate --seed');
    }

    /** @test */
    public function it_returns_grouped_rows_by_date()
    {
        $row1 = Row::factory()->create(['date' => '2025-04-20']);
        $row2 = Row::factory()->create(['date' => '2025-07-26']);
        $row3 = Row::factory()->create(['date' => '2025-12-18']);
        $row4 = Row::factory()->create(['date' => '2025-12-18']);

        $response = $this->get(route('rows'));

        $response->assertStatus(200);

        $response->assertJsonStructure([
            '2025-04-20' => [['id', 'name', 'date']],
            '2025-07-26' => [['id', 'name', 'date']],
            '2025-12-18' => [['id', 'name', 'date']],
        ]);

        $response->assertJson([
            '2025-04-20' => [
                ['id' => $row1->id, 'name' => $row1->name, 'date' => $row1->date],
            ],
            '2025-07-26' => [
                ['id' => $row2->id, 'name' => $row2->name, 'date' => $row2->date],
            ],
            '2025-12-18' => [
                ['id' => $row3->id, 'name' => $row3->name, 'date' => $row3->date],
                ['id' => $row4->id, 'name' => $row4->name, 'date' => $row4->date],
            ],
        ]);
    }

    /** @test */
    public function it_returns_empty_response_when_no_rows_exist()
    {
        $response = $this->get(route('rows'));

        $response->assertStatus(200);

        $response->assertJson([]);
    }

    /** @test */
    public function it_returns_progress_from_redis()
    {
        $progress_key = 'parsing_progress:test-key';
        Redis::set($progress_key, 50);

        $response = $this->getJson('/get-progress?key=' . $progress_key);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'progress',
        ]);

        $response->assertJson([
            'progress' => 50,
        ]);
    }
}
