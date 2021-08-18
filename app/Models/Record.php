<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Record extends Model
{
    protected $guarded = [];

    protected $casts = [
        'read' => 'boolean',
    ];

    use HasFactory;

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Run date search on model
     * @author Chinonso
     * @param \Illuminate\Database\Eloquent\Builder
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDataCelebracaoContrato($builder, Request $request)
    {
        return $builder->when($request->from, function ($query) use ($request) {
            $query->where('dataCelebracaoContrato', '>=', now()->parse($request->from)->toDateString());
        })->when($request->to, function ($query) use ($request) {
            $query->where('dataCelebracaoContrato', '<=', now()->parse($request->to)->toDateString());
        });
    }

    /**
     * Run amount search on model
     * @author Chinonso
     * @param \Illuminate\Database\Eloquent\Builder
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePrecoContratual($builder, Request $request)
    {
        return $builder->when($request->amount_min, function ($query) use ($request) {
            $query->where('precoContratual', '>=', $request->amount_min);
        })->when($request->amount_max, function ($query) use ($request) {
            $query->where('precoContratual', '<=', $request->amount_max);
        });
    }

    /**
     * Run wining company search on model
     * @author Chinonso
     * @param \Illuminate\Database\Eloquent\Builder
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdjudicatarios($builder, Request $request)
    {
        return $builder->when($request->winning_company, function ($query) use ($request) {
            $query->where('adjudicatarios', 'like', "%$request->winning_company%");
        });
    }
}
