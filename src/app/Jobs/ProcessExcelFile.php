<?php

namespace App\Jobs;

use Exception;
use App\Imports\RowImport;
use App\Services\RowValidator;
use App\Services\RowSaver;
use App\Services\ErrorLogger;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessExcelFile implements ShouldQueue
{
    use Queueable, SerializesModels, Dispatchable;

    public function __construct(
        public string  $file_path,
        private string $progress_key
    )
    {
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function handle(): void
    {
        try {
            Redis::set($this->progress_key, 0);

            $row_validator = new RowValidator();
            $row_saver = new RowSaver();
            $error_logger = new ErrorLogger();

            Excel::import(new RowImport($row_validator, $row_saver, $error_logger, $this->progress_key), $this->file_path);
        } catch (Exception $e) {
            Log::error('Ошибка в ProcessExcelFile: ' . $e->getMessage());
        }
    }
}