<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Manga;
use App\Models\Figura;
use App\Models\Merch;

class PedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegurarnos de que existe un usuario normal (no admin)
        $usuario = User::where('email', 'usuario@test.com')->first();
        
        if (!$usuario) {
            $usuario = User::create([
                'name' => 'Usuario Test',
                'email' => 'usuario@test.com',
                'password' => bcrypt('password'),
                'is_admin' => false,
            ]);
        }

        // Pedido 1: Entregado
        $pedido1 = Pedido::create([
            'user_id' => $usuario->id,
            'numero_pedido' => Pedido::generarNumeroPedido(),
            'estado' => 'entregado',
            'subtotal' => 62.85,
            'impuesto' => 13.20,
            'total' => 76.05,
            'email_cliente' => $usuario->email,
            'nombre_cliente' => $usuario->name,
            'direccion_envio' => 'Calle Principal 123, 28001 Madrid, España',
            'direccion_facturacion' => 'Calle Principal 123, 28001 Madrid, España',
        ]);

        // Obtener productos para el pedido
        $manga1 = Manga::find(1); // JJK Vol 0
        $manga2 = Manga::find(2); // JJK Vol 4
        $figura1 = Figura::find(1); // Figura Deku

        if ($manga1) {
            PedidoItem::create([
                'pedido_id' => $pedido1->id,
                'producto_id' => $manga1->id,
                'producto_type' => 'App\Models\Manga',
                'nombre_producto' => $manga1->nombre,
                'precio_unitario' => $manga1->precio,
                'cantidad' => 2,
                'subtotal' => $manga1->precio * 2,
            ]);
        }

        if ($manga2) {
            PedidoItem::create([
                'pedido_id' => $pedido1->id,
                'producto_id' => $manga2->id,
                'producto_type' => 'App\Models\Manga',
                'nombre_producto' => $manga2->nombre,
                'precio_unitario' => $manga2->precio,
                'cantidad' => 1,
                'subtotal' => $manga2->precio,
            ]);
        }

        if ($figura1) {
            PedidoItem::create([
                'pedido_id' => $pedido1->id,
                'producto_id' => $figura1->id,
                'producto_type' => 'App\Models\Figura',
                'nombre_producto' => $figura1->nombre,
                'precio_unitario' => $figura1->precio,
                'cantidad' => 1,
                'subtotal' => $figura1->precio,
            ]);
        }

        // Pedido 2: En procesamiento
        $pedido2 = Pedido::create([
            'user_id' => $usuario->id,
            'numero_pedido' => Pedido::generarNumeroPedido(),
            'estado' => 'procesando',
            'subtotal' => 41.32,
            'impuesto' => 8.68,
            'total' => 50.00,
            'email_cliente' => $usuario->email,
            'nombre_cliente' => $usuario->name,
            'direccion_envio' => 'Calle Principal 123, 28001 Madrid, España',
            'direccion_facturacion' => 'Calle Principal 123, 28001 Madrid, España',
        ]);

        $merch1 = Merch::find(1); // Camiseta
        $merch2 = Merch::find(2); // Sudadera

        if ($merch1) {
            PedidoItem::create([
                'pedido_id' => $pedido2->id,
                'producto_id' => $merch1->id,
                'producto_type' => 'App\Models\Merch',
                'nombre_producto' => $merch1->nombre,
                'precio_unitario' => $merch1->precio,
                'cantidad' => 1,
                'subtotal' => $merch1->precio,
            ]);
        }

        if ($merch2) {
            PedidoItem::create([
                'pedido_id' => $pedido2->id,
                'producto_id' => $merch2->id,
                'producto_type' => 'App\Models\Merch',
                'nombre_producto' => $merch2->nombre,
                'precio_unitario' => $merch2->precio,
                'cantidad' => 1,
                'subtotal' => $merch2->precio,
            ]);
        }

        // Pedido 3: Pendiente
        $pedido3 = Pedido::create([
            'user_id' => $usuario->id,
            'numero_pedido' => Pedido::generarNumeroPedido(),
            'estado' => 'pendiente',
            'subtotal' => 24.79,
            'impuesto' => 5.21,
            'total' => 30.00,
            'email_cliente' => $usuario->email,
            'nombre_cliente' => $usuario->name,
            'direccion_envio' => 'Calle Principal 123, 28001 Madrid, España',
            'direccion_facturacion' => 'Calle Principal 123, 28001 Madrid, España',
        ]);

        $figura2 = Figura::find(2); // Figura Chainsaw Man

        if ($figura2) {
            PedidoItem::create([
                'pedido_id' => $pedido3->id,
                'producto_id' => $figura2->id,
                'producto_type' => 'App\Models\Figura',
                'nombre_producto' => $figura2->nombre,
                'precio_unitario' => $figura2->precio,
                'cantidad' => 1,
                'subtotal' => $figura2->precio,
            ]);
        }

        $this->command->info('✓ Pedidos de prueba creados exitosamente');
    }
}
