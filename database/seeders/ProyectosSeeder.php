<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Proyecto;

class ProyectosSeeder extends Seeder
{
   
    public function run(): void
    {
        DB::table('proyectos')->insert([
        [
            'proyecto'=>'Habilitacion de kitchenette- Juzgado de Comp. Comun de Aysen',
            'descripcion'=>'Adquisicion de 8 sillas y 2 mesas de comedor',
            'codigo'=>'1471-25-001',
            'fecha_inicio'=>'2025-01-01',
            'fecha_termino'=>'2025-06-30',
            'avance'=>'0',
            'monto_estimado'=>1500000,
            'monto_asignado'=>1324946,
            'cfinanciero_id'=>'1400',
            'estado_id'=>'5',
        ],

        [
            'proyecto'=>'Habilitacion de kitchenette- Juzgado Civil de Coyhaique',
            'descripcion'=>'Mobiliario idóneo para la cocina del Tribunal, toda vez, que a la fecha contingentemente se ha empleado mobiliario antiguo de oficina, que no es apto para esos fines.',
            'codigo'=>'1400-25-002',
            'fecha_inicio'=>'2025-01-01',
            'fecha_termino'=>'2025-06-30',
            'avance'=>'0',
            'monto_estimado'=>2000000,
            'monto_asignado'=>0,
            'cfinanciero_id'=>'1400',
            'estado_id'=>'5',
        ],

        [
            'proyecto'=>'Renovacion de sillas funcionario - Juzgado Civil de Coyhaique',
            'descripcion'=>'Primer Juzgado Letras -10 sillas ergonométricas para los funcionarios (actuarios) y cambiar los sillones de la Sra. juez y secretario Titular del Tribunal, mobiliario que tiene una data aproximada de 10 años, lo que permitiría procurar por la salud física y bienes del personal.',
            'codigo'=>'1400-25-003',
            'fecha_inicio'=>'2025-01-01',
            'fecha_termino'=>'2025-06-30',
            'avance'=>'0',
            'monto_estimado'=>1010000,
            'monto_asignado'=>1167050,
            'cfinanciero_id'=>'1400',
            'estado_id'=>'5',
        ],

        [
            'proyecto'=>'Renovacion de silla funcionaria - Juzgado Mixto Chile Chico',
            'descripcion'=>'Solicita silla adecuada para  funcionaria Yamily Ema Pereda Farah',
            'codigo'=>'1400-25-004',
            'fecha_inicio'=>'2025-01-01',
            'fecha_termino'=>'2025-06-30',
            'avance'=>'0',
            'monto_estimado'=>1000000,
            'monto_asignado'=>1000000,
            'cfinanciero_id'=>'1400',
            'estado_id'=>'5',
        ],

        [
            'proyecto'=>'Adquisicion de Container- para habilitarse como bodega CAPJ',
            'descripcion'=>' Un contenedor de 40 pis, para utilizarse como bodega para bienes en desuso y documentacion de la jurisdiccion, las medidas son: 12,19 metros de largo, 2,44 metros de ancho, 2,59 metros de alto,  30m2',
            'codigo'=>'1400-25-005',
            'fecha_inicio'=>'2025-01-01',
            'fecha_termino'=>'2025-06-30',
            'avance'=>'0',
            'monto_estimado'=>4165000,
            'monto_asignado'=>4046000,
            'cfinanciero_id'=>'1400',
            'estado_id'=>'5',
        ],

        [
            'proyecto'=>'Compra de Refrigerador para Kitchenette - Juzgado Civil',
            'descripcion'=>'Adquisición refrigerador Top Mount Freezer 341 Litros Space Max',
            'codigo'=>'1400-25-006',
            'fecha_inicio'=>'2025-01-01',
            'fecha_termino'=>'2025-06-30',
            'avance'=>'0',
            'monto_estimado'=>500000,
            'monto_asignado'=>415310,
            'cfinanciero_id'=>'1400',
            'estado_id'=>'5',
        ],

        [
            'proyecto'=>'Compra de Refrigerador para Kitchenette - Tribunal Oral',
            'descripcion'=>'Adquisición refrigerador Top Mount Freezer 341 Litros Space Max',
            'codigo'=>'1400-25-007',
            'fecha_inicio'=>'2025-01-01',
            'fecha_termino'=>'2025-06-30',
            'avance'=>'0',
            'monto_estimado'=>500000,
            'monto_asignado'=>415310,
            'cfinanciero_id'=>'1402',
            'estado_id'=>'5',
        ],

        [
            'proyecto'=>'Compra de Refrigerador para Kitchenette - Juzgado de Familia ',
            'descripcion'=>'Adquisición refrigerador Top Mount Freezer 341 Litros Space Max',
            'codigo'=>'1400-25-008',
            'fecha_inicio'=>'2025-01-01',
            'fecha_termino'=>'2025-06-30',
            'avance'=>'0',
            'monto_estimado'=>500000,
            'monto_asignado'=>415310,
            'cfinanciero_id'=>'1451',
            'estado_id'=>'5',
        ],

        [
            'proyecto'=>'Compra de Refrigerador para Kitchenette - Juzgado Laboral ',
            'descripcion'=>'Adquisición refrigerador Top Mount Freezer 341 Litros Space Max',
            'codigo'=>'1400-25-009',
            'fecha_inicio'=>'2025-01-01',
            'fecha_termino'=>'2025-06-30',
            'avance'=>'0',
            'monto_estimado'=>500000,
            'monto_asignado'=>415310,
            'cfinanciero_id'=>'1431',
            'estado_id'=>'5',
        ],

        [
            'proyecto'=>'Reposición de termos de 60 litros - Juzgado comp. comun Aysen',
            'descripcion'=>'Reposición de termos de 60 litros',
            'codigo'=>'1400-25-010',
            'fecha_inicio'=>'2025-01-01',
            'fecha_termino'=>'2025-06-30',
            'avance'=>'0',
            'monto_estimado'=>1400000,
            'monto_asignado'=>1627451,
            'cfinanciero_id'=>'1471',
            'estado_id'=>'5',
        ],     
           
        [
            'proyecto'=>'Provision Equipos de Aire Acondicionado - Juzgado civil',
            'descripcion'=>'Provisión e Instalación de equipos de Aire Acondicionado en Oficinas primer piso: La oficina del primer piso, en donde funciona atención de publico y que es compartida por 5 funcionarios es necesaria la instalación de  equipo de Aire, que mejore las condiciones de trabajo de este pool de funcionarios.',
            'codigo'=>'1400-25-011',
            'fecha_inicio'=>'2025-01-01',
            'fecha_termino'=>'2025-06-30',
            'avance'=>'0',
            'monto_estimado'=>3000000,
            'monto_asignado'=>1363133,
            'cfinanciero_id'=>'1400',
            'estado_id'=>'5',
        ],           

        ]);
    }
}