<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nombre_completo', 'documento', 'telefono', 'direccion'];

    // Oculta el documento salvo las 2 primeras y 2 últimas cifras, como en el mockup (RNF-05)
    public function getDocumentoOcultoAttribute(): string
    {
        $doc = $this->documento;
        if (strlen($doc) <= 4) return $doc;
        return substr($doc, 0, 2) . str_repeat('*', strlen($doc) - 4) . substr($doc, -2);
    }
}
