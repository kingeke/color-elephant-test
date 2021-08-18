<?php

namespace App\Imports;

use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RecordImport implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    use Importable;

    public $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function collection(Collection $records)
    {
        //get fresh record from DB per batch
        $this->transaction->refresh();

        //this holds all errors encountered in import process
        $errors = [];

        //if status of transaction is still pending, update to processing
        if ($this->transaction->status == 'pending') {
            $this->transaction->update([
                'status' => 'processing',
            ]);
        }

        //loop through collection
        foreach ($records as $record) {

            $record = collect($record)->toArray();

            try {

                //fix date issues
                $record = array_merge($record, [
                    'dataPublicacao'         => $this->formatDate($record['dataPublicacao']),
                    'dataCelebracaoContrato' => $this->formatDate($record['dataCelebracaoContrato']),
                ]);

                $this->transaction->records()->firstOrCreate(
                    [
                        'idcontrato' => $record['idcontrato'],
                    ],
                    $record
                );

            } catch (\Exception $e) {

                //report exception
                report($e);

                //add errors to errors variable
                $errors[] = [
                    'error' => "An error occurred with record {$record['idcontrato']}: {$e->getMessage()}",
                ];
            }
        }

        //log errors
        info("Errors", $errors);

        //create all errors
        if (count($errors) > 0) {
            $this->transaction->errors()->createMany($errors);
        }
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    /**
     * Formats the excel returned integer to correct date format
     * @author Chinonso
     * @param  Int|String $date
     * @return CarbonInstance|null
     */
    public function formatDate($date)
    {
        return $date ? now()->instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)) : null;
    }
}
