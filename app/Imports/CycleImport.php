<?php

namespace App\Imports;

use Illuminate\Database\Console\DbCommand;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;

HeadingRowFormatter::default('none');


class CycleImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $req = 'SELECT id_cycle FROM cycle WHERE nom_cycle="' . $row['cycle'] . '"';
            $id_cycle = DB::select($req);
            $id_cycle = $id_cycle[0]->id_cycle ?? null;
            DB::table('cycle')->upsert(
                [
                    'id_cycle' => $id_cycle,
                    'nom_cycle' => $row['cycle'],
                ],
                ['id_cycle'],
                ['nom_cycle']
            );
            $req = 'SELECT id_cycle FROM cycle WHERE nom_cycle="' . $row['cycle'] . '"';
            $id_cycle = DB::select($req);
            $id_cycle = $id_cycle[0]->id_cycle ?? null;
            $req_filiere = 'SELECT id_filiere FROM filière WHERE
                id_cycle=' . $id_cycle . ' AND nom_filiere ="' . $row['filiere'] . '"';
            $id_filiere = DB::select($req_filiere);
            $id_filiere = $id_filiere[0]->id_filiere ?? null;

            DB::table('filière')->upsert(
                [
                    'id_filiere' => $id_filiere,
                    'id_cycle' => $id_cycle,
                    'nom_filiere' => $row['filiere']
                ],
                ['id_filiere'],
                ['nom_filiere']
            );
        }
    }
    public function headingRow(): int
    {
        return 1;
    }
}
