<?php

namespace App\Imports;

use App\Services\RowSaver;
use App\Services\RowValidator;
use App\Services\ErrorLogger;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class RowImport implements ToArray, WithChunkReading, WithStartRow
{
    const CHUNK_SIZE = 1000;
    const START_ROW = 2; // Старт со 2, так как заголовки пропущены

    public function chunkSize(): int
    {
        return self::CHUNK_SIZE;
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function startRow(): int
    {
        return self::START_ROW;
    }

    private $row_number = self::START_ROW;
    private $row_validator;
    private $row_saver;
    private $error_logger;
    private $progress_key;

    public function __construct(
        RowValidator $row_validator,
        RowSaver     $row_saver, ErrorLogger $error_logger,
        string       $progress_key
    )
    {
        $this->row_validator = $row_validator;
        $this->row_saver = $row_saver;
        $this->error_logger = $error_logger;
        $this->progress_key = $progress_key;
    }

    /**
     * @param array $rows
     * @return void
     */
    public function array(array $rows): void
    {
        foreach ($rows as $row) {
            $this->processRow($row, $this->row_number++);
        }

        $this->error_logger->writeErrorsToFile();
    }

    /**
     * @param array $row
     * @param int $row_number
     * @return void
     */
    private function processRow(array $row, int $row_number): void
    {
        // Счетчик обработанных строк
        Redis::incr($this->progress_key);

        $data = [
            'id' => $row[0] ?? null,
            'name' => $row[1] ?? null,
            'date' => $row[2] ?? null,
        ];

        $errors = $this->row_validator->validate($data);

        if (!empty($errors)) {
            $this->error_logger->addError($row_number, $errors);
        } elseif (!$this->row_saver->saveRow($data)) {
            $this->error_logger->addError($row_number, ['ID есть в БД: ' . $data['id']]);
        }
    }
}