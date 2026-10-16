<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::query();

        if ($request->filled('buscar')) {
            $query->where('nombre_completo', 'like', '%' . $request->buscar . '%');
        }

        return $query->orderBy('nombre_completo')->get()->map(function ($c) {
            $c->documento_oculto = $c->documento_oculto; // dispara el accessor para listas (RNF-05)
            return $c;
        });
    }

    // Ficha completa del cliente (documento se revela completo aquí, según el mockup)
    public function show(Cliente $cliente)
    {
        return $cliente;
    }

    // Se usa tanto desde el checkout público como desde "Nuevo cliente" en el panel (RF-03)
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'documento' => 'required|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);

        return response()->json(Cliente::create($data), 201);
    }

    public function update(Request $request, Cliente $cliente)
    {
        $data = $request->validate([
            'nombre_completo' => 'sometimes|string|max:255',
            'documento' => 'sometimes|string|max:20',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);

        $cliente->update($data);
        return $cliente;
    }
}
