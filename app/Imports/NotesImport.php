<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
HeadingRowFormatter::default('none');

class NotesImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    public function collection(Collection $rows)
    {
        $req1 = 'SELECT Co_cntr,Co_tp ,Co_mini_project,Co_examen FROM elements WHERE id_element =' . $this->params->id_element;
        $co = DB::select($req1);
        $ctr = ($co[0]->CO_cntr != 0);
        $tp = ($co[0]->Co_tp != 0);
        $minip = ($co[0]->Co_mini_project != 0);
        $ex = ($co[0]->Co_examen != 0);
        foreach ($rows as $row) {
            if ($ctr) DB::table('notes')->where('CNE', $row['CNE'])->where('année', $this->params->annee)->update(['N_cntr' => $rows['note controle']]);
            if ($tp) DB::table('notes')->where('CNE', $row['CNE'])->where('année', $this->params->annee)->update(['N_tp' => $rows['note TP']]);
            if ($minip) DB::table('notes')->where('CNE', $row['CNE'])->where('année', $this->params->annee)->update(['N_mini_project' => $rows['note mini project']]);
            if ($ex) DB::table('notes')->where('CNE', $row['CNE'])->where('année', $this->params->annee)->update(['N_examen_nor' => $rows['note examen'], 'N_examen_ratt' => $rows['note rattrapage']]);
        }
    }
    public function headingRow(): int
    {
        return 1;
    }
}
