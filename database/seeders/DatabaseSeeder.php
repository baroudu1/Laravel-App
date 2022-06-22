<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        DB::table('département')->insert(
            [
                ['nom_departement' => 'Informatique'],
                ['nom_departement' => 'Mathématiques'],
                ['nom_departement' => 'Sciences de la Vie'],
                ['nom_departement' => 'Environnement'],
                ['nom_departement' => 'Chimie'],
                ['nom_departement' => 'Génie Electrique'],
                ['nom_departement' => 'Génie Mécanique'],
                ['nom_departement' => 'Génie Industriel'],
                ['nom_departement' => 'TEC et Gestion']
            ]
        );
        DB::table('semetre')->insert(
            [
                ['nom_semestre' => 'S1'],
                ['nom_semestre' => 'S2'],
                ['nom_semestre' => 'S3'],
                ['nom_semestre' => 'S4'],
                ['nom_semestre' => 'S5'],
                ['nom_semestre' => 'S6'],
                ['nom_semestre' => 'S7'],
                ['nom_semestre' => 'S8'],
                ['nom_semestre' => 'S9'],
                ['nom_semestre' => 'S10'],
                ['nom_semestre' => 'S11'],
                ['nom_semestre' => 'S12']
            ]
        );
        DB::table('cycle')->insert(['nom_cycle' => 'Tronc Commun']);
        DB::table('filière')->insert(
            [
                [
                    'nom_filiere' => 'MIP',
                    'id_cycle' => 1
                ],
                [
                    'nom_filiere' => 'BCG',
                    'id_cycle' => 1
                ]
            ]
        );

        DB::table('users')->insert(
            [
                'CIN' => 'CN00000',
                'nom' => 'sabaaoui',
                'prenom' => 'mustafa',
                'email' => 'mustafa.sabaaoui@usmba.ac.ma',
                'password' => Hash::make('mustafa.sabaaoui@usmba.ac.ma'),
            ]
        );
        DB::table('admin')->insert(['CIN' => 'CN00000']);
    }
}
