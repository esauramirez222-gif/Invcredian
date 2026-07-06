<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'internal_code',
        'available_quantity',
        'total_quantity',
        'status',
        'location',
        'registration_date',
        'observations'
    ];

    protected function casts(): array
    {
        return [
            'registration_date' => 'date',
        ];
    }

    // Un recurso pertenece a una categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Un recurso puede estar en muchos detalles de préstamos
    public function loanItems()
    {
        return $this->hasMany(LoanItem::class);
    }

    // Un recurso tiene muchos movimientos de historial
    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}