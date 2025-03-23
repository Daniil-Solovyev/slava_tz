<?php

namespace App\Services;

use App\Models\Row;
use Carbon\Carbon;

class RowSaver
{
    private $unique_ids = [];

    public function __construct()
    {
        $this->loadExistingIds();
    }

    /**
     * @return void
     */
    private function loadExistingIds(): void
    {
        $this->unique_ids = Row::pluck('row_id')->toArray();
    }

    /**
     * @param array $data
     * @return bool
     */
    public function saveRow(array $data): bool
    {
        // Проверка на дубли
        if (in_array($data['id'], $this->unique_ids)) {
            return false;
        }

        Row::create([
            'row_id' => $data['id'],
            'name' => $data['name'],
            'date' => Carbon::createFromFormat('d.m.Y', $data['date'])->toDateString(),
        ]);

        $this->unique_ids[] = $data['id'];

        return true;
    }
}