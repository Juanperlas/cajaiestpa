<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConceptsTableSeeder extends Seeder
{
    public function run()
    {
        // Datos a insertar en la tabla "concepts"
        $data = [
            'Matricula',
            'Matricula extemporanea',
            'Traslado externo',
            'Convalidacion de estudios',
            'Examen de suficiencia profesional',
            'Aplicación profesional',
            'Expedicion de titulo profesional',
            'Expedicion de certificados de estudios',
            'Examen de recuperacion',
        ];

        // Iterar sobre los datos y insertarlos en la tabla
        foreach ($data as $conceptName) {
            DB::table('concepts')->insert([
                'concept_name' => $conceptName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
