<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'applicant_name',
        'applicant_last_name',
        'reviewer_id',
        'status',
        'notes',
        'reviewer_notes'
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

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