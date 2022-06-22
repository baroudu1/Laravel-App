<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


use App\Exports\enseignantExport;
use App\Imports\enseignantImport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;


class ControllergestionEtudiant extends Controller
{
    public function show($menu = 'Gestion des Etudiants')
    {
        $CIN = Auth::user()->CIN;
        $nom = Auth::user()->nom;
        $prenom = Auth::user()->prenom;
        $poste = null;
        $admin = DB::select('select  CIN from admin where CIN=:cin', ['cin' => $CIN]);
        $coordinateur = DB::select('select coordinateur from enseignant where CIN=:cin ', ['cin' => $CIN]);
        foreach ($admin as $keye) {
            $admin = $keye->CIN;
        }
        //dd($admin);
        foreach ($coordinateur as $keeye) {
            $coordinateur = $keeye->coordinateur;
        }
        if ($admin != []) {
            $poste = -1;
        } else if ($coordinateur != []) {
            $poste = $coordinateur;
        } else {
            $poste = -2;
        }
        $name = $menu;
        $cycle = DB::select('SELECT * FROM cycle');
        $anne = DB::select('SELECT DISTINCT année_universitaire as annee FROM inscription');

        view('inc.nav');
        return view('gestionEtudiant', compact('name', 'nom', 'prenom',  'cycle', 'anne', 'poste'));
    }


