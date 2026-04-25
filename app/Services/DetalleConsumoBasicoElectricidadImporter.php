<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use RuntimeException;

class DetalleConsumoBasicoElectricidadImporter
{
    private const RUT_EMISOR_ELECTRICIDAD = '88272600-2';

    /**
     * @return array{
     *     summary: array<string, int>,
     *     incidents: array<int, array<string, mixed>>
     * }
     */
    public function import(string $filePath, string $sheetName = 'Todos', bool $dryRun = false): array
    {
        if (! File::exists($filePath)) {
            throw new RuntimeException("No existe el archivo: {$filePath}");
        }

        $sheetRows = $this->readSheetRows($filePath, $sheetName);

        $summary = [
            'rows_read' => 0,
            'inserted' => 0,
            'updated' => 0,
            'skipped_blank_fae' => 0,
            'skipped_fae_not_found' => 0,
            'skipped_missing_client' => 0,
            'skipped_invalid_kwh' => 0,
            'prorated_rows' => 0,
        ];

        $incidents = [];
        $normalizedRows = [];
        $clientes = [];
        $faes = [];

        foreach ($sheetRows as $sheetRowNumber => $row) {
            if ($sheetRowNumber === 1) {
                continue;
            }

            $normalized = $this->normalizeRow($row, $sheetRowNumber);

            if (! $normalized['has_data']) {
                continue;
            }

            $summary['rows_read']++;
            $normalizedRows[] = $normalized;

            if ($normalized['numerocliente'] !== '') {
                $clientes[$normalized['numerocliente']] = true;
            }

            foreach ($normalized['faes'] as $fae) {
                $faes[$fae] = true;
            }
        }

        $clientesExistentes = DB::table('clientesmedidores')
            ->whereIn('numerocliente', array_keys($clientes))
            ->pluck('numerocliente')
            ->mapWithKeys(fn ($cliente) => [(string) $cliente => true])
            ->all();

        $dtesPorNumero = DB::table('dtes')
            ->where('RutEmisor', self::RUT_EMISOR_ELECTRICIDAD)
            ->whereIn('NumeroDte', array_keys($faes))
            ->select('id', 'NumeroDte', 'Monto')
            ->get()
            ->mapWithKeys(function ($dte) {
                return [(string) $dte->NumeroDte => (array) $dte];
            })
            ->all();

        $existingDetailRows = DB::table('detalle_consumos_basicos')
            ->where('tipo', 'Electricidad')
            ->select('id', 'dtes_id', 'numerocliente', 'consumo')
            ->get();

        $existingDetails = [];
        foreach ($existingDetailRows as $detailRow) {
            $existingDetails[$this->detailKey((int) $detailRow->dtes_id, (string) $detailRow->numerocliente)] = [
                'id' => (int) $detailRow->id,
                'consumo' => (float) $detailRow->consumo,
            ];
        }

        $operation = function () use (
            $normalizedRows,
            $clientesExistentes,
            $dtesPorNumero,
            &$summary,
            &$incidents,
            &$existingDetails,
            $dryRun
        ): void {
            foreach ($normalizedRows as $row) {
                if ($row['fae_raw'] === '') {
                    $summary['skipped_blank_fae']++;
                    $incidents[] = $this->incident($row, 'FAE vacío');
                    continue;
                }

                if (! isset($clientesExistentes[$row['numerocliente']])) {
                    $summary['skipped_missing_client']++;
                    $incidents[] = $this->incident($row, 'numerocliente no existe en clientesmedidores');
                    continue;
                }

                if ($row['consumo'] === null) {
                    $summary['skipped_invalid_kwh']++;
                    $incidents[] = $this->incident($row, 'kWh inválido o vacío');
                    continue;
                }

                $dtes = [];
                $missingFaes = [];

                foreach ($row['faes'] as $fae) {
                    if (! isset($dtesPorNumero[$fae])) {
                        $missingFaes[] = $fae;
                        continue;
                    }

                    $dtes[] = $dtesPorNumero[$fae];
                }

                if ($missingFaes !== []) {
                    $summary['skipped_fae_not_found']++;
                    $incidents[] = $this->incident(
                        $row,
                        'FAE no encontrado en dtes',
                        ['faes_no_encontrados' => implode(', ', $missingFaes)]
                    );
                    continue;
                }

                $allocations = $this->buildAllocations($dtes, $row['consumo']);

                if (count($allocations) > 1) {
                    $summary['prorated_rows']++;
                }

                foreach ($allocations as $allocation) {
                    $detailKey = $this->detailKey((int) $allocation['dtes_id'], $row['numerocliente']);
                    $payload = [
                        'dtes_id' => (int) $allocation['dtes_id'],
                        'tipo' => 'Electricidad',
                        'numerocliente' => $row['numerocliente'],
                        'consumo' => $allocation['consumo'],
                        'updated_at' => now(),
                    ];

                    if (isset($existingDetails[$detailKey])) {
                        $summary['updated']++;

                        if (! $dryRun) {
                            DB::table('detalle_consumos_basicos')
                                ->where('id', $existingDetails[$detailKey]['id'])
                                ->update([
                                    'consumo' => $payload['consumo'],
                                    'updated_at' => $payload['updated_at'],
                                ]);
                        }

                        $existingDetails[$detailKey]['consumo'] = $allocation['consumo'];
                        continue;
                    }

                    $summary['inserted']++;

                    if (! $dryRun) {
                        $payload['created_at'] = now();
                        $detailId = DB::table('detalle_consumos_basicos')->insertGetId($payload);
                        $existingDetails[$detailKey] = [
                            'id' => $detailId,
                            'consumo' => $allocation['consumo'],
                        ];
                    } else {
                        $existingDetails[$detailKey] = [
                            'id' => 0,
                            'consumo' => $allocation['consumo'],
                        ];
                    }
                }
            }
        };

        if ($dryRun) {
            DB::beginTransaction();

            try {
                $operation();
                DB::rollBack();
            } catch (\Throwable $exception) {
                DB::rollBack();
                throw $exception;
            }
        } else {
            DB::transaction($operation);
        }

        return [
            'summary' => $summary,
            'incidents' => $incidents,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function readSheetRows(string $filePath, string $sheetName): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getSheetByName($sheetName);

        if ($worksheet === null) {
            throw new RuntimeException("No existe la hoja '{$sheetName}' en el archivo.");
        }

        $rows = $worksheet->toArray(null, true, true, true);
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return $rows;
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private function normalizeRow(array $row, int $sheetRowNumber): array
    {
        $numerocliente = trim((string) ($row['B'] ?? ''));
        $faeRaw = trim((string) ($row['D'] ?? ''));
        $anio = trim((string) ($row['I'] ?? ''));
        $mes = trim((string) ($row['J'] ?? ''));
        $kwhRaw = trim((string) ($row['K'] ?? ''));

        return [
            'sheet_row' => $sheetRowNumber,
            'numerocliente' => $numerocliente,
            'fae_raw' => $faeRaw,
            'faes' => $this->parseFaes($faeRaw),
            'anio' => $anio,
            'mes' => $mes,
            'consumo_raw' => $kwhRaw,
            'consumo' => $this->parseDecimal($kwhRaw),
            'has_data' => $numerocliente !== '' || $faeRaw !== '' || $anio !== '' || $mes !== '' || $kwhRaw !== '',
        ];
    }

    /**
     * @return array<int, string>
     */
    private function parseFaes(string $faeRaw): array
    {
        if ($faeRaw === '') {
            return [];
        }

        return array_values(array_filter(array_map(function ($part) {
            return trim((string) $part);
        }, preg_split('/\s*\+\s*/', $faeRaw) ?: [])));
    }

    private function parseDecimal(string $value): ?float
    {
        if ($value === '') {
            return null;
        }

        $normalized = str_replace([' ', ','], ['', '.'], $value);

        if (! is_numeric($normalized)) {
            return null;
        }

        return round((float) $normalized, 3);
    }

    /**
     * @param array<int, array<string, mixed>> $dtes
     * @return array<int, array{dtes_id:int, consumo:float}>
     */
    private function buildAllocations(array $dtes, float $consumo): array
    {
        if (count($dtes) === 1) {
            return [[
                'dtes_id' => (int) $dtes[0]['id'],
                'consumo' => round($consumo, 3),
            ]];
        }

        $totalMonto = array_reduce($dtes, function ($carry, $dte) {
            return $carry + (float) ($dte['Monto'] ?? 0);
        }, 0.0);

        $allocations = [];
        $assigned = 0.0;
        $totalItems = count($dtes);

        foreach ($dtes as $index => $dte) {
            if ($index === $totalItems - 1) {
                $portion = round($consumo - $assigned, 3);
            } else {
                $ratio = $totalMonto > 0
                    ? ((float) ($dte['Monto'] ?? 0) / $totalMonto)
                    : (1 / $totalItems);

                $portion = round($consumo * $ratio, 3);
                $assigned += $portion;
            }

            $allocations[] = [
                'dtes_id' => (int) $dte['id'],
                'consumo' => $portion,
            ];
        }

        return $allocations;
    }

    /**
     * @param array<string, mixed> $row
     * @param array<string, mixed> $extra
     * @return array<string, mixed>
     */
    private function incident(array $row, string $reason, array $extra = []): array
    {
        return array_merge([
            'sheet_row' => $row['sheet_row'],
            'reason' => $reason,
            'numerocliente' => $row['numerocliente'],
            'fae' => $row['fae_raw'],
            'anio' => $row['anio'],
            'mes' => $row['mes'],
            'consumo' => $row['consumo_raw'],
        ], $extra);
    }

    private function detailKey(int $dtesId, string $numeroCliente): string
    {
        return $dtesId . '|' . $numeroCliente . '|Electricidad';
    }
}
