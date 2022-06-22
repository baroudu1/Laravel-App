<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');
class modulesImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */

    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {

            $req_sem = 'SELECT id_semestre FROM semetre WHERE nom_semestre="' . $row['semestre'] . '"';
            $id_sem = DB::select($req_sem);
            $id_sem = $id_sem[0]->id_semestre;
            $req_fil = 'SELECT id_filiere FROM filière WHERE nom_filiere="' . $row['filiere'] . '"';
            $id_fil = DB::select($req_fil);
            $id_fil = $id_fil[0]->id_filiere;
            $req_ver = 'SELECT m.id_module FROM module m, module_ds_semestre ms WHERE
            m.id_module=ms.id_module  AND ms.id_filiere=' . $id_fil . ' AND
            m.nom_module="' . $row['nom module'] . '"';
            $ver = DB::select($req_ver);

            if (count($ver) == 0) {
                $id_module = DB::table('module')->insertGetId(['nom_module' => $row['nom module']]);
                DB::table('module_ds_semestre')->insert([
                    'id_module' => $id_module,
                    'id_semestre' => $id_sem,
                    'id_filiere' => $id_fil,
                ]);
                $ver = $id_module;
            } else {
                $ver = $ver[0]->id_module;
            }
            $co_cntr = ($row['coefficient de CC'] == "") ? 0 : $row['coefficient de CC'];
            $co_tp = ($row['coefficient de TP'] == "") ? 0 : $row['coefficient de TP'];
            $co_minp = ($row['coefficient de mini project'] == "") ? 0 : $row['coefficient de mini project'];
            $co_ex = ($row['coefficient de CU'] == "") ? 0 : $row['coefficient de CU'];


            DB::table('elements')->insert(
                [
                    'id_module' => $ver,
                    'nom_element' => $row['nom element'],
                    'Co_element' => $row['coefficient dans module'],
                    'Co_cntr' => $co_cntr,
                    'Co_tp' => $co_tp,
                    'Co_mini_project' => $co_minp,
                    'Co_examen' => $co_ex,
                ]
            );
        }
    }
    public function headingRow(): int
    {
        return 1;
    }
}
