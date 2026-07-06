<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    // Una categoría tiene muchos recursos
    public function resources()
    {
        return $this->hasMany(Resource::class);
    }
}
