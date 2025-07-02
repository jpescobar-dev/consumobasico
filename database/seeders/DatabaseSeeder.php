<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([   
            UsersTableSeeder::class, 
            RolesAndAdminSeeder::class,
            ProveedoresSeeder::class, 
            ItemsSeeder::class, 
            CatalogosSeeder::class,  
            AsignacionesSeeder::class,
            CfinancierosSeeder::class, 
            CcostosSeeder::class,
            ClientesmedidoresSeeder::class,
            EstadosSeeder::class,
            ContratosSeeder::class,
            LicitacionesSeeder::class,
            OrdenescomprasSeeder::class,
            IniciativasSeeder::class,
            ProyectosSeeder::class,
            RolesAndPermissionsSeeder::class
                     
            // ProfilesTableSeeder::class,         
           
        ]); 
    }
}
