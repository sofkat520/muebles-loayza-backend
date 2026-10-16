<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // GET /api/productos?buscar=ropero  (RF-02: consultar/buscar)
    public function index(Request $request)
    {
        $query = Producto::query();

        if ($request->filled('buscar')) {
            $texto = $request->buscar;
            $query->where(function ($q) use ($texto) {
                $q->where('nombre', 'like', "%$texto%")
                  ->orWhere('categoria', 'like', "%$texto%");
            });
        }

        return $query->orderBy('nombre')->get();
    }

    public function show(Producto $producto)
    {
        return $producto;
    }

    // POST /api/productos (RF-01: registrar productos)
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|string',
        ]);

        return response()->json(Producto::create($data), 201);
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'categoria' => 'nullable|string|max:255',
            'precio' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|string',
        ]);

        $producto->update($data);
        return $producto;
    }

    // PATCH /api/productos/{producto}/ajustar-stock (RF-06: gestionar inventario)
    public function ajustarStock(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'cantidad' => 'required|integer', // puede ser negativo (salida) o positivo (ingreso)
        ]);

        $producto->stock = max(0, $producto->stock + $data['cantidad']);
        $producto->save();

        return $producto;
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return response()->noContent();
    }
}