    public function insert_update_etudiant(Request $request)
    {
        $request->validate([
            'CNE' => ['required'],
            'nom' => ['required'],
            'prenom' => ['required'],
            'id_fi' => ['required'],
        ]);
        $secc = $request->id_sec1;
        if ($request->id == 0) {
            $ele_semes_req = 'SELECT id_element,nom_element FROM elements e WHERE
                            id_module IN
                            (SELECT id_module FROM module_ds_semestre WHERE id_semestre=' . $request->id_se . ')';
            $eles_semes = DB::select($ele_semes_req);
            $id_eles = [];
            foreach ($eles_semes as $ele_semes)
                array_push($id_eles, $ele_semes->id_element);

            DB::table('note')->where('CNE', $request->CNE)->where('année', $request->annee)
                ->whereIN('id_element', $id_eles)->delete();

            DB::table('etudiant')->where('CNE', $request->CNE)->update(
                [
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                ]
            );
            if ($request->id_se <= 2) {
                $rst = DB::select('SELECT DISTINCT  RIGHT(section,1) section FROM inscription
                WHERE année_universitaire LIKE "' . $request->annee . '" AND CNE ="' . $request->CNE . '"');
                $rst =  $rst[0]->section ?? "";
                $secc .= $rst;
            } else {
                $rst = DB::select('SELECT DISTINCT  LEFT(section,1) section FROM inscription
                WHERE année_universitaire LIKE "' . $request->annee . '" AND CNE ="' . $request->CNE . '"');
                $rst =  $rst[0]->section ?? "";
                $secc .= $rst;
            }
            DB::table('inscription')->where('CNE', $request->CNE)->update(
                [
                    'section' => $secc,
                ]
            );
        } else {
            $secc .= $request->id_sec1;
            DB::table('etudiant')->insert(
                [
                    'CNE' => $request->CNE,
                    'nom' => $request->nom,
                    'prenom' => $request->prenom,
                ]
            );
            DB::table('inscription')->insert(
                [
                    'CNE' => $request->CNE,
                    'id_filiere' => $request->id_fi,
                    'année_universitaire' => $request->annee,
                    'niveau' => $request->id_ni,
                    'section' => $secc,
                ]
            );
        }

        $modules = explode(",", $request->modules);
        foreach ($modules as $module) {
            $id_element1 = DB::select('SELECT id_element FROM elements  where id_module=' . $module);
            foreach ($id_element1 as $id_e1) {
                DB::table('note')->insert([
                    'CNE' => $request->CNE,
                    'id_element' => $id_e1->id_element,
                    'année' => $request->annee,
                ]);
            }
        }
        return response()->json(['insert' => $request->id]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'id_fi' => ['required'],
            'file' => ['required'],
        ]);
        $date1 = Carbon::now()->format('Y');
        $date = $date1 . "-" . ($date1 + 1);

        $path = $request->file('file');
        $lenghtt = count($path);
        if ($lenghtt > 0 && $lenghtt < 4) {
            if ($request->id_cy == 1) {
                if ($lenghtt == 1) {
                    return response()->json(['success' => false, 'message' => 'Importer les deux Fichier']);
                }
                $file1 = $request->file('file')[0];
                $file2 = $request->file('file')[1];

                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadsheet1 = $reader->load($file1);
                $worksheet1 = $spreadsheet1->getActiveSheet();
                $titel1 = $worksheet1->getCellByColumnAndRow(1, 6)->getValue();
                $highestRow1 = $worksheet1->getHighestRow(); // e.g. 10
                $highestColumn1 = $worksheet1->getHighestColumn(); // e.g 'F'
                $highestColumnIndex1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn1); // e.g. 5

                $reader2 = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $spreadsheet2 = $reader2->load($file2);
                $worksheet2 = $spreadsheet2->getActiveSheet();
                $highestRow2 = $worksheet2->getHighestRow(); // e.g. 10
                $highestColumn2 = $worksheet2->getHighestColumn(); // e.g 'F'
                $highestColumnIndex2 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn2); // e.g. 5
                //dd(trim($titel1));
                $annee1 = 0;
                $hrow1 = 0;
                $hcol1 = 0;
                $annee2 = 0;
                $hrow2 = 0;
                $hcol2 = 0;
                if (str_contains(trim($titel1), "1ère année")) {

                    $annee1 = $worksheet1;
                    $hrow1 = $highestRow1;
                    $hcol1 = $highestColumnIndex1;
                    $annee2 = $worksheet2;
                    $hrow2 = $highestRow2;
                    $hcol2 = $highestColumnIndex2;
                } else {
                    $annee1 = $worksheet2;
                    $hrow1 = $highestRow2;
                    $hcol1 = $highestColumnIndex2;
                    $annee2 = $worksheet1;
                    $hrow2 = $highestRow1;
                    $hcol2 = $highestColumnIndex1;
                }
                for ($row = 11; $row <= $hrow1 - 4; ++$row) {
                    //section
                    $test = str_replace("S1_", "", $annee1->getCellByColumnAndRow(8, $row)->getValue());
                    $test1 = str_replace("S2_", "", $test);
                    $test2 = str_replace("S3_", "", $test1);
                    $test3 = str_replace("S4_", "", $test2);
                    $test4 = str_replace(" ", "", $test3);
                    ///////
                    DB::table('etudiant')->upsert(
                        [
                            'CNE' => $annee1->getCellByColumnAndRow(1, $row)->getValue(),
                            'nom' => $annee1->getCellByColumnAndRow(2, $row)->getValue(),
                            'prenom' => $annee1->getCellByColumnAndRow(3, $row)->getValue(),
                        ],
                        [
                            'CNE'
                        ],
                        [
                            'nom', 'prenom'
                        ]
                    );
                    ///
                    if (strlen($test4) ==  1) {
                        $test4 .= $test4;
                    }
                    /////
                    DB::table('inscription')->insert([
                        'CNE' => $annee1->getCellByColumnAndRow(1, $row)->getValue(),
                        'id_filiere' => $request->id_fi,
                        'année_universitaire' => $date,
                        'niveau' => substr($annee1->getCellByColumnAndRow(5, $row)->getValue(), 0, 1),
                        'section' => $test4,
                    ]);
                    //////
                    $id_element = null;
                    /////
                    if (trim($annee1->getCellByColumnAndRow(6, $row)->getValue()) == 'nouvelle inscription') {

                        $id_element = DB::select('SELECT e.id_element FROM elements e , module_ds_semestre ms
                            WHERE ms.id_semestre IN (1,2) AND ms.id_module=e.id_module AND ms.id_filiere = ' . $request->id_fi);
                        foreach ($id_element as $id_e) {
                            DB::table('note')->insert([
                                'CNE' => $annee1->getCellByColumnAndRow(1, $row)->getValue(),
                                'id_element' => $id_e->id_element,
                                'année' => $date,
                            ]);
                        }
                    } else {
                        $k = 0;
                        for ($col = 9; $col <= $hcol1 - 2; ++$col) {
                            if ($col == (9 + 2 * $k)) {
                                $value = $annee1->getCellByColumnAndRow($col, 9)->getValue();
                                $cell = $annee1->getCellByColumnAndRow($col, $row);
                                $color = $cell->getStyle()->getFill()->getStartColor()->getRGB();
                                if (trim($value) != "Moyenne du Semestre" && $color == "FF0000") {
                                    $id_element1 = DB::select('SELECT e.id_element FROM elements e , module_ds_semestre ms, module m WHERE ms.id_semestre IN (1,2) AND m.id_module=e.id_module AND ms.id_module=e.id_module AND ms.id_filiere = ' . $request->id_fi . ' AND m.nom_module LIKE "%' . $value . '%"');
                                    foreach ($id_element1 as $id_e1) {
                                        DB::table('note')->insert([
                                            'CNE' => $annee1->getCellByColumnAndRow(1, $row)->getValue(),
                                            'id_element' => $id_e1->id_element,
                                            'année' => $date,
                                        ]);
                                    }
                                }
                                $k++;
                            }
                        }
                    }
                }
                ///////////////////////

                ////////

                for ($row = 11; $row <= $hrow2 - 1; ++$row) {
                    //section
                    $test = str_replace("S1_", "", $annee2->getCellByColumnAndRow(8, $row)->getValue());
                    $test1 = str_replace("S2_", "", $test);
                    $test2 = str_replace("S3_", "", $test1);
                    $test3 = str_replace("S4_", "", $test2);
                    $test4 = str_replace(" ", "", $test3);
                    ///////
                    DB::table('etudiant')->upsert(
                        [
                            'CNE' => $annee2->getCellByColumnAndRow(1, $row)->getValue(),
                            'nom' => $annee2->getCellByColumnAndRow(2, $row)->getValue(),
                            'prenom' => $annee2->getCellByColumnAndRow(3, $row)->getValue(),
                        ],
                        [
                            'CNE'
                        ],
                        [
                            'nom', 'prenom'
                        ]
                    );
                    ///
                    if (strlen($test4) ==  1) {
                        $test4 .= $test4;
                    }
                    /////
                    DB::table('inscription')->insert([
                        'CNE' => $annee2->getCellByColumnAndRow(1, $row)->getValue(),
                        'id_filiere' => $request->id_fi,
                        'année_universitaire' => $date,
                        'niveau' => substr($annee2->getCellByColumnAndRow(5, $row)->getValue(), 0, 1),
                        'section' => $test4,
                    ]);
                    //////
                    $id_element = null;
                    /////
                    $k = 0;
                    for ($col = 9; $col <= $hcol2 - 2; ++$col) {
                        if ($col == (9 + 2 * $k)) {
                            $value = $annee2->getCellByColumnAndRow($col, 9)->getValue();
                            $cell = $annee2->getCellByColumnAndRow($col, $row);
                            $color = $cell->getStyle()->getFill()->getStartColor()->getRGB();
                            if (trim($value) != "Moyenne du Semestre" && ($color == "FF0000" || $color == "92D050")) {
                                $id_element1 = DB::select('SELECT e.id_element FROM elements e , module_ds_semestre ms, module m WHERE ms.id_semestre IN (1,2,3,4) AND m.id_module=e.id_module AND ms.id_module=e.id_module AND ms.id_filiere = ' . $request->id_fi . ' AND m.nom_module LIKE "%' . $value . '%"');
                                foreach ($id_element1 as $id_e1) {
                                    DB::table('note')->insert([
                                        'CNE' => $annee2->getCellByColumnAndRow(1, $row)->getValue(),
                                        'id_element' => $id_e1->id_element,
                                        'année' => $date,
                                    ]);
                                }
                            }
                            $k++;
                        }
                    }
                }
                /////
                return response()->json(['success' => true, 'message' => 'wawawa']);
            } else {
                //lst et master
                if ($request->id_cy == 2) {
                    if ($lenghtt != 1) {
                        return response()->json(['success' => false, 'message' => 'Importer un seul Fichier']);
                    }
                    $file1 = $request->file('file')[0];
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    $spreadsheet = $reader->load($file1);
                    $worksheet = $spreadsheet->getActiveSheet();
                    $titel1 = $worksheet->getCellByColumnAndRow(1, 6)->getValue();
                    $hrow1  = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn1 = $worksheet->getHighestColumn(); // e.g 'F'
                    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn1); // e.g. 5
                    ////////descricription colomn
                    if ($worksheet->getCellByColumnAndRow(2, $hrow1)->getValue() == "Module Aquis pour l'année en cours")
                        $hrow1 -= 4;
                    ////////

                    for ($row = 11; $row <= $hrow1; ++$row) {
                        if ($worksheet->getCellByColumnAndRow(1, $row)->getValue() != null && $worksheet->getCellByColumnAndRow(2, $row)->getValue() != null && $worksheet->getCellByColumnAndRow(3, $row)->getValue() != null) {
                            DB::table('etudiant')->upsert(
                                [
                                    'CNE' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                                    'nom' => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
                                    'prenom' => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
                                ],
                                [
                                    'CNE'
                                ],
                                [
                                    'nom', 'prenom'
                                ]
                            );
                            /////////////
                            DB::table('inscription')->insert([
                                'CNE' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                                'id_filiere' => $request->id_fi,
                                'année_universitaire' => $date,
                                'niveau' => substr($worksheet->getCellByColumnAndRow(5, $row)->getValue(), 0, 1),
                            ]);
                            //////

                            $k = 0;
                            for ($col = 9; $col <= $highestColumnIndex - 11; ++$col) {
                                if ($col == (9 + 2 * $k)) {
                                    $value = $worksheet->getCellByColumnAndRow($col, 9)->getValue();
                                    $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                    $color = $cell->getStyle()->getFill()->getStartColor()->getRGB();
                                    if (trim($value) != "Moyenne du Semestre" && ($color == "FF0000"  || $color == "92D050")) {
                                        $id_element1 = DB::select('SELECT e.id_element FROM elements e , module_ds_semestre ms, module m WHERE
                                        ms.id_semestre IN (5,6) AND m.id_module=e.id_module AND
                                        ms.id_module=e.id_module AND ms.id_filiere = ' . $request->id_fi . '
                                        AND m.nom_module LIKE "%' . $value . '"');
                                        foreach ($id_element1 as $id_e1) {
                                            DB::table('note')->insert(
                                                [
                                                    'CNE' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                                                    'id_element' => $id_e1->id_element,
                                                    'année' => $date,

                                                ]
                                            );
                                        }
                                    }
                                    $k++;
                                }
                            }
                        }
                    }

                    return response()->json(['success' => true, 'message' => 'success']);
                }
                ///////////////////////
                if ($request->id_cy == 3) {
                    if ($lenghtt != 2) {
                        return response()->json(['success' => false, 'message' => 'Importer les deux Fichiers']);
                    }
                    $file = $request->file('file');
                    foreach ($file as $file1) {

                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                        $spreadsheet = $reader->load($file1);
                        $worksheet = $spreadsheet->getActiveSheet();
                        $titel1 = $worksheet->getCellByColumnAndRow(1, 6)->getValue();
                        $hrow1  = $worksheet->getHighestRow(); // e.g. 10
                        $highestColumn1 = $worksheet->getHighestColumn(); // e.g 'F'
                        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn1); // e.g. 5
                        ////////descricription colomn
                        if ($worksheet->getCellByColumnAndRow(2, $hrow1)->getValue() == "Module Aquis pour l'année en cours")
                            $hrow1 -= 4;
                        ////////
                        $niiv = 2;
                        if (str_contains(trim($file1->getClientOriginalName()), "1ème")) {
                            $niiv = 1;
                        }
                        for ($row = 11; $row <= $hrow1; ++$row) {
                            if ($worksheet->getCellByColumnAndRow(1, $row)->getValue() != null && $worksheet->getCellByColumnAndRow(2, $row)->getValue() != null && $worksheet->getCellByColumnAndRow(3, $row)->getValue() != null) {
                                DB::table('etudiant')->upsert(
                                    [
                                        'CNE' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                                        'nom' => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
                                        'prenom' => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
                                    ],
                                    [
                                        'CNE'
                                    ],
                                    [
                                        'nom', 'prenom'
                                    ]
                                );
                                /////////////

                                DB::table('inscription')->insert([
                                    'CNE' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                                    'id_filiere' => $request->id_fi,
                                    'année_universitaire' => $date,
                                    'niveau' => substr($worksheet->getCellByColumnAndRow(5, $row)->getValue(), 0, 1),
                                ]);
                                //////

                                $k = 0;
                                for ($col = 9; $col <= $highestColumnIndex; ++$col) {
                                    if ($col == (9 + 2 * $k)) {
                                        $value = $worksheet->getCellByColumnAndRow($col, 9)->getValue();
                                        $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                        $color = $cell->getStyle()->getFill()->getStartColor()->getRGB();
                                        if (trim($value) != "Moyenne du Semestre" && ($color == "FF0000"  || $color == "92D050")) {
                                            $id_element1 = DB::select('SELECT e.id_element FROM elements e , module_ds_semestre ms, module m WHERE
                                        ms.id_semestre IN (7,8,9,10) AND m.id_module=e.id_module AND
                                        ms.id_module=e.id_module AND ms.id_filiere = ' . $request->id_fi . '
                                        AND m.nom_module LIKE "%' . $value . '"');
                                            foreach ($id_element1 as $id_e1) {
                                                DB::table('note')->insert(
                                                    [
                                                        'CNE' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                                                        'id_element' => $id_e1->id_element,
                                                        'année' => $date,

                                                    ]
                                                );
                                            }
                                        }
                                        $k++;
                                    }
                                }
                            }
                        }
                        ///////////////////////

                    }
                    return response()->json(['success' => true, 'message' => 'success']);
                }

                if ($request->id_cy == 4) {
                    if ($lenghtt != 3) {
                        return response()->json(['success' => false, 'message' => 'Importer les trois Fichiers']);
                    }
                    $file = $request->file('file');
                    foreach ($file as $file1) {

                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                        $spreadsheet = $reader->load($file1);
                        $worksheet = $spreadsheet->getActiveSheet();
                        $titel1 = $worksheet->getCellByColumnAndRow(1, 6)->getValue();
                        $hrow1  = $worksheet->getHighestRow(); // e.g. 10
                        $highestColumn1 = $worksheet->getHighestColumn(); // e.g 'F'
                        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn1); // e.g. 5
                        ////////descricription colomn
                        if ($worksheet->getCellByColumnAndRow(2, $hrow1)->getValue() == "Module Aquis pour l'année en cours")
                            $hrow1 -= 4;
                        ////////
                        $niiv = 3;
                        if (str_contains(trim($file1->getClientOriginalName()), "1ème")) {
                            $niiv = 1;
                        } else if (str_contains(trim($file1->getClientOriginalName()), "2ème")) {
                            $niiv = 2;
                        }
                        for ($row = 11; $row <= $hrow1; ++$row) {
                            if ($worksheet->getCellByColumnAndRow(1, $row)->getValue() != null && $worksheet->getCellByColumnAndRow(2, $row)->getValue() != null && $worksheet->getCellByColumnAndRow(3, $row)->getValue() != null) {
                                DB::table('etudiant')->upsert(
                                    [
                                        'CNE' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                                        'nom' => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
                                        'prenom' => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
                                    ],
                                    [
                                        'CNE'
                                    ],
                                    [
                                        'nom', 'prenom'
                                    ]
                                );
                                /////////////

                                DB::table('inscription')->insert([
                                    'CNE' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                                    'id_filiere' => $request->id_fi,
                                    'année_universitaire' => $date,
                                    'niveau' => substr($worksheet->getCellByColumnAndRow(5, $row)->getValue(), 0, 1),
                                ]);
                                //////

                                $k = 0;
                                for ($col = 8; $col <= $highestColumnIndex; ++$col) {
                                    if ($col == (8 + 2 * $k)) {
                                        $value = $worksheet->getCellByColumnAndRow($col, 9)->getValue();
                                        $cell = $worksheet->getCellByColumnAndRow($col, $row);
                                        $color = $cell->getStyle()->getFill()->getStartColor()->getRGB();
                                        if (trim($value) != "Moyenne du Semestre" && ($color == "FF0000"  || $color == "92D050")) {
                                            $id_element1 = DB::select('SELECT e.id_element FROM elements e , module_ds_semestre ms, module m WHERE
                                        ms.id_semestre IN (7,8,9,10,11,12) AND m.id_module=e.id_module AND
                                        ms.id_module=e.id_module AND ms.id_filiere = ' . $request->id_fi . '
                                        AND m.nom_module LIKE "%' . $value . '"');
                                            foreach ($id_element1 as $id_e1) {
                                                DB::table('note')->insert(
                                                    [
                                                        'CNE' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                                                        'id_element' => $id_e1->id_element,
                                                        'année' => $date,
                                                    ]
                                                );
                                            }
                                        }
                                        $k++;
                                    }
                                }
                            }
                        }
                        ///////////////////////
                    }
                    return response()->json(['success' => true, 'message' => 'success']);
                }

                //////////end else lt7t
            }
        }
        return response()->json(['success' => false, 'message' => 'Importer un seul Fichier ( ou deux fichier Pour TC) ']);
    }
    public function getEtudiant(Request $request)
    {
        $request->validate([
            'id_fi' => ['required'],
            'id_se' => ['required'],
            'anne' => ['required'],
        ]);

        $etudiant = DB::select(
            'SELECT et.CNE, et.nom,et.prenom, COUNT(DISTINCT ms.id_module) AS nb_module FROM etudiant et , inscription i,module_ds_semestre ms WHERE et.CNE=i.CNE AND i.id_filiere = ms.id_filiere AND ms.id_module IN ( SELECT DISTINCT e.id_module FROM elements e, note n WHERE n.id_element= e.id_element AND n.CNE=et.CNE ) AND ms.id_filiere = ' . $request->id_fi . ' AND ms.id_semestre = ' . $request->id_se . ' AND RIGHT(i.section,1) LIKE "%' . $request->id_sec . '%" AND i.année_universitaire LIKE "%' . $request->anne . '%"
            AND ( et.CNE LIKE "%' . $request->coco . '%" OR
            CONCAT(et.nom," ",et.prenom) LIKE "%' . $request->coco . '%" OR CONCAT(et.prenom," ",et.nom) LIKE "%' . $request->coco . '%" )  GROUP BY et.CNE, et.nom,et.prenom order by nb_module DESC,et.nom ASC'
        );
        if ($request->id_se <= 2) {
            $etudiant = DB::select(
                'SELECT et.CNE, et.nom,et.prenom, COUNT(DISTINCT ms.id_module) AS nb_module FROM etudiant et , inscription i,module_ds_semestre ms WHERE et.CNE=i.CNE AND i.id_filiere = ms.id_filiere AND ms.id_module IN ( SELECT DISTINCT e.id_module FROM elements e, note n WHERE n.id_element= e.id_element AND n.CNE=et.CNE ) AND ms.id_filiere = ' . $request->id_fi . ' AND ms.id_semestre = ' . $request->id_se . ' AND LEFT(i.section,1) LIKE "%' . $request->id_sec . '%" AND i.année_universitaire LIKE "%' . $request->anne . '%"
                AND ( et.CNE LIKE "%' . $request->coco . '%" OR
                CONCAT(et.nom," ",et.prenom) LIKE "%' . $request->coco . '%" OR CONCAT(et.prenom," ",et.nom) LIKE "%' . $request->coco . '%" )  GROUP BY et.CNE, et.nom,et.prenom order by nb_module DESC,et.nom ASC'
            );
        }
        $output = "";
        $count = count($etudiant);
        if ($count == 0) {
            $output .= '
            <tr>
            <td></td>
            <td><h5 class="text-center mt-4">No result found</h5></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            </tr>
            ';
        } else {
            foreach ($etudiant as $element) {

                $output .= '<tr>
                <td class="align-middle col-1"><div class="form-check px-5"><input class="form-check-input d-flex flex-column check" type="checkbox" value="" id="check" data-id="' . $element->CNE . '"></div></td>
                <td><p class="mt-2">' . $element->CNE . '</p></td>
                <td><p class="mt-2">' . $element->nom . '</p></td>
                <td><p class="mt-2">' . $element->prenom . '</p></td>
                <td class="text-center"><p class="mt-2">' . $element->nb_module . '</p></td>
                <td class="align-middle"><button class="btn btn-link text-secondary mb-0 ha-view" id="view-' . $element->CNE . '"  data-bs-toggle="modal" data-bs-target="#exampleModalview"><i class="fas fa-eye" style="color:#fd7e14"></i></button>
                <button class="btn btn-link text-secondary mb-0 ha-edit" data-bs-toggle="modal" id="edit-' . $element->CNE . '" data-bs-target="#exampleModalMessage"><i class="far fa-edit text-info"></i></button></td>
                </tr>';
            }
        }
        echo $output;
    }

