<?php

namespace Database\Seeders;

use App\Models\Modulo;
use App\Models\ResultadoAprendizaje;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriteriosEvaluacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/csv/criterios_evaluacion.csv');

        if (!file_exists($path)) {
            $this->command->error("CSV no encontrado: $path");
            return;
        }

        // Leer todas las líneas y parsear con str_getcsv
        $rows = array_map('str_getcsv', file($path));

        // El primer registro es la cabecera
        $header = array_map('trim', array_shift($rows));

        $data = [];
        foreach ($rows as $row) {
            // Ignorar filas vacías o mal formadas
            if (count($row) < count($header)) {
                continue;
            }

            $rec = array_combine($header, $row);

            $moduloId = Modulo::where('codigo', trim($rec['cod_modulo'] ?? ''))->first()->id ?? null;

            $data[] = [
                'resultado_aprendizaje_id' => ResultadoAprendizaje::where(['modulo_id' => $moduloId, 'codigo' => "RA" . trim($rec['id_ra'] ?? '')])->first()->id ?? null,
                'codigo' => trim($rec['id_criterio'] ?? ''),
                'descripcion' => $rec['definicion'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insertar/actualizar usando upsert para evitar duplicados por 'codigo'
        DB::transaction(function () use ($data) {
            foreach (array_chunk($data, 200) as $chunk) {
                DB::table('criterios_evaluacion')->upsert(
                    $chunk,
                    ['resultado_aprendizaje_id', 'codigo'], // llave única para evitar duplicados
                    ['descripcion', 'updated_at']
                );
            }
        });
    }
}
