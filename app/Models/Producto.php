<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'categoria', 'precio', 'stock', 'stock_minimo', 'descripcion', 'imagen',
    ];

    // true si el stock está por debajo del mínimo definido (para el badge ámbar del mockup)
    public function getStockBajoAttribute(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }
}
