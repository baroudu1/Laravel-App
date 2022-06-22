<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
class modulesExport implements FromArray, WithHeadings,ShouldAutoSize,WithColumnWidths
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
        $req = 'SELECT m.nom_module,e.nom_element, f.nom_filiere,s.nom_semestre,
            e.Co_element,e.Co_cntr,e.Co_tp,e.Co_mini_project,e.Co_examen
            FROM elements e,module m,semetre s,filière f, module_ds_semestre ms WHERE e.id_module=m.id_module
            AND m.id_module=ms.id_module AND ms.id_semestre=s.id_semestre AND ms.id_filiere=f.id_filiere';
        return DB::select($req);
    }
    public function headings(): array
    {
        return ['nom module', 'nom element', 'filiere', 'semestre', 'coefficient dans module', 'coefficient de CC', 'coefficient de TP', 'coefficient de mini project', 'coefficient de CU'];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 50,
            'B' => 50
        ];
    }
}
