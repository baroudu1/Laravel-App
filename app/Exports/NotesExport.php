<?php

namespace App\Exports;


use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;



class NotesExport implements FromArray, WithHeadings,ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct($params)
    {
        $this->params = $params;
    }
    public function array(): array
    {
        $anne_req = 'SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
        $anne = DB::select($anne_req);
        $anne = $anne[0]->année_universitaire ?? "";
        $req1 = 'SELECT Co_cntr,Co_tp ,Co_mini_project,Co_examen FROM elements WHERE id_element =' . $this->params->id_element;
        $co = DB::select($req1);
        $req = 'SELECT et.CNE,et.nom,et.prenom,';
        if ($co[0]->Co_cntr != 0) $req .= 'IF(n.N_cntr=-1,"ABS",n.N_cntr) as N_cntr, ';
        if ($co[0]->Co_tp != 0) $req .= 'IF(n.N_tp=-1,"ABS",n.N_tp) as N_tp, ';
        if ($co[0]->Co_mini_project != 0) $req .= 'IF(n.N_mini_project=-1,"ABS",n.N_mini_project) as N_mini_project,';
        if ($co[0]->Co_examen != 0) $req .= 'IF(n.N_examen_nor=-1,"ABS",n.N_examen_nor) as N_examen_nor,IF(n.N_examen_ratt=-1,"ABS",n.N_examen_ratt) as N_examen_ratt ';

        $req .= ' FROM inscription i,note n,etudiant et
        WHERE et.CNE=n.CNE AND i.CNE=et.CNE AND n.id_element=' . $this->params->id_element . '
        AND';
        $rst = DB::select('SELECT ms.id_semestre FROM module_ds_semestre ms,elements e WHERE
        ms.id_module =e.id_module AND e.id_element='.$this->params->id_element);
        $id_se = $rst[0]->id_semestre ?? "";
        if($id_se <= 2){
            $req .= ' LEFT(i.section,1)="' . $this->params->section . '" AND n.année="' . $anne . '"';
        }else{
            $req .= ' RIGHT(i.section,1)="' . $this->params->section . '" AND n.année="' . $anne . '"';
        }
        return DB::select($req);
    }

    public function headings(): array
    {
        $req = 'SELECT Co_cntr,Co_tp ,Co_mini_project,Co_examen FROM elements WHERE id_element =' . $this->params->id_element;
        $co = DB::select($req);
        $header = ['CIN', 'nom', 'prenom'];
        if ($co[0]->Co_cntr != 0) array_push($header, 'note controle');
        if ($co[0]->Co_tp != 0) array_push($header, 'note TP');
        if ($co[0]->Co_mini_project != 0) array_push($header, 'note mini project');
        if ($co[0]->Co_examen != 0) array_push($header, 'note examen', 'note rattrapage');
        return $header;
    }
}
