<?php

namespace App\Console\Commands;

use App\Models\Producto;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class AsignarImagenesProductos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'productos:asignar-imagenes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asigna automáticamente las imágenes a los productos según su nombre';

    /**
     * Mapeo de nombres de productos a nombres de archivos de imágenes
     */
    protected $mapeoImagenes = [
        'Papa Holandesa' => 'papa holandesa.jpg',
        'Papa Waycha' => 'Papa waycha.jpg',
        'Yuca Fresca' => 'Yuca.jpg',
        'Choclo Fresco' => 'Choclo.jpg',
        'Tomate Perita' => 'Tomate Pera.jpg',
        'Cebolla Blanca' => 'Cebolla blanca.jpg',
        'Zanahoria' => 'zanahoria.jpg',
        'Locoto Rojo' => 'locoto rojo.jpg',
        'Naranja de Bermejo' => 'naranja.jpg',
        'Plátano de Yungas' => 'platano.jpg',
        'Mandarina' => 'mandarina.jpg',
        'Arroz Grano de Oro' => 'arroz.jpg', // Prioriza .jpg, si no existe usa .png
        'Quinua Real' => 'quinua.jpg',
        'Maíz Pelado' => 'maiz pelado.jpg',
        'Huevos de Campo' => 'Huevos de campo.jpg',
        'Miel Pura de Abeja' => 'miel.jpg',
    ];

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🖼️  Asignando imágenes a productos...');
        $this->newLine();

        $asignados = 0;
        $noEncontrados = 0;
        $yaTienenImagen = 0;

        foreach ($this->mapeoImagenes as $nombreProducto => $nombreImagen) {
            $producto = Producto::where('nombre', $nombreProducto)->first();

            if (!$producto) {
                $this->warn("⚠️  Producto no encontrado: {$nombreProducto}");
                $noEncontrados++;
                continue;
            }

            // Si ya tiene imagen, la saltamos
            if ($producto->imagen) {
                $this->line("⏭️  {$nombreProducto} ya tiene imagen asignada");
                $yaTienenImagen++;
                continue;
            }

            // Verificar si la imagen existe
            $rutaImagen = 'productos/' . $nombreImagen;
            $rutaCompleta = storage_path('app/public/' . $rutaImagen);

            // Si no existe .jpg, intentar .png (solo para arroz)
            if (!file_exists($rutaCompleta) && $nombreImagen === 'arroz.jpg') {
                $rutaImagen = 'productos/arroz.png';
                $rutaCompleta = storage_path('app/public/' . $rutaImagen);
            }

            if (!file_exists($rutaCompleta)) {
                $this->warn("⚠️  Imagen no encontrada: {$nombreImagen}");
                $noEncontrados++;
                continue;
            }

            // Asignar la imagen
            $producto->imagen = $rutaImagen;
            $producto->save();

            $this->info("✅ {$nombreProducto} → {$nombreImagen}");
            $asignados++;
        }

        $this->newLine();
        $this->info("📊 Resumen:");
        $this->line("   ✅ Asignados: {$asignados}");
        $this->line("   ⏭️  Ya tenían imagen: {$yaTienenImagen}");
        $this->line("   ⚠️  No encontrados: {$noEncontrados}");

        return Command::SUCCESS;
    }
}