    public function getInfoEtudiant(Request $request)
    {
        $module = DB::select('SELECT DISTINCT m.nom_module FROM module m,elements e,note n,module_ds_semestre ms WHERE e.id_module = m.id_module AND e.id_element = n.id_element AND n.CNE LIKE "' . $request->CNE . '" AND n.année LIKE "%' . $request->anne . '%" AND m.id_module = ms.id_module AND ms.id_semestre=' . $request->id_se);
        $output = "";
        foreach ($module as $keo) {
            $output .= '<tr>
            <td><div class="my-auto"><h6 class="mb-0 text-xs">' . $keo->nom_module . '</h6></div></td>
            <td><p class="text-xs font-weight-bold mb-0"><i class="fas fa-check"></i></p> </td>
            </tr>';
        }
        echo $output;
    }
    public function SupprimerEtudiant(Request $request)
    {
        DB::table('note')->whereIn('CNE', explode(",", $request->id))->delete();
        DB::table('inscription')->whereIn('CNE', explode(",", $request->id))->delete();
        DB::table('etudiant')->whereIn('CNE', explode(",", $request->id))->delete();
        return response()->json(['success' => "Etudiant Deleted successfully."]);
    }
    public function exportEtudients(Request $request)
    {

        /*********styling****** */
        $tableHead_modules = [
            'font' => [
                'color' => ['rgb' => '000000'],
                'bold' => true,
                'size' => 11
            ],
            'alignment' => [
                'textRotation' => 90,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'cccccc']
            ],
        ];

