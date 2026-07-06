<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'applicant_name',
        'applicant_last_name',
        'reviewer_id',
        'status',
        'notes',
        'reviewer_notes'
    ];

    // Un préstamo pertenece a un revisor (Administrador)
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // Un préstamo tiene muchos detalles (ítems)
    public function items()
    {
        return $this->hasMany(LoanItem::class);
    }
}