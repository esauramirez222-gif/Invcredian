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
        // Tu catálogo estructurado
        $catalog = [
            'Cables' => [
                'HDMI', 'USB-A a USB-B', 'Cable de Teléfono', 'VGA', 'HDMI a VGA', 
                'Fuente de Poder', 'USB-A a MicroUSB', 'DisplayPort', 'Extensor USB-A', 
                'Extensión Eléctrica', 'DisplayPort a Mini DisplayPort', 'RCA', 
                'Ethernet', 'LED Driver', 'VGA a Mini DisplayPort', 'Adaptador VGA a DVI'
            ],
            'Cargadores' => [
                'Cargador MicroUSB', 'Cargador de Laptop', 'Cargador Tipo Laptop', 'Adaptador DC 5V'
            ],
            'Periféricos' => [
                'Teclado', 'Cámara Web', 'Repetidor Wi-Fi', 'Lámpara de techo', 'Audífonos', 
                'Batería UB1250', 'Teléfono Inalámbrico', 'Control de TV', 'Timbre', 
                'Teléfono Fijo', 'Teléfono Timbre', 'Checador de Huella', 'Mouse'
            ],
            'Otros' => [
                'Soporte para TV', 'Soporte para PC', 'Soporte para Teléfono', 'Patas de silla', 
                'Licencia de Antivirus', 'Mouse Pad', 'Caja de Tornillos', 'Patas de Escritorio', 'Soporte Genérico'
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

            // 2. Crear los recursos asociados a esta categoría
            foreach ($products as $index => $productName) {
                // Cantidad aleatoria entre 10 y 50 para empezar
                $quantity = rand(10, 50); 
                
                Resource::create([
                    'category_id' => $category->id,
                    'name' => $productName,
                    'internal_code' => $prefix . '-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'available_quantity' => $quantity,
                    'total_quantity' => $quantity, // Al inicio, todo está disponible
                    'status' => 'active',
                    'registration_date' => now(),
                    'observations' => 'Inventario inicial automatizado'
                ]);
            }
        }
    }
}