        $tableHead = [
            'font' => [
                'color' => ['rgb' => '000000'],
                'bold' => true,
                'size' => 11
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'alignment' => [

                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'cccccc']
            ],
        ];
        //even row
        $evenRow = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'f8f9fa']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
        //odd row
        $oddRow = [
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'ffffff']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ]
        ];
        /*********styling****** */


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        /***heading****** */
        //set default font
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        //heading

        $modulesname_req = 'SELECT DISTINCT m.nom_module FROM module m,module_ds_semestre ms WHERE
        ms.id_module = m.id_module AND ms.id_filiere =' . $request->id_fi . ' AND ms.id_semestre=' . $request->id_se . '';
        $nom_modules = DB::select($modulesname_req);
        $c = count($nom_modules) + 3;
        $col_moy = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($c);
        //merge heading
        $sheet->mergeCells("A1:" . $col_moy . "1");
        // set font style
        $sheet->getStyle('A1')->getFont()->setSize(20);
        // set cell alignment
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


        /***heading****** */
        // $modules_req_etudient = DB::select('SELECT m.nom_module FROM module m,elements e,note n,module_ds_semestre ms WHERE e.id_module = m.id_module AND e.id_element = n.id_element AND n.CNE LIKE "'.$request->CNE.'" AND n.année LIKE "%'.$request->anne.'%" AND m.id_module = ms.id_module AND ms.id_semestre='.$request->id_se);



        //header text
        $i = 4;
        foreach ($nom_modules as $nom_module) {
            $value = $sheet->getCellByColumnAndRow($i, 2)->setValue($nom_module->nom_module);
            $i++;
        }
        $sheet->setCellValue('A2', "CNE")->setCellValue('B2', "Nom")->setCellValue('C2', "Prenom");
        //set font style and background color
        $spreadsheet->getActiveSheet()->getStyle('A2:' . $col_moy . '2')->applyFromArray($tableHead);
        $spreadsheet->getActiveSheet()->getStyle('D2:' . $col_moy . '2')->applyFromArray($tableHead_modules);
        /******looping data */
        $row = 3;
        //$student_req = 'SELECT DISTINCT e.CNE,e.nom,e.prenom FROM etudiant e,inscription i ,module_ds_semestre ms WHERE
        //e.CNE=i.CNE AND i.id_filiere = ms.id_filiere AND ms.id_semestre='.$request->id_se.'
        //AND i.section LIKE "%'.$request->id_sec.'%" AND i.année_universitaire="'.$request->anne.'"';
        //$req1 = 'SELECT et.CNE, et.nom,et.prenom, COUNT(ms.id_module) AS nb_module FROM etudiant et , inscription i,module_ds_semestre ms WHERE et.CNE=i.CNE AND i.id_filiere = ms.id_filiere AND ms.id_module IN ( SELECT e.id_module FROM elements e, note n WHERE n.id_element= e.id_element AND n.CNE=et.CNE ) AND ms.id_filiere = ' . $request->id_fi . ' AND ms.id_semestre = ' . $request->id_se . ' AND i.section LIKE "%' . $request->id_sec . '%" AND i.année_universitaire LIKE "%' . $request->anne . '%"  GROUP BY et.CNE order by nb_module DESC,et.nom ASC';


        if ($request->id_se <= 2) {
            $req1 =
                'SELECT et.CNE, et.nom,et.prenom, COUNT(DISTINCT ms.id_module) AS nb_module FROM etudiant et , inscription i,module_ds_semestre ms WHERE et.CNE=i.CNE AND i.id_filiere = ms.id_filiere AND ms.id_module IN ( SELECT DISTINCT e.id_module FROM elements e, note n WHERE n.id_element= e.id_element AND n.CNE=et.CNE ) AND ms.id_filiere = ' . $request->id_fi . ' AND ms.id_semestre = ' . $request->id_se . ' AND LEFT(i.section,1) LIKE "%' . $request->id_sec . '%" AND i.année_universitaire LIKE "%' . $request->anne . '%" GROUP BY et.CNE, et.nom,et.prenom order by nb_module DESC,et.nom ASC';
        } else {
            $req1 =
                'SELECT et.CNE, et.nom,et.prenom, COUNT(DISTINCT ms.id_module) AS nb_module FROM etudiant et , inscription i,module_ds_semestre ms WHERE et.CNE=i.CNE AND i.id_filiere = ms.id_filiere AND ms.id_module IN ( SELECT DISTINCT e.id_module FROM elements e, note n WHERE n.id_element= e.id_element AND n.CNE=et.CNE ) AND ms.id_filiere = ' . $request->id_fi . ' AND ms.id_semestre = ' . $request->id_se . ' AND RIGHT(i.section,1) LIKE "%' . $request->id_sec . '%" AND i.année_universitaire LIKE "%' . $request->anne . '%" GROUP BY et.CNE, et.nom,et.prenom order by nb_module DESC,et.nom ASC';
        }


        $studentData = DB::select($req1);
        foreach ($studentData as $student) {


            /////////////////////
            $req = 'SELECT
            IF(m.nom_module IN
            (SELECT DISTINCT m.nom_module FROM module m,elements e,note n,module_ds_semestre ms  WHERE
            e.id_module = m.id_module AND e.id_element = n.id_element AND n.CNE = "' . $student->CNE . '"
            AND n.année ="' . $request->anne . '" AND m.id_module = ms.id_module
            AND ms.id_semestre="' . $request->id_se . '"),"X"," ")  as "check"
            FROM module m,module_ds_semestre ms WHERE ms.id_module = m.id_module AND
            ms.id_filiere =' . $request->id_fi . ' AND ms.id_semestre=' . $request->id_se . '';
            $modules_check = DB::select($req);
            ////////////////////

            $sheet->setCellValue('A' . $row, $student->CNE)
                ->setCellValue('B' . $row, $student->nom)
                ->setCellValue('C' . $row, $student->prenom);
            //////fitching modules checked
            $i = 4;
            foreach ($modules_check as $module_check) {
                $sheet->getCellByColumnAndRow($i, $row)->setValue($module_check->check);
                $i++;
            }


            //set row style
            if ($row % 2 == 0) {
                //even row
                $sheet->getStyle('A' . $row . ':' . $col_moy . '' . $row)->applyFromArray($evenRow);
            } else {
                //odd row
                $sheet->getStyle('A' . $row . ':' . $col_moy . '' . $row)->applyFromArray($oddRow);
            }
            //increment row
            $row++;
        }

        $fileName = 'Etudiants.xlsx';

        $response = response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . urlencode($fileName) . '"');
        $response->send();
    }
    public function fetchInfoEtudiant(Request $request)
    {
        $etud = DB::select('SELECT CNE, nom, prenom FROM etudiant WHERE CNE="' . $request->CNE . '"');
        $CNE = "";
        $nom = "";
        $prenom = "";
        foreach ($etud as $ee) {
            $CNE = $ee->CNE;
            $nom = $ee->nom;
            $prenom = $ee->prenom;
        }

        $modulesname_req = 'SELECT DISTINCT m.nom_module , m.id_module FROM module m,module_ds_semestre ms WHERE
        ms.id_module = m.id_module AND ms.id_filiere =' . $request->id_fi . ' AND ms.id_semestre=' . $request->id_se;
        $nom_modules_ts = DB::select($modulesname_req);
        $module = DB::select('SELECT m.nom_module  FROM module m,elements e,note n,module_ds_semestre ms WHERE e.id_module = m.id_module AND e.id_element = n.id_element AND n.CNE LIKE "' . $request->CNE . '" AND n.année LIKE "%' . $request->anne . '%" AND m.id_module = ms.id_module AND ms.id_semestre=' . $request->id_se);
        $output = "";
        $mods = [];
        foreach ($module as $mod) {
            array_push($mods, $mod->nom_module);
        }
        foreach ($nom_modules_ts as $keo) {

            $checked = (in_array($keo->nom_module, $mods)) ? "checked" : "";
            $output .= '<tr>
            <td><div class="my-auto"><h6 class="mb-0 text-xs" >' . $keo->nom_module . '</h6></div></td>
            <td> <input class="checkcheck" data-id="' . $keo->id_module . '" type="checkbox" ' . $checked . ' style=" width: 17px; height: 17px;"> </td>
            </tr>';
        }
        return response()->json(['CNE' => $CNE, 'nom' => $nom, 'prenom' => $prenom, 'output' => $output]);
    }
}
