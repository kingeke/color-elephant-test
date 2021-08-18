<?php

namespace App\Imports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ExcelImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue, WithStartRow
{
    public $data;

    public function collection(Collection $rows)
    {
        $this->data = $rows;
        info("Data", collect($rows)->toArray());
    }

    public function startRow(): int
    {
        return 1;
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
