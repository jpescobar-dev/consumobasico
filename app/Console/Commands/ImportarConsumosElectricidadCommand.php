<?php

namespace App\Console\Commands;

use App\Services\DetalleConsumoBasicoElectricidadImporter;
use Illuminate\Console\Command;
use RuntimeException;

class ImportarConsumosElectricidadCommand extends Command
{
    protected $signature = 'consumos:importar-electricidad
                            {archivo? : Ruta del archivo Excel}
                            {--sheet=Todos : Nombre de la hoja a importar}
                            {--dry-run : Analiza el archivo sin escribir en la base de datos}';

    protected $description = 'Importa consumos eléctricos desde un Excel a detalle_consumos_basicos';

    public function __construct(private readonly DetalleConsumoBasicoElectricidadImporter $importer)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $filePath = $this->argument('archivo') ?: public_path('storage/DetalleConsumoElectrico 2023-2024-2025.xlsx');
        $sheetName = (string) $this->option('sheet');
        $dryRun = (bool) $this->option('dry-run');

        $this->info('Iniciando importación de consumos eléctricos');
        $this->line("Archivo: {$filePath}");
        $this->line("Hoja: {$sheetName}");
        $this->line($dryRun ? 'Modo: DRY RUN' : 'Modo: INSERCIÓN/ACTUALIZACIÓN');

        try {
            $result = $this->importer->import($filePath, $sheetName, $dryRun);
        } catch (RuntimeException $exception) {
            $this->error($exception->getMessage());
            return self::FAILURE;
        } catch (\Throwable $exception) {
            $this->error('Error durante la importación: ' . $exception->getMessage());
            return self::FAILURE;
        }

        $summary = $result['summary'];
        $incidents = $result['incidents'];

        $this->newLine();
        $this->info('Resumen');
        $this->table(
            ['Métrica', 'Cantidad'],
            [
                ['Filas leídas', $summary['rows_read']],
                ['Insertadas', $summary['inserted']],
                ['Actualizadas', $summary['updated']],
                ['Omitidas por FAE vacío', $summary['skipped_blank_fae']],
                ['Omitidas por FAE no encontrado', $summary['skipped_fae_not_found']],
                ['Omitidas por cliente inexistente', $summary['skipped_missing_client']],
                ['Omitidas por kWh inválido', $summary['skipped_invalid_kwh']],
                ['Filas prorrateadas', $summary['prorated_rows']],
            ]
        );

        if ($incidents !== []) {
            $this->newLine();
            $this->warn('Incidencias detectadas (muestra de las primeras 20)');
            $this->table(
                ['Fila', 'Motivo', 'Cliente', 'FAE', 'Año', 'Mes', 'kWh'],
                array_map(function ($incident) {
                    return [
                        $incident['sheet_row'],
                        $incident['reason'],
                        $incident['numerocliente'],
                        $incident['fae'],
                        $incident['anio'],
                        $incident['mes'],
                        $incident['consumo'],
                    ];
                }, array_slice($incidents, 0, 20))
            );

            if (count($incidents) > 20) {
                $this->line('Incidencias adicionales no mostradas: ' . (count($incidents) - 20));
            }
        }

        return self::SUCCESS;
    }
}
