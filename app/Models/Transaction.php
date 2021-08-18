<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $guarded = [];

    use HasFactory;
    
    public function records()
    {
        return $this->hasMany(Record::class);
    }
    
    public function errors()
    {
        return $this->hasMany(Error::class);
    }    
}
