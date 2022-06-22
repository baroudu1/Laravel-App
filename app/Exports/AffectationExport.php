<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AffectationExport implements FromArray,WithHeadings,ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function array():array
    {
        $anne_req='SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
        $anne=DB::select($anne_req);
        $date = $anne[0]->année_universitaire;
        $nom_filiere = DB::select('select coordinateur from enseignant where CIN=:cin ', ['cin' => Auth::user()->CIN]);
        $req = 'SELECT u.CIN,e.nom_element,em.section FROM users u,département d ,enseinganant_de_module em,enseignant en,elements e
        WHERE u.CIN=em.id_enseignant AND en.CIN=em.id_enseignant AND d.id_departement=en.id_departement AND e.id_element=em.id_element
        AND  em.année = "' . $date . '" AND em.id_filiere=' . $nom_filiere[0]->coordinateur;
        return DB::select($req);
    }

    public function headings(): array
    {
        return ['CIN', 'nom d\'element', 'section'];
    }
    
}
