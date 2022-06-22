<?php

namespace App\Exports;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class enseignantExport implements FromArray, WithHeadings,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array():array
    {
        $req='SELECT distinct u.CIN,u.nom,u.prenom,u.email,d.nom_departement,
        IF(e.coordinateur=0,"-",f.nom_filiere) AS filière
        FROM users u,enseignant e,département d, filière f WHERE
        u.CIN=e.CIN AND e.id_departement=d.id_departement AND (e.coordinateur=f.id_filiere or e.coordinateur=0)';

        return DB::select($req);
    }

    public function headings(): array
    {
        return ['CIN', 'nom', 'prenom','email', 'departement','filiere(coordonnateur)'];
    }
}
