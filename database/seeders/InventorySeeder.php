<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Resource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        // Tu catálogo estructurado con formato: 'Nombre del producto' => Cantidad
        $catalog = [
            'Cables' => [
                'HDMI' => 3, 
                'HDMI a USB-C' => 1, 
                'USB-A a USB-B' => 1, 
                'Cable de Teléfono' => 5, 
                'VGA' => 2, 
                'HDMI a DVI' => 2, 
                'Fuente de Poder' => 18, 
                'USB-A a MicroUSB' => 4, 
                'DisplayPort' => 1, 
                'Extensor USB-A' => 1, 
                'Extensión Eléctrica' => 2, 
                'DisplayPort a Mini DisplayPort' => 1, 
                'RCA' => 4, 
                'Ethernet' => 2, 
                'LED Driver' => 2, 
                'VGA a Mini DisplayPort' => 1, 
                'Adaptador VGA a DVI' => 1
            ],
            'Cargadores' => [
                'Cargador MicroUSB' => 1, 
                'Cargador de Laptop' => 5, 
                'Cargador Tipo Laptop' => 2, 
                'Adaptador DC 5V' => 8
            ],
            'Periféricos' => [
                'Teclado' => 1, 
                'Cámara Web' => 4, 
                'Repetidor Wi-Fi' => 1, 
                'Lámpara de techo' => 1, 
                'Audífonos' => 1, 
                'Batería UB1250' => 1, 
                'Teléfono Inalámbrico' => 2, 
                'Control de TV' => 2, 
                'Timbre' => 1, 
                'Teléfono Fijo' => 2, 
                'Teléfono Timbre' => 1, 
                'Checador de Huella' => 2, 
                'Mouse' => 2
            ],
            'Otros' => [
                'Soporte para TV' => 2, 
                'Soporte para PC' => 2, 
                'Soporte para Teléfono' => 1, 
                'Patas de silla' => 4, 
                'Licencia de Antivirus' => 2, 
                'Mouse Pad' => 3, 
                'Caja de Tornillos' => 1, 
                'Patas de Escritorio' => 4, 
                'Soporte Genérico' => 1
            ]
        ];

        foreach ($catalog as $categoryName => $products) {
            // 1. Crear la categoría
            $category = Category::create([
                'name' => $categoryName,
                'description' => 'Recursos de la categoría: ' . $categoryName
            ]);

            // Generar un prefijo para el código interno (Ej. "CAB" para Cables)
            $prefix = strtoupper(substr($categoryName, 0, 3));
            
            // Contador manual para el código interno (ya que quitamos el índice numérico)
            $counter = 1;

            // 2. Crear los recursos asociados a esta categoría
            // Ahora extraemos el nombre del producto y la cantidad que le asignaste
            foreach ($products as $productName => $quantity) {
                
                Resource::create([
                    'category_id' => $category->id,
                    'name' => $productName,
                    'internal_code' => $prefix . '-' . str_pad($counter, 3, '0', STR_PAD_LEFT),
                    'available_quantity' => $quantity,
                    'total_quantity' => $quantity, // Al inicio, todo está disponible
                    'status' => 'active',
                    'registration_date' => now(),
                    'observations' => 'Inventario inicial automatizado'
                ]);
                
                $counter++; // Aumentamos el contador para el siguiente producto
            }
        }
    }
}