<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ImportDetalleConsumoElectrico extends Command
{
    protected $signature = 'import:detalle-consumo-electrico
                            {file? : Ruta del CSV}
                            {--dry-run : Solo analiza, no inserta}';

    protected $description = 'Importa datos de consumo eléctrico a detalle_consumos_basicos desde un CSV';

    public function handle(): int
    {
        $filePath = $this->argument('file') ?? storage_path('app/imports/DetalleConsumoElectrico.csv');
        $dryRun = (bool) $this->option('dry-run');

        if (! File::exists($filePath)) {
            $this->error("No existe el archivo: {$filePath}");
            return self::FAILURE;
        }

        $this->info('Iniciando importación...');
        $this->line("Archivo: {$filePath}");
        $this->line($dryRun ? 'Modo: DRY RUN' : 'Modo: INSERCIÓN');

        $rows = $this->readCsv($filePath);

        if (empty($rows)) {
            $this->warn('El archivo no contiene filas válidas.');
            return self::SUCCESS;
        }

        $stats = [
            'total_csv' => count($rows),
            'duplicados_descartados' => 0,
            'sin_kwh' => 0,
            'sin_dte' => 0,
            'sin_cliente' => 0,
            'ya_existia' => 0,
            'insertados' => 0,
            'errores' => 0,
        ];

        $seen = [];
        $rowsToInsert = [];

        DB::beginTransaction();

        try {
            foreach ($rows as $index => $row) {
                $numeroDte = trim((string) ($row['numerodte'] ?? ''));
                $numeroCliente = trim((string) ($row['ncliente'] ?? ''));
                $periodoCsv = trim((string) ($row['periodo'] ?? ''));
                $kwhRaw = trim((string) ($row['kwh'] ?? ''));

                if ($numeroDte === '' || $numeroCliente === '') {
                    $stats['errores']++;
                    continue;
                }

                $periodoNormalizado = $this->normalizarPeriodoCsv($periodoCsv);

                if (! $periodoNormalizado) {
                    $this->warn("Fila {$index}: período no reconocido [{$periodoCsv}]");
                    $stats['errores']++;
                    continue;
                }

                $consumo = $this->normalizarNumeroConsumo($kwhRaw);

                if ($consumo === null) {
                    $stats['sin_kwh']++;
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Evitar duplicados del CSV
                | Regla: NumeroDte + Periodo + NumeroCliente
                |--------------------------------------------------------------------------
                */
                $dedupeKey = $numeroDte . '|' . $periodoNormalizado . '|' . $numeroCliente;

                if (isset($seen[$dedupeKey])) {
                    $stats['duplicados_descartados']++;
                    continue;
                }

                $seen[$dedupeKey] = true;

                /*
                |--------------------------------------------------------------------------
                | Validar existencia de cliente en clientesmedidores
                |--------------------------------------------------------------------------
                */
                $clienteExiste = DB::table('clientesmedidores')
                    ->where('numerocliente', $numeroCliente)
                    ->exists();

                if (! $clienteExiste) {
                    $stats['sin_cliente']++;
                    $this->warn("No existe numerocliente {$numeroCliente} en clientesmedidores");
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Buscar DTE
                |--------------------------------------------------------------------------
                | Se usa NumeroDte + Periodo para hacer match robusto.
                |--------------------------------------------------------------------------
                */
                $dte = DB::table('dtes')
                    ->select('id', 'NumeroDte', 'Periodo')
                    ->where('NumeroDte', $numeroDte)
                    ->where('Periodo', $periodoNormalizado)
                    ->first();

                if (! $dte) {
                    $stats['sin_dte']++;
                    $this->warn("No se encontró DTE para NumeroDte={$numeroDte}, Periodo={$periodoNormalizado}");
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Evitar insertar nuevamente si ya existe
                |--------------------------------------------------------------------------
                */
                $yaExiste = DB::table('detalle_consumos_basicos')
                    ->where('dtes_id', $dte->id)
                    ->where('numerocliente', $numeroCliente)
                    ->where('tipo', 'Electricidad')
                    ->exists();

                if ($yaExiste) {
                    $stats['ya_existia']++;
                    continue;
                }

                $rowsToInsert[] = [
                    'dtes_id' => $dte->id,
                    'tipo' => 'Electricidad',
                    'numerocliente' => $numeroCliente,
                    'consumo' => $consumo,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (! $dryRun && ! empty($rowsToInsert)) {
                foreach (array_chunk($rowsToInsert, 500) as $chunk) {
                    DB::table('detalle_consumos_basicos')->insert($chunk);
                }
            }

            $stats['insertados'] = count($rowsToInsert);

            if ($dryRun) {
                DB::rollBack();
            } else {
                DB::commit();
            }

            $this->newLine();
            $this->info('Resumen importación');
            $this->table(
                ['Métrica', 'Cantidad'],
                [
                    ['Total filas CSV', $stats['total_csv']],
                    ['Duplicados descartados', $stats['duplicados_descartados']],
                    ['Filas sin kWh', $stats['sin_kwh']],
                    ['Sin DTE relacionado', $stats['sin_dte']],
                    ['Sin cliente relacionado', $stats['sin_cliente']],
                    ['Ya existían en detalle_consumos_basicos', $stats['ya_existia']],
                    ['Filas preparadas/insertadas', $stats['insertados']],
                    ['Errores de formato', $stats['errores']],
                ]
            );

            return self::SUCCESS;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Error durante la importación: ' . $e->getMessage());
            return self::FAILURE;
        }
    }

    private function readCsv(string $filePath): array
    {
        $rows = [];

        $handle = fopen($filePath, 'r');

        if (! $handle) {
            return [];
        }

        $headers = fgetcsv($handle, 0, ';');

        if (! $headers) {
            fclose($handle);
            return [];
        }

        $headers = array_map(fn ($value) => $this->normalizarHeader($value), $headers);

        while (($data = fgetcsv($handle, 0, ';')) !== false) {
            if (count(array_filter($data, fn ($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }

            $row = [];

            foreach ($headers as $index => $header) {
                $row[$header] = isset($data[$index]) ? trim((string) $data[$index]) : null;
            }

            $rows[] = $row;
        }

        fclose($handle);

        return $rows;
    }

    private function normalizarHeader(?string $header): string
    {
        $header = trim((string) $header);
        $header = mb_strtolower($header, 'UTF-8');

        $map = [
            'n°cliente' => 'ncliente',
            'nºcliente' => 'ncliente',
            'numero dte' => 'numerodte',
            'numerodte' => 'numerodte',
            'periodo' => 'periodo',
            'kwh' => 'kwh',
            'monto' => 'monto',
            'juzgado' => 'juzgado',
            'direccion' => 'direccion',
            'tipo' => 'tipo',
            'año' => 'anio',
            'mes' => 'mes',
            'id' => 'id',
        ];

        return $map[$header] ?? preg_replace('/[^a-z0-9]/', '', iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $header));
    }

    private function normalizarPeriodoCsv(?string $periodo): ?string
    {
        if (! $periodo) {
            return null;
        }

        $periodo = trim(mb_strtolower($periodo, 'UTF-8'));

        $meses = [
            'ene' => 1,
            'feb' => 2,
            'mar' => 3,
            'abr' => 4,
            'may' => 5,
            'jun' => 6,
            'jul' => 7,
            'ago' => 8,
            'sep' => 9,
            'oct' => 10,
            'nov' => 11,
            'dic' => 12,
        ];

        /*
        |--------------------------------------------------------------------------
        | Ejemplo CSV: abr-23
        |--------------------------------------------------------------------------
        */
        if (preg_match('/^([a-z]{3})-(\d{2})$/', $periodo, $matches)) {
            $mesTxt = $matches[1];
            $anio2 = (int) $matches[2];

            if (! isset($meses[$mesTxt])) {
                return null;
            }

            $mes = $meses[$mesTxt];
            $anio = $anio2 >= 70 ? 1900 + $anio2 : 2000 + $anio2;

            return $mes . '/' . $anio; // formato dtes.Periodo: 10/2024
        }

        /*
        |--------------------------------------------------------------------------
        | Si ya viene como 10/2024 o 4/2024
        |--------------------------------------------------------------------------
        */
        if (preg_match('/^(\d{1,2})\/(\d{4})$/', $periodo, $matches)) {
            return ((int) $matches[1]) . '/' . ((int) $matches[2]);
        }

        return null;
    }

    private function normalizarNumeroConsumo(?string $value): ?float
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | En el CSV los kWh vienen como:
        | 1.472   => 1472
        | 913.000 => 913000
        |--------------------------------------------------------------------------
        */
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);

        if (! is_numeric($value)) {
            return null;
        }

        return (float) $value;
    }
}