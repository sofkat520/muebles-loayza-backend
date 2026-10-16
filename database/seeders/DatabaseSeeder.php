<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario para entrar al panel (Figura 5 del mockup)
        User::create([
            'name' => 'Ana Q.',
            'email' => 'vendedor@mueblesloayza.com',
            'password' => bcrypt('password'),
        ]);

        Producto::create(['nombre' => 'Ropero 6 puertas nogal', 'categoria' => 'Roperos', 'precio' => 890.00, 'stock' => 4, 'stock_minimo' => 3, 'descripcion' => 'Melamina nogal, 6 puertas con espacio para colgar y 3 repisas internas. Incluye delivery e instalación dentro de Cusco.']);
        Producto::create(['nombre' => 'Cómoda 4 cajones cedro', 'categoria' => 'Cómodas', 'precio' => 320.00, 'stock' => 1, 'stock_minimo' => 3, 'descripcion' => 'Cómoda de 4 cajones en melamina cedro.']);
        Producto::create(['nombre' => 'Escritorio en L blanco', 'categoria' => 'Escritorios', 'precio' => 410.00, 'stock' => 6, 'stock_minimo' => 3, 'descripcion' => 'Escritorio en L color blanco.']);

        Cliente::create(['nombre_completo' => 'Rosa Tito Quispe', 'documento' => '41223372', 'telefono' => '984112210', 'direccion' => 'Av. de la Cultura, Cusco']);
        Cliente::create(['nombre_completo' => 'Julio Mamani Ccama', 'documento' => '45556688', 'telefono' => '974556663', 'direccion' => 'San Sebastián, Cusco']);
    }
}
