<?php

namespace App\Http\Controllers;

use App\Imports\RecordImport;
use App\Jobs\UpdateTransactionStatus;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @author Chinonso
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::withCount('records', 'errors')->latest()->paginate(10);

        return response()->json([
            'status'       => 'success',
            'transactions' => $transactions,
        ]);
    }

    /**
     * Imports the data from excel and queues to Model creation
     * @author Chinonso
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:transactions,name',
            'file' => 'required|file|mimes:xlsx',
        ]);

        try {

            $transaction = Transaction::create([
                'name' => $request->name,
            ]);

            (new RecordImport($transaction))->queue($request->file('file'))->chain([
                new UpdateTransactionStatus($transaction),
            ]);

            return response()->json([
                'status'      => 'success',
                'message'     => 'Your transaction will start processing shortly.',
                'transaction' => $transaction,
            ]);

        } catch (\Exception $e) {

            report($e);

            return messageResponse('error', "An error occurred: {$e->getMessage()}", 400);
        }
    }

    /**
     * Display the specified resource.
     * @author Chinonso
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        $transaction->loadCount('records', 'errors');

        return response()->json([
            'status'      => 'success',
            'transaction' => $transaction,
            'records'     => $transaction->records()->latest()->paginate(10, ['*'], 'records-page'),
            'errors'      => $transaction->errors()->latest()->paginate(10, ['*'], 'errors-page'),
        ]);
    }
}
