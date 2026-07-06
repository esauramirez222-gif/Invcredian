<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanItem extends Model
{
    protected $fillable = [
        'loan_id',
        'resource_id',
        'quantity'
    ];

    // Este detalle pertenece a un préstamo general
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    // Este detalle pertenece a un recurso físico
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}