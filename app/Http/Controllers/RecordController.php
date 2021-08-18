<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            "from"       => "nullable|date|date_format:Y-m-d",
            "to"         => "nullable|date|date_format:Y-m-d",
            "amount_min" => "nullable|numeric",
            "amount_max" => "nullable|numeric",
        ]);

        //run scope queries on model, this can be viewed in the model class
        $records = Record::dataCelebracaoContrato($request)
            ->precoContratual($request)
            ->adjudicatarios($request);

        return response()->json([
            'status'  => 'success',
            'records' => $records->latest()->paginate(50),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Record $record)
    {
        //if request has isRead return the read column on the record
        if ($request->has('isRead')) {
            return response()->json([
                'status' => 'success',
                'read'   => $record->read,
            ]);
        }

        //once a user requests the resource, mark it as read
        if (!$record->read) {
            $record->update([
                'read'    => true,
                'read_at' => now()->toDateTimeString(),
            ]);
        }

        return response()->json([
            'status' => 'success',
            'record' => $record,
        ]);
    }
}
