<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FranchiseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('franchises')->truncate();
        Schema::enableForeignKeyConstraints();

        $data = [
            ['id' => 1,  'branch_office' => 'Merida',                   'franchise_id' => 1,  'is_active' => 1],
            ['id' => 2,  'branch_office' => 'San Cristóbal',            'franchise_id' => 2,  'is_active' => 1],
            ['id' => 3,  'branch_office' => 'Valera',                   'franchise_id' => 3,  'is_active' => 1],
            ['id' => 4,  'branch_office' => '',                         'franchise_id' => 4,  'is_active' => 0],
            ['id' => 5,  'branch_office' => 'Colon',                    'franchise_id' => 5,  'is_active' => 1],
            ['id' => 6,  'branch_office' => 'El Vigia',                 'franchise_id' => 6,  'is_active' => 1],
            ['id' => 7,  'branch_office' => 'Coloncito',                'franchise_id' => 7,  'is_active' => 1],
            ['id' => 8,  'branch_office' => 'La Fria',                  'franchise_id' => 8,  'is_active' => 1],
            ['id' => 9,  'branch_office' => 'Caja Seca',                'franchise_id' => 9,  'is_active' => 1],
            ['id' => 10, 'branch_office' => 'Puerto Ordaz',             'franchise_id' => 10, 'is_active' => 1],
            ['id' => 11, 'branch_office' => 'Maracaibo',                'franchise_id' => 11, 'is_active' => 1],
            ['id' => 12, 'branch_office' => 'San Antonio',              'franchise_id' => 12, 'is_active' => 1],
            ['id' => 13, 'branch_office' => 'Caracas',                  'franchise_id' => 13, 'is_active' => 1],
            ['id' => 14, 'branch_office' => 'Machiques',                'franchise_id' => 14, 'is_active' => 1],
            ['id' => 15, 'branch_office' => 'Bocono',                   'franchise_id' => 15, 'is_active' => 1],
            ['id' => 16, 'branch_office' => 'Anaco',                    'franchise_id' => 16, 'is_active' => 1],
            ['id' => 17, 'branch_office' => 'Cantaura',                 'franchise_id' => 17, 'is_active' => 0],
            ['id' => 18, 'branch_office' => 'Carora',                   'franchise_id' => 18, 'is_active' => 1],
            ['id' => 19, 'branch_office' => 'Maturin',                  'franchise_id' => 19, 'is_active' => 1],
            ['id' => 20, 'branch_office' => 'El Tigre',                 'franchise_id' => 20, 'is_active' => 1],
            ['id' => 21, 'branch_office' => 'Valencia',                 'franchise_id' => 21, 'is_active' => 1],
            ['id' => 22, 'branch_office' => 'Ciudad Bolivar',           'franchise_id' => 22, 'is_active' => 1],
            ['id' => 23, 'branch_office' => 'Temblador',                'franchise_id' => 23, 'is_active' => 1],
            ['id' => 24, 'branch_office' => 'Caripito',                 'franchise_id' => 24, 'is_active' => 1],
            ['id' => 25, 'branch_office' => 'Santa Barbara',            'franchise_id' => 25, 'is_active' => 1],
            ['id' => 26, 'branch_office' => 'Carupano',                 'franchise_id' => 26, 'is_active' => 1],
            ['id' => 27, 'branch_office' => 'El Guayabo',               'franchise_id' => 27, 'is_active' => 1],
            ['id' => 28, 'branch_office' => 'Corporacion',              'franchise_id' => 28, 'is_active' => 1],
            ['id' => 29, 'branch_office' => 'Rubio',                    'franchise_id' => 29, 'is_active' => 0],
            ['id' => 30, 'branch_office' => '',                         'franchise_id' => 30, 'is_active' => 0],
            ['id' => 31, 'branch_office' => 'Capacho',                  'franchise_id' => 31, 'is_active' => 0],
            ['id' => 32, 'branch_office' => 'Punta de Mata',            'franchise_id' => 32, 'is_active' => 0],
            ['id' => 33, 'branch_office' => 'Tipuro',                   'franchise_id' => 33, 'is_active' => 0],
            ['id' => 34, 'branch_office' => 'Ureña',                    'franchise_id' => 34, 'is_active' => 0],
            ['id' => 35, 'branch_office' => 'La Villa del Rosario',     'franchise_id' => 35, 'is_active' => 0],
            ['id' => 36, 'branch_office' => 'San Jose de Perijá',       'franchise_id' => 36, 'is_active' => 0],
            ['id' => 37, 'branch_office' => 'Pendiente',                'franchise_id' => 37, 'is_active' => 0],
            ['id' => 38, 'branch_office' => 'Pendiente',                'franchise_id' => 38, 'is_active' => 0],
            ['id' => 39, 'branch_office' => 'Pendiente',                'franchise_id' => 39, 'is_active' => 0],
            ['id' => 40, 'branch_office' => 'Pendiente',                'franchise_id' => 40, 'is_active' => 0],
            ['id' => 41, 'branch_office' => 'Valencia 2',               'franchise_id' => 41, 'is_active' => 1],
            ['id' => 42, 'branch_office' => 'Puertos de Altagracia',    'franchise_id' => 42, 'is_active' => 1],
            ['id' => 43, 'branch_office' => 'Catia 2',                  'franchise_id' => 43, 'is_active' => 1],
            ['id' => 44, 'branch_office' => 'Santa Barbara de Barinas', 'franchise_id' => 44, 'is_active' => 1],
        ];

        DB::table('franchises')->insert($data);
    }
};