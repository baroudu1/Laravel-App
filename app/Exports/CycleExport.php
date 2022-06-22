<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CycleExport implements  FromArray, WithHeadings,ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array():array
    {
        $req='SELECT c.nom_cycle,f.nom_filiere FROM filière f,cycle c WHERE c.id_cycle=f.id_cycle';
        return DB::select($req);
    }

    public function headings(): array
    {
        return ['cycle', 'filiere'];
    }
}
