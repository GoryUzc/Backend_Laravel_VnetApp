<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::enableForeignKeyConstraints();
        $data = [
            ['id'=> 1, 'name'=> 'admin', 'description'=>'Administrador de la aplicacion, tiene todos los permisos.'],
            ['id'=> 2, 'name'=> 'supervisor', 'description'=>'Agente interno de la sucursales de Vnet encargados de la gestion de acceso a la red y seguimientos de las ordenes de instalacion.'],
            ['id'=> 3, 'name'=> 'contractor', 'description'=>'La entidad encargada de realizar las ordenes de instalacion.'],
            ['id'=> 4, 'name'=> 'crew', 'description'=>'Tecnico de las contratistas.'],
            
        ];

         DB::table('roles')->insert($data);
    }
}
