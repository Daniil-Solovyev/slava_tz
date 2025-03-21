<?php

namespace Tests\Feature;

use App\Models\Row;
use Tests\TestCase;
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
            '2025-04-20' => [['id', 'row_id', 'name']],
            '2025-07-26' => [['id', 'row_id', 'name']],
            '2025-12-18' => [['id', 'row_id', 'name']],
        ]);

        $response->assertJson([
            '2025-04-20' => [
                ['id' => $row1->id, 'row_id' => $row1->row_id, 'name' => $row1->name],
            ],
            '2025-07-26' => [
                ['id' => $row2->id, 'row_id' => $row2->row_id, 'name' => $row2->name],
            ],
            '2025-12-18' => [
                ['id' => $row3->id, 'row_id' => $row3->row_id, 'name' => $row3->name],
                ['id' => $row4->id, 'row_id' => $row4->row_id, 'name' => $row4->name],
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
}
