<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class EtudiantImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        //dd($collection);
        $i = 0;
        $j = 0;
        $cart = array();
        foreach ($collection as $row) {
            if ($i == 8) {
                foreach ($row as $item) {
                    if ($j >= 8 && $item != null && $item != "Moyenne du Semestre" && $item != "Résultat du Semestre") {
                        array_push($cart, $item);
                    }
                    $j++;
                }
            }
            $i++;
        }
        dd($cart);
    }
}
