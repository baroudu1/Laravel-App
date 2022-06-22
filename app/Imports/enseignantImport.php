<?php

namespace App\Imports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

HeadingRowFormatter::default('none');
class enseignantImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */

    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {

            $req_depart = 'SELECT id_departement FROM département WHERE nom_departement="' . $row['departement'] . '"';
            $id_depart = DB::select($req_depart);
            $id_depart = $id_depart[0]->id_departement ?? null;
            DB::table('users')->upsert(
                [
                    'CIN' => $row['CIN'],
                    'nom' => $row['nom'],
                    'prenom' => $row['prenom'],
                    'email' => $row['email'],
                    'password' => Hash::make($row['CIN']),
                ],
                ['CIN'],
                ['nom', 'prenom', 'email']
            );
            $id_fil = 0;
            if ($row['filiere(coordonnateur)'] != "-") {
                $req_fil = 'SELECT id_filiere FROM filière WHERE nom_filiere="' . $row['filiere(coordonnateur)'] . '"';
                $id_fil = DB::select($req_fil);
                $id_fil = $id_fil[0]->id_filiere;
            }
            if ($id_fil != 0) {
                $reqq = 'SELECT CIN from enseignant WHERE coordinateur=' . $id_fil;
                $exist = DB::select($reqq);
                $exist = $exist[0]->CIN ?? "";
                if ($exist != "") {
                    DB::table('enseignant')->where('CIN', $exist)->update([
                        'coordinateur' => 0,
                    ]);
                }
            }
            DB::table('enseignant')->upsert(
                [
                    'CIN' => $row['CIN'],
                    'id_departement' => $id_depart,
                    'coordinateur' =>  $id_fil,
                ],
                ['CIN'],
                ['id_departement', 'coordinateur']
            );
        }
    }

    public function headingRow(): int
    {
        return 1;
    }
}
