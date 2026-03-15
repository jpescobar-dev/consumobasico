<?php

namespace App\Console\Commands;

use App\Models\DetalleConsumoBasico;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportarDetalleElectricidad extends Command
{
    // Comando: php artisan importar:electricidad storage/app/electricidad.csv
    protected $signature = 'importar:electricidad {archivo}';
    protected $description = 'Importa datos de electricidad desde un archivo CSV';

    public function handle()
    {
        $archivo = $this->argument('archivo');

        if (!File::exists($archivo)) {
            $this->error("Archivo no encontrado: $archivo");
            return 1;
        }

        $handle = fopen($archivo, 'r');
        if (!$handle) {
            $this->error("No se pudo abrir el archivo.");
            return 1;
        }

        fgetcsv($handle, 1000, "\t"); // Saltar encabezado (asumiendo separador tabulador)

        DB::beginTransaction();

        try {
            while (($fila = fgetcsv($handle, 1000, "\t")) !== false) {
                if (count($fila) < 3) continue;

                $dtes_id = trim($fila[0]);
                $numerocliente = trim($fila[1]);
                $tipo = trim($fila[2]) ?: 'ELECTRICIDAD';
                $consumo = floatval(str_replace(',', '.', trim($fila[3])));              


                DetalleConsumoBasico::create([
                    'dtes_id' => $dtes_id ?: null,
                    'tipo' => $tipo,
                    'numerocliente' => $numerocliente ?: null,
                    'consumo' => $consumo,
                ]);
            }

            fclose($handle);
            DB::commit();
            $this->info("Importación completada con éxito.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error durante la importación: " . $e->getMessage());
        }

        return 0;
    }
}




