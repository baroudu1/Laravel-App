<?php

namespace App\Imports;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');

class AffectatioImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */

    public function collection(Collection $rows)
    {
        //dd($rows);
        $nom_filiere = DB::select('select coordinateur from enseignant where CIN=:cin ', ['cin' => Auth::user()->CIN]);
        $anne_req = 'SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
        $annee = DB::select($anne_req);
        $anne = $annee[0]->année_universitaire ?? "";
        foreach ($rows as $row) {
			$id_element_req='SELECT e.id_element FROM elements e, module_ds_semestre ms WHERE
                        e.id_module=ms.id_module AND ms.id_filiere='.$nom_filiere[0]->coordinateur.' AND e.nom_element="'.$row['nom d\'element'].'"';
		    $id_element=DB::select($id_element_req);
		    $id_element=$id_element[0]->id_element ?? null;
			echo $row['section'];
			if($id_element)
            DB::table('enseinganant_de_module')->upsert(
                [
                    'id_enseignant' => $row['CIN'],
                    'id_element' => $id_element,
                    'id_filiere' => $nom_filiere[0]->coordinateur,
                    'section' => $row['section'],
                    'année' => $anne,
                ],['id_element','année','section'],['id_enseignant']);
        }
    }
    public function headingRow(): int
    {
        return 1;
    }
}
