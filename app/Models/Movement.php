<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    protected $fillable = [
        'resource_id',
        'user_id',
        'type',
        'quantity',
        'notes'
    ];

    // El movimiento pertenece a un recurso
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    // El movimiento fue registrado por un usuario administrador
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}