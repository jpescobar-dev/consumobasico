<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class ImportarConsumosElectricidadCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('database.default', 'sqlite');
        Config::set('database.connections.sqlite.database', ':memory:');

        DB::purge('sqlite');
        DB::setDefaultConnection('sqlite');

        Schema::create('dtes', function ($table) {
            $table->increments('id');
            $table->string('NumeroDte');
            $table->string('RutEmisor')->nullable();
            $table->decimal('Monto', 12, 3)->default(0);
        });

        Schema::create('clientesmedidores', function ($table) {
            $table->string('numerocliente')->primary();
        });

        Schema::create('detalle_consumos_basicos', function ($table) {
            $table->increments('id');
            $table->unsignedInteger('dtes_id');
            $table->string('tipo');
            $table->string('numerocliente');
            $table->decimal('consumo', 10, 3)->default(0);
            $table->timestamps();
        });
    }

    public function test_dry_run_reports_rows_without_writing_data(): void
    {
        $this->seedBaseData();
        $filePath = $this->createExcelFixture();

        $this->artisan('consumos:importar-electricidad', [
            'archivo' => $filePath,
            '--dry-run' => true,
        ])->assertExitCode(0);

        $this->assertDatabaseCount('detalle_consumos_basicos', 0);
    }

    public function test_import_inserts_updates_and_is_idempotent(): void
    {
        $this->seedBaseData();
        $filePath = $this->createExcelFixture();

        DB::table('detalle_consumos_basicos')->insert([
            'dtes_id' => 3,
            'tipo' => 'Electricidad',
            'numerocliente' => 'CL-001',
            'consumo' => 70,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->artisan('consumos:importar-electricidad', [
            'archivo' => $filePath,
        ])->assertExitCode(0);

        $this->assertDatabaseCount('detalle_consumos_basicos', 4);

        $this->assertDatabaseHas('detalle_consumos_basicos', [
            'dtes_id' => 1,
            'tipo' => 'Electricidad',
            'numerocliente' => 'CL-001',
            'consumo' => 150.000,
        ]);

        $this->assertDatabaseHas('detalle_consumos_basicos', [
            'dtes_id' => 1,
            'tipo' => 'Electricidad',
            'numerocliente' => 'CL-002',
            'consumo' => 60.000,
        ]);

        $this->assertDatabaseHas('detalle_consumos_basicos', [
            'dtes_id' => 2,
            'tipo' => 'Electricidad',
            'numerocliente' => 'CL-002',
            'consumo' => 40.000,
        ]);

        $this->assertDatabaseHas('detalle_consumos_basicos', [
            'dtes_id' => 3,
            'tipo' => 'Electricidad',
            'numerocliente' => 'CL-001',
            'consumo' => 77.000,
        ]);

        $this->artisan('consumos:importar-electricidad', [
            'archivo' => $filePath,
        ])->assertExitCode(0);

        $this->assertDatabaseCount('detalle_consumos_basicos', 4);
    }

    private function seedBaseData(): void
    {
        DB::table('clientesmedidores')->insert([
            ['numerocliente' => 'CL-001'],
            ['numerocliente' => 'CL-002'],
        ]);

        DB::table('dtes')->insert([
            ['id' => 1, 'NumeroDte' => '1001', 'RutEmisor' => '88272600-2', 'Monto' => 600],
            ['id' => 2, 'NumeroDte' => '1002', 'RutEmisor' => '88272600-2', 'Monto' => 400],
            ['id' => 3, 'NumeroDte' => '2001', 'RutEmisor' => '88272600-2', 'Monto' => 500],
        ]);
    }

    private function createExcelFixture(): string
    {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->setTitle('Todos');

        $rows = [
            ['id', 'N°CLIENTE', 'MEDIDOR', 'FAE', 'PERIODO', 'JUZGADO', 'DIRECCION', 'TIPO', 'AÑO', 'MES', 'kWh', 'Monto'],
            [1, 'CL-001', '', '1001', 'enero', 'J1', 'Dir 1', 'BT4-3', '2025', 'Enero', '150', '1000'],
            [2, 'CL-001', '', '', 'enero', 'J1', 'Dir 1', 'BT4-3', '2025', 'Enero', '99', '1000'],
            [3, 'CL-001', '', '9999', 'enero', 'J1', 'Dir 1', 'BT4-3', '2025', 'Enero', '100', '1000'],
            [4, 'CL-404', '', '1001', 'enero', 'J1', 'Dir 1', 'BT4-3', '2025', 'Enero', '50', '1000'],
            [5, 'CL-002', '', '1001+1002', 'enero', 'J2', 'Dir 2', 'AT4-1', '2025', 'Enero', '100', '1000'],
            [6, 'CL-001', '', '2001', 'enero', 'J1', 'Dir 1', 'BT4-3', '2025', 'Enero', '77', '1000'],
        ];

        foreach ($rows as $rowIndex => $row) {
            $column = 'A';
            foreach ($row as $value) {
                $worksheet->setCellValue("{$column}" . ($rowIndex + 1), $value);
                $column++;
            }
        }

        $path = tempnam(sys_get_temp_dir(), 'detalle-electricidad-test-');
        $xlsxPath = $path . '.xlsx';
        rename($path, $xlsxPath);

        $writer = new Xlsx($spreadsheet);
        $writer->save($xlsxPath);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return $xlsxPath;
    }
}
