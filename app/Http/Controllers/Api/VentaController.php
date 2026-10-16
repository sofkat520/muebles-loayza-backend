<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    // RF-07: consultar ventas (panel principal / historial)
    public function index()
    {
        return Venta::with(['cliente', 'detalles.producto'])
            ->latest()
            ->get();
    }

    public function show(Venta $venta)
    {
        return $venta->load(['cliente', 'detalles.producto']);
    }

    // RF-04 registrar venta + RF-06 descontar stock automáticamente + RF-08 genera el comprobante
    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'metodo_pago' => 'nullable|string|max:50',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
        ]);

        $venta = DB::transaction(function () use ($data) {
            $total = 0;
            $detalles = [];

            foreach ($data['items'] as $item) {
                $producto = Producto::lockForUpdate()->findOrFail($item['producto_id']);

                if ($producto->stock < $item['cantidad']) {
                    abort(422, "Stock insuficiente para {$producto->nombre}");
                }

                $producto->stock -= $item['cantidad'];
                $producto->save();

                $subtotal = $producto->precio * $item['cantidad'];
                $total += $subtotal;

                $detalles[] = [
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $producto->precio,
                ];
            }

            $venta = Venta::create([
                'cliente_id' => $data['cliente_id'],
                'metodo_pago' => $data['metodo_pago'] ?? null,
                'total' => $total,
            ]);

            foreach ($detalles as $d) {
                $venta->detalles()->create($d);
            }

            return $venta;
        });

        return response()->json($venta->load(['cliente', 'detalles.producto']), 201);
    }
}
