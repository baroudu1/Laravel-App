<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

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

class ControllerGenerePV extends Controller
{
    public function show($menu = 'Générer PV')
    {
        $CIN = Auth::user()->CIN;
        $nom = Auth::user()->nom;
        $prenom = Auth::user()->prenom;
        $anne_req = 'SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
        $annee = DB::select($anne_req);
        $anneee = $annee[0]->année_universitaire ?? "";
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
        $cycle = DB::select('SELECT * FROM cycle');
        $anne = DB::select('SELECT DISTINCT année_universitaire as annee FROM inscription');

        $fill = DB::select('SELECT nom_filiere FROM filière WHERE id_filiere=' . $poste . '');
        $fill = $fill[0]->nom_filiere ?? '';
        $name = $menu;
        $element = DB::select('SELECT DISTINCT e.id_element , e.nom_element FROM enseinganant_de_module em,elements e WHERE e.id_element=em.id_element AND em.id_enseignant="' . $CIN . '" AND em.année="' . $anneee . '"');

        view('inc.nav');
        return view('generePV', compact('name', 'nom', 'prenom',  'cycle', 'anne', 'anneee', 'poste', 'fill', 'element'));
    }
    public function PV_Semestre(Request $request)
    {

        $request->validate([
            'id_se' => ['required'],
            'id_fi' => ['required'],
            'anne' => ['required'],
        ]);
        /****styling*** */
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
                'startColor' => ['rgb' => 'B7DEE8']
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
                'startColor' => ['rgb' => 'B7DEE8']
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
        /****styling*** */


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        /**heading*** */
        //set default font
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        //heading



        /**heading*** */
        // $modules_req_etudient = DB::select('SELECT m.nom_module FROM module m,elements e,note n,module_ds_semestre ms WHERE e.id_module = m.id_module AND e.id_element = n.id_element AND n.CNE LIKE "'.$request->CNE.'" AND n.année LIKE "%'.$request->anne.'%" AND m.id_module = ms.id_module AND ms.id_semestre='.$request->id_se);

        $modulesname_req = 'SELECT DISTINCT m.nom_module,m.id_module FROM module m,module_ds_semestre ms WHERE
        ms.id_module = m.id_module AND ms.id_filiere =' . $request->id_fi . ' AND ms.id_semestre=' . $request->id_se . '';
        $nom_modules = DB::select($modulesname_req);
        $blooo_req = DB::select('SELECT em.blocker FROM enseinganant_de_module em,module_ds_semestre ms
        WHERE ms.id_filiere= em.id_filiere AND ms.id_semestre=' . $request->id_se . ' AND em.année = "' . $request->anne . '"');
        $blooo = $blooo_req[0]->blocker ?? '';
        //header text
        $i = 4;
        foreach ($nom_modules as $nom_module) {
            $count_req = 'SELECT id_element AS nb_element FROM elements WHERE id_module=' . $nom_module->id_module . '';
            $nb_element = DB::select($count_req);
            $nb_element = count($nb_element);
            $value = $sheet->getCellByColumnAndRow($i, 4)->setValue($nom_module->nom_module);
            $value = $sheet->getCellByColumnAndRow(++$i, 4)->setValue("Resultat");
            if ($blooo != 6) {
                if ($nb_element > 1) $sheet->getCellByColumnAndRow(++$i, 4)->setValue("Objet de rattrapage");
            }
            $i++;
        }
        //$columString=$sheet->getHighestColumn();
        $columString = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i - 1);
        $col_moy = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
        $col_reslt = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1);
        $sheet->setCellValue('A4', "CNE")->setCellValue('B4', "Nom")->setCellValue('C4', "Prenom")
            ->setCellValue($col_moy . '4', "Moyenne de semestre")->setCellValue($col_reslt . '4', "Resultat de semestre");
        //set font style and background color
        if ($request->id_se == 6) {
            $spreadsheet->getActiveSheet()->getStyle('A4:M4')->applyFromArray($tableHead);
        } else {
            //$spreadsheet->getActiveSheet()->getStyle('A4:P4')->applyFromArray($tableHead);
            $spreadsheet->getActiveSheet()->getStyle('A4:'. $col_reslt .'4')->applyFromArray($tableHead);
        }

        $spreadsheet->getActiveSheet()->getStyle('D4:' . $col_reslt . '4')->applyFromArray($tableHead_modules);
        /****looping data */
        $row = 5;
        $req1 = 'SELECT et.CNE, et.nom,et.prenom, COUNT(ms.id_module) AS nb_module FROM etudiant et , inscription i,module_ds_semestre ms WHERE et.CNE=i.CNE AND i.id_filiere = ms.id_filiere AND ms.id_module IN ( SELECT e.id_module FROM elements e, note n WHERE n.id_element= e.id_element AND n.CNE=et.CNE ) AND ms.id_filiere = ' . $request->id_fi . ' AND ms.id_semestre = ' . $request->id_se . ' AND i.section LIKE "%' . $request->id_sec . '%" AND i.année_universitaire LIKE "%' . $request->anne . '%" GROUP BY et.CNE,et.nom,et.prenom order by nb_module DESC,et.nom ASC';

        $studentData = DB::select($req1);
        ///////req seuil devalidation ////////
        $seuil_req = 'SELECT seuil_v,id_cycle FROM cycle WHERE id_cycle IN (SELECT id_cycle FROM filière  WHERE id_filiere=' . $request->id_fi . ')';
        $seuil_data = DB::select($seuil_req);
        $seuil = $seuil_data[0]->seuil_v;
        $id_cycle = $seuil_data[0]->id_cycle;
        ///////req seuil devalidation ////////
        foreach ($studentData as $student) {


            /////////////////////module studied in this semester
            $req = 'SELECT m.id_module,m.nom_module,
            IF(m.nom_module IN
            (SELECT DISTINCT m.nom_module FROM module m,elements e,note n,module_ds_semestre ms  WHERE
            e.id_module = m.id_module AND e.id_element = n.id_element AND n.CNE = "' . $student->CNE . '"
            AND n.année ="' . $request->anne . '" AND m.id_module = ms.id_module
            AND ms.id_semestre="' . $request->id_se . '"),"X",0)  as "check"
            FROM module m,module_ds_semestre ms WHERE ms.id_module = m.id_module AND
            ms.id_filiere =' . $request->id_fi . ' AND ms.id_semestre=' . $request->id_se . '';
            $modules_check = DB::select($req);
            ////////////////////

            $sheet->setCellValue('A' . $row, $student->CNE)
                ->setCellValue('B' . $row, $student->nom)
                ->setCellValue('C' . $row, $student->prenom);
            //////fetching modules checked
            $i = 4;
            $moy_sem = 0;
            $nb_module_valide = 0;
            $module_nv_lst = 0;
            $module_nv_m = 0;
            $moy_sem_test = 0;
            $nb_module_ds_semestre = 6;
            /////////combien de modules prend le pfe
            $req = 'SELECT id_module FROM module_ds_semestre WHERE id_filiere=' . $request->id_fi . ' AND id_semestre=' . $request->id_se;
            $nb_module_pfe = DB::select($req);
            $nb_module_pfe = count($nb_module_pfe);
            $nb_module_pfe = $nb_module_ds_semestre - $nb_module_pfe + 1;
            /////////combien de modules prend le pfe
            foreach ($modules_check as $module_check) {
                ///////////////
                $count_req = 'SELECT id_element AS nb_element FROM elements WHERE id_module=' . $module_check->id_module . '';
                $nb_element = DB::select($count_req);
                $nb_element = count($nb_element);
                //////////////
                if ($module_check->check == "X") {
                    $note = $this->note_module($student->CNE, $module_check->id_module, $request->anne);

                    $sheet->getCellByColumnAndRow($i, $row)->setValue($note);
                    if ($blooo == 6) {
                        $result = ($note < $seuil ||  $note == "ABS") ? "Non validé" :  "Validé";
                    } else {

                        if ($id_cycle == 1 || $id_cycle == 2)
                            $result = ($note < 5 ||  $note == "ABS") ? "Non validé" : (($note < $seuil) ? "rattrapage" : "Validé");
                        if ($id_cycle == 3)
                            $result = ($note < 7 ||  $note == "ABS") ? "Non validé" : (($note < $seuil) ? "rattrapage" : "Validé");
                        if ($id_cycle == 4)
                            $result = ($note == "ABS") ? "Non validé" : (($note < $seuil) ? "rattrapage" : "Validé");
                    }
                    $sheet->getCellByColumnAndRow(++$i, $row)->setValue($result);
                    //modules with more than 1 element before ratt
                    if ($nb_element > 1) {
                        if ($blooo != 6) {
                            if ($result == "rattrapage" || ($id_cycle == 4 && $result == "Validé") ) {
                                $id_els_req = 'SELECT id_element,nom_element FROM elements WHERE id_module=' . $module_check->id_module;
                                $elements = DB::select($id_els_req);
                                $ratt_obj = "";
                                $ratt = 0;
                                foreach ($elements as $element) {
                                    $note_element = $this->note_element($student->CNE, $element->id_element, $request->anne, $ratt);
                                 /*   if ($note_element < 5) {
                                        $result = "Non validé";
                                        $sheet->getCellByColumnAndRow($i, $row)->setValue($result);
                                        break;
                                    } else */
                                    if ($note_element < $seuil || ($id_cycle == 4 && $note_element < 5)) {
                                        $ratt_obj .= " " . $element->nom_element;
                                        $result == "rattrapage";
                                        $sheet->getCellByColumnAndRow($i, $row)->setValue($result);
                                    }
                                }
                                if ($ratt_obj != "") {
                                    $sheet->getCellByColumnAndRow(++$i, $row)->setValue($ratt_obj);
                                } else {
                                    $i++;
                                }
                            }
                            if ($result == "Validé" || $result == "Non validé")$i++;
                        }
                    }
                    if ($note != "ABS" && $note != " ") {
                        $note = ($module_check->nom_module == "Projet de fin d'études") ? $nb_module_pfe * $note : $note;
                        $moy_sem += $note;
                        $nb_module_valide = ($result == "Validé") ? $nb_module_valide + 1 : $nb_module_valide;
                        if($note < 7) $module_nv_lst = 1;
                        if($note < 8) $module_nv_m = 1;
                    } else $moy_sem_test = 1;
                    $i++;
                } else {
                    if ($nb_element > 1) {
                        $i = $i + 3;
                    } else {
                        $i = $i + 2;
                    }
                }
            }
            $moy_sem /= $nb_module_ds_semestre;
            $sem_result = ($moy_sem < 10) ? "non validé" : "validé";
            $sheet->setCellValue($col_moy . $row, $moy_sem);
            if ($id_cycle == 1 || $id_cycle == 2 || $id_cycle == 4) $sem_result = ($moy_sem >= $seuil && $module_nv_lst == 0) ? "validé" : "non validé";
            if ($id_cycle == 3) $sem_result = ($moy_sem >= $seuil && $module_nv_m == 0 && $nb_module_valide >= 5) ? "validé" : "non validé";



            $sheet->setCellValue($col_reslt . $row, $sem_result);



            //set row style
            if ($row % 2 == 0) {
                //even row
                $sheet->getStyle('A' . $row . ':' . $col_reslt . $row)->applyFromArray($evenRow);
            } else {
                //odd row
                $sheet->getStyle('A' . $row . ':' . $col_reslt . $row)->applyFromArray($oddRow);
            }
            //increment row
            $row++;
        }

        $fileName = 'PV_Semestre.xlsx';
        $response = response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . urlencode($fileName) . '"');
        $response->send();
    }
    public function PV_module(Request $request)
    {
        $request->validate([
            'id_module' => ['required'],
            'annee' => ['required'],
            'section' => ['required'],
        ]);
        ///get the module id from the id sent by request it can be mod_id (if $request->id_mm!=0) or el_id
        $elll = 0;
        if ($request->id_mm == 0) {
            $modd = DB::select('SELECT id_module FROM elements WHERE id_element = ' . $request->id_module . '');
            $elll = $request->id_module;
            $request->id_module = $modd[0]->id_module ?? '';
        }
        /****** style array *************/
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
                'startColor' => ['rgb' => 'B7DEE8']
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


        /*************styling* ************/

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //set default font
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        //heading

        $count_req = 'SELECT id_element ,nom_element,Co_cntr,Co_tp,Co_mini_project,Co_examen FROM elements WHERE id_module=' . $request->id_module . '';
        $nb_element_res = DB::select($count_req);
        $nb_element = count($nb_element_res);
        $i = 4;
        /////////////////// print heading
        $sheet->setCellValue('A8', "CNE")->setCellValue('B8', "Nom")->setCellValue('C8', "Prenom");

        foreach ($nb_element_res as $element) {
            if ($elll != 0 &&  $element->id_element != $elll) continue;
            $sheet->getCellByColumnAndRow($i, 7)->setValue($element->nom_element);
            $beg_col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);

            $ctr = ($element->Co_cntr != 0);
            $tp = ($element->Co_tp != 0);
            $minip = ($element->Co_mini_project != 0);
            $ex = ($element->Co_examen != 0);

            if ($ctr)  $sheet->getCellByColumnAndRow($i++, 8)->setValue("NCC");
            if ($tp)  $sheet->getCellByColumnAndRow($i++, 8)->setValue("NTP");
            if ($minip)  $sheet->getCellByColumnAndRow($i++, 8)->setValue("NMP");
            if ($ex)  $sheet->getCellByColumnAndRow($i++, 8)->setValue("NCU");
            $sheet->getCellByColumnAndRow($i++, 8)->setValue("NF");

            $end_col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i - 1);

            $sheet->mergeCells($beg_col . '7:' . $end_col . '7');
        }
        ////////////////////
        if ($request->id_mm != 0)
            $sheet->getCellByColumnAndRow($i++, 8)->setValue("NFM");

        $sheet->getCellByColumnAndRow($i, 8)->setValue("Resultat");
        ///////////////// style heading
        $end_col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
        $end_col1 = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i - 2);
        $spreadsheet->getActiveSheet()->getStyle('A8:' . $end_col . '8')->applyFromArray($tableHead);
        $spreadsheet->getActiveSheet()->getStyle('D7:' . $end_col1 . '7')->applyFromArray($tableHead);
        /////////////




        $data_req = 'SELECT DISTINCT et.CNE,et.nom,et.prenom FROM note n,etudiant et,enseinganant_de_module em
                    WHERE et.CNE=n.CNE AND n.id_element=em.id_element  AND  n.année="' . $request->annee . '" AND em.section LIKE "%' . $request->section . '%" AND
                    n.id_element IN (SELECT id_element FROM elements WHERE id_module=' . $request->id_module . ')';
        $etudiants = DB::select($data_req);
        $blooo_req = DB::select(
            'SELECT em.blocker FROM enseinganant_de_module em WHERE
            em.section="' . $request->section . '" AND em.année = "' . $request->annee . '" AND
            em.id_element IN
            (SELECT id_element FROM elements WHERE id_module=' . $request->id_module . ' )'
        );
        $blooo = $blooo_req[0]->blocker ?? '';

        $less_5_test = 0;
        $row = 9;
        foreach ($etudiants as $etudiant) {

            $sheet->setCellValue('A' . $row, $etudiant->CNE)
                ->setCellValue('B' . $row, $etudiant->nom)
                ->setCellValue('C' . $row, $etudiant->prenom);

            $i = 4;
            foreach ($nb_element_res as $element) {
                if ($elll != 0 &&  $element->id_element != $elll) continue;
                $sheet->getCellByColumnAndRow($i, $row)->setValue($element->nom_element);
                $beg_col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);


                ///////////////////
                $req = 'SELECT IF(n.N_cntr is null,0,n.N_cntr) as N_cntr,IF(n. N_tp is null,0,n. N_tp) as N_tp,IF(n.N_mini_project is null,0,n.N_mini_project) as N_mini_project,IF(n.N_examen_nor is null,0,n.N_examen_nor) as N_examen_nor,IF(n.N_examen_ratt is null,0,n.N_examen_ratt) as N_examen_ratt
                            FROM note n
                            WHERE n.CNE="' . $etudiant->CNE . '"  AND n.id_element=' . $element->id_element . '  AND n.année="' . $request->annee . '"';
                $data = DB::select($req);
                $no_cntr = $data[0]->N_cntr;
                $no_tp = $data[0]->N_tp;
                $no_minip =  $data[0]->N_mini_project;
                $no_ex_n = $data[0]->N_examen_nor;
                $no_ex_r = ($data[0]->N_examen_ratt ?? 0);
                if ($no_ex_n == -1 || $no_ex_r == -1) {
                    $no_ex = -1;
                } else {
                    $no_ex = max($no_ex_n, $no_ex_r);
                }

                //////////////////

                $ctr = ($element->Co_cntr != 0);
                $tp = ($element->Co_tp != 0);
                $minip = ($element->Co_mini_project != 0);
                $ex = ($element->Co_examen != 0);
                $ratt = 0;
                if ($ctr)  $sheet->getCellByColumnAndRow($i++, $row)->setValue(($no_cntr == -1) ? 'ABS' : $no_cntr);
                if ($tp)  $sheet->getCellByColumnAndRow($i++, $row)->setValue(($no_tp == -1) ? 'ABS' : $no_tp);
                if ($minip)  $sheet->getCellByColumnAndRow($i++, $row)->setValue(($no_minip == -1) ? 'ABS' : $no_minip);
                if ($ex)  $sheet->getCellByColumnAndRow($i++, $row)->setValue(($no_ex == -1) ? 'ABS' : $no_ex);
                //dd($this->note_element($etudiant->CNE, $element->id_element, $request->annee, $ratt));
                $sheet->getCellByColumnAndRow($i++, $row)->setValue(($this->note_element($etudiant->CNE, $element->id_element, $request->annee, $ratt) == -1) ? "ABS" : $this->note_element($etudiant->CNE, $element->id_element, $request->annee, $ratt));
                $note_element = $this->note_element($etudiant->CNE, $element->id_element, $request->annee, $ratt);
                if ($note_element < 5) $less_5_test = 1;
            }
            ///////test if before ratt or after ratt


            ///////req seuil devalidation ////////
            $id_filiere_req = 'SELECT id_filiere FROM module_ds_semestre WHERE id_module=' . $request->id_module;
            $id_filiere = DB::select($id_filiere_req);
            $id_filiere = $id_filiere[0]->id_filiere;
            $seuil_req = 'SELECT seuil_v,id_cycle FROM cycle WHERE id_cycle IN (SELECT id_cycle FROM filière  WHERE id_filiere=' . $id_filiere . ')';
            $seuil1 = DB::select($seuil_req);

            $seuil = $seuil1[0]->seuil_v;
            $id_cycle = $seuil1[0]->id_cycle;
            ///////req seuil devalidation ////////

            $module_nf = $this->note_module($etudiant->CNE, $request->id_module, $request->annee);

            $result = "";
            if ($blooo == 6) {
                $result = ($module_nf < $seuil ||  $module_nf == "ABS") ? "Non validé" :  "Validé";
            } else {
                if ($id_cycle == 1 || $id_cycle == 2)
                    $result = ($module_nf < 5 ||  $module_nf == "ABS") ? "Non validé" : (($module_nf < $seuil) ? "rattrapage" : "Validé");
                if ($id_cycle == 3)
                    $result = ($module_nf < 7 ||  $module_nf == "ABS") ? "Non validé" : (($module_nf < $seuil) ? "rattrapage" : "Validé");
                if ($id_cycle == 4)
                    $result = ($module_nf == "ABS" || $less_5_test == 1) ? "Non validé" : (($module_nf < $seuil) ? "rattrapage" : "Validé");
            }
            if ($request->id_mm != 0)
                $sheet->getCellByColumnAndRow($i++, $row)->setValue($module_nf);

            $sheet->getCellByColumnAndRow($i, $row)->setValue($result);
            ///////test if before ratt or after ratt
            $end_col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);

            //set row style
            if ($row % 2 == 0) {
                //even row
                $sheet->getStyle('A' . $row . ':' . $end_col . $row)->applyFromArray($evenRow);
            } else {
                //odd row
                $sheet->getStyle('A' . $row . ':' . $end_col . $row)->applyFromArray($oddRow);
            }
            //increment row
            $row++;
        }


        $fileName = 'PV_Module.xlsx';


        $response = response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . urlencode($fileName) . '"');
        $response->send();
    }

    /********somme calc function
    public function note_element($CNE, $id_element, $annee)
    {
        $req1 = 'SELECT Co_cntr,Co_tp ,Co_mini_project,Co_examen FROM elements WHERE id_element =' . $id_element;
        $co = DB::select($req1);
        $ctr = ($co[0]->Co_cntr != 0);
        $tp = ($co[0]->Co_tp != 0);
        $minip = ($co[0]->Co_mini_project != 0);
        $ex = ($co[0]->Co_examen != 0);

        $co_ctr = $co[0]->Co_cntr / 100;
        $co_tp = $co[0]->Co_tp / 100;
        $co_minip = $co[0]->Co_mini_project / 100;
        $co_ex = $co[0]->Co_examen / 100;

        $note_elem = '';
        $req = 'SELECT IF(n.N_cntr is null,0,n.N_cntr) as N_cntr,IF(n. N_tp is null,0,n. N_tp) as N_tp,IF(n.N_mini_project is null,0,n.N_mini_project) as N_mini_project,IF(n.N_examen_nor is null,0,n.N_examen_nor) as N_examen_nor,IF(n.N_examen_ratt is null,0,n.N_examen_ratt) as N_examen_ratt
        FROM note n
        WHERE n.CNE="' . $CNE . '"  AND n.id_element=' . $id_element . '  AND n.année="' . $annee . '"';
        $data = DB::select($req);
        $no_cntr = $data[0]->N_cntr;
        $no_tp = $data[0]->N_tp;
        $no_minip =  $data[0]->N_mini_project;
        $no_ex_n = $data[0]->N_examen_nor;
        $no_ex_r = ($data[0]->N_examen_ratt ?? 0);
        $no_ex = max($no_ex_n, $no_ex_r);
        if ($no_cntr < 0 || $no_tp < 0 || $no_minip < 0 || $no_ex_n < 0 || $no_ex_r < 0) {
            $note_elem = -1;
            return $note_elem;
        } else {
            $note_elem = $no_cntr * $co_ctr + $no_tp * $co_tp + $no_minip * $co_minip + $no_ex * $co_ex;
        }

        return $note_elem;
    }

    public function note_module($CNE, $id_module, $annee)
    {

        $req = 'SELECT id_element,Co_element FROM elements WHERE id_module =' . $id_module;

        $elements = DB::select($req);
        $module_note = 0;
        foreach ($elements as $element) {

            $co_element = $element->Co_element / 100;
            $not_ele = $this->note_element($CNE, $element->id_element, $annee);
            if ($not_ele == -1) {
                $module_note = 'ABS';
                return $module_note;
            }
            $module_note += $not_ele * $co_element;
        }
        return $module_note;
    }


     */
    public function get_Semestre(Request $request)
    {
        if ($request->anne == -1) {
            $anne_req = 'SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
            $annee = DB::select($anne_req);
            $anne = $annee[0]->année_universitaire ?? "";
            $request->anne = $anne;
        }
        $fil_mods = '(SELECT id_module FROM module_ds_semestre WHERE id_filiere=' . $request->id_fi . ' AND id_semestre =s.id_semestre)';
        $fil_mods_eles = '( SELECT id_element FROM elements WHERE id_module IN ' . $fil_mods . ' )';
        $sem_block = '(SELECT DISTINCT  blocker FROM enseinganant_de_module  WHERE année = "' . $request->anne . '" AND id_element IN ' . $fil_mods_eles . ' )';

        $sem_req = 'SELECT DISTINCT s.id_semestre,s.nom_semestre FROM semetre s, module_ds_semestre ms WHERE
                    ( 3 = all ' . $sem_block . ' OR  6 = all ' . $sem_block . ') AND ms.id_semestre=  s.id_semestre AND ms.id_filiere=' . $request->id_fi;
        $rst = DB::select($sem_req);

        return response()->json($rst);
    }

    /****somme calc function
     * $rst = DB::select('SELECT s.id_semestre,s.nom_semestre FROM semetre s WHERE
        (SELECT DISTINCT  blocker FROM enseinganant_de_module  WHERE année = "' . $request->anne . '" AND id_element IN (SELECT id_element FROM elements WHERE id_module IN (SELECT id_module FROM module_ds_semestre WHERE
        id_filiere=' . $request->id_fi . ' AND id_semestre =s.id_semestre))) IN (3,6)');
     */
    /*
    public function note_element($CNE, $id_element, $annee)
    {
        $req1 = 'SELECT Co_cntr,Co_tp ,Co_mini_project,Co_examen FROM elements WHERE id_element =' . $id_element;
        $co = DB::select($req1);


        $co_ctr = $co[0]->Co_cntr / 100;
        $co_tp = $co[0]->Co_tp / 100;
        $co_minip = $co[0]->Co_mini_project / 100;
        $co_ex = $co[0]->Co_examen / 100;

        $note_elem = '';
        $req = 'SELECT n.N_cntr,n.N_tp,n.N_mini_project,n.N_examen_nor,n.N_examen_ratt
        FROM note n
        WHERE n.CNE="' . $CNE . '"  AND n.id_element=' . $id_element . '  AND n.année="' . $annee . '"';
        $data = DB::select($req);
        //////
        $no_cntr = $data[0]->N_cntr ?? "";
        /////////
        $no_tp = $data[0]->N_tp ?? "";        //////
        $no_minip =  $data[0]->N_mini_project ?? "";
        ////////
        $no_ex_n = $data[0]->N_examen_nor ?? "";
        $no_ex_r = ($data[0]->N_examen_ratt ?? 0);
        $no_ex = max($no_ex_n, $no_ex_r);
        ////////////////////////////////////////////////////////////////////////////////////////////
        if ($no_cntr == "" || $no_tp || $no_minip || $no_ex_n || $no_ex_r == "" || $no_cntr == null || $no_tp || $no_minip || $no_ex_n || $no_ex_r == null) return "";
        ///////////////////////////////////////////////////////////////////////////////////////////////
        if ($no_cntr < 0 || $no_tp < 0 || $no_minip < 0 || $no_ex_n < 0 || $no_ex_r < 0) {
            $note_elem = -1;
            return $note_elem;
        } else {
            $note_elem = $no_cntr * $co_ctr + $no_tp * $co_tp + $no_minip * $co_minip + $no_ex * $co_ex;
        }

        return $note_elem;
    }

    public function note_module($CNE, $id_module, $annee)
    {

        $req = 'SELECT id_element,Co_element FROM elements WHERE id_module =' . $id_module;

        $elements = DB::select($req);
        $module_note = 0;
        foreach ($elements as $element) {

            $co_element = $element->Co_element / 100;
            $not_ele = $this->note_element($CNE, $element->id_element, $annee);
            /////////////
            if ($not_ele == "") return " ";
            ////////////
            if ($not_ele == -1) {
                $module_note = 'ABS';
                return $module_note;
            }
            $module_note += $not_ele * $co_element;
        }
        return $module_note;
    }
    /****somme calc function
     */


    /*********************calc func with seuil */

    public function note_element($CNE, $id_element, $annee, &$ratt)
    {
        $req1 = 'SELECT Co_cntr,Co_tp ,Co_mini_project,Co_examen FROM elements WHERE id_element =' . $id_element;
        $co = DB::select($req1);


        $co_ctr = $co[0]->Co_cntr / 100;
        $co_tp = $co[0]->Co_tp / 100;
        $co_minip = $co[0]->Co_mini_project / 100;
        $co_ex = $co[0]->Co_examen / 100;

        $note_elem = '';
        $req = 'SELECT n.N_cntr,n.N_tp,n.N_mini_project,n.N_examen_nor,n.N_examen_ratt
        FROM note n
        WHERE n.CNE="' . $CNE . '"  AND n.id_element=' . $id_element . '  AND n.année="' . $annee . '"';
        $data = DB::select($req);
        //////
        if (isset($data[0]->N_cntr)) {
            $no_cntr = $data[0]->N_cntr;
        } else {
            $no_cntr =  "";
        }
        if (isset($data[0]->N_tp)) {
            $no_tp = $data[0]->N_tp;
        } else {
            $no_tp = "";
        }
        if (isset($data[0]->N_mini_project)) {
            $no_minip =  $data[0]->N_mini_project;
        } else {
            $no_minip =  "";
        }
        if (isset($data[0]->N_examen_nor)) {
            $no_ex_n = $data[0]->N_examen_nor;
        } else {
            $no_ex_n =  "";
        }
        ////////
        if (isset($data[0]->N_examen_ratt)) $ratt = 1;

        $no_ex_r = ($data[0]->N_examen_ratt ?? 0);

        if ($no_ex_n == -1 || $no_ex_r == -1) {
            $no_ex = -1;
        } else {
            $no_ex = max($no_ex_n, $no_ex_r);
        }
        ////////////////////////////////////////////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////////////////////////////////////////////
        if ($no_cntr < 0 || $no_tp < 0 || $no_minip < 0 || $no_ex_n < 0 || $no_ex_r < 0) {
            $note_elem = -1;
            return $note_elem;
        } else {

            $note_elem = (($no_cntr == "") ?  0 : $no_cntr) * $co_ctr + (($no_tp == "") ?  0 : $no_tp) * $co_tp + (($no_minip == "") ?  0 : $no_minip) * $co_minip + (($no_ex == "") ?  0 : $no_ex) * $co_ex;
        }

        return $note_elem;
    }

    public function note_module($CNE, $id_module, $annee)
    {

        $req = 'SELECT id_element,Co_element FROM elements WHERE id_module =' . $id_module;

        $elements = DB::select($req);
        $module_note = 0;
        /////////////////////////ratt///////////////////////
        $ratt = 0;
        /////////////////////////ratt///////////////////////


        ///////req seuil de validation ////////
        $id_filiere_req = 'SELECT id_filiere FROM module_ds_semestre WHERE id_module=' . $id_module;
        $id_filiere = DB::select($id_filiere_req);
        $id_filiere = $id_filiere[0]->id_filiere;
        $seuil_req = 'SELECT seuil_v,id_cycle FROM cycle WHERE id_cycle IN (SELECT id_cycle FROM filière  WHERE id_filiere=' . $id_filiere . ')';
        $seuil1 = DB::select($seuil_req);
        $seuil = $seuil1[0]->seuil_v;
        $id_cycle = $seuil1[0]->id_cycle;
        ///////req seuil devalidation ////////

        foreach ($elements as $element) {

            $co_element = $element->Co_element / 100;
            $not_ele = $this->note_element($CNE, $element->id_element, $annee, $ratt);
            /////////////
            if ($not_ele === "") return " ";
            ////////////
            if ($not_ele == -1) {
                $module_note = 'ABS';
                return $module_note;
            }
            $module_note += $not_ele * $co_element;
        }
        /////////////////////////ratt///////////////////////
        if ($ratt == 1 && $module_note > $seuil && $id_cycle != 3 && $id_cycle != 4) $module_note = $seuil;
        /////////////////////////ratt///////////////////////

        return $module_note;
    }

    /*********************calc func with seuil */

    public function PV_Filiere(Request $request)
    {
        $request->validate([
            'id_fi' => ['required'],
            'anne' => ['required'],
        ]);
        /*styling** */
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
                'startColor' => ['rgb' => 'B7DEE8']
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
                'startColor' => ['rgb' => 'B7DEE8']
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
        /*styling** */


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        /*heading** */
        //set default font
        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        //heading



        /*heading** */
        // $modules_req_etudient = DB::select('SELECT m.nom_module FROM module m,elements e,note n,module_ds_semestre ms WHERE e.id_module = m.id_module AND e.id_element = n.id_element AND n.CNE LIKE "'.$request->CNE.'" AND n.année LIKE "%'.$request->anne.'%" AND m.id_module = ms.id_module AND ms.id_semestre='.$request->id_se);

        $blooo_req = DB::select('SELECT em.blocker,ms.id_semestre FROM enseinganant_de_module em,module_ds_semestre ms
        WHERE ms.id_filiere= em.id_filiere AND ms.id_filiere=' . $request->id_fi . ' AND em.année = "' . $request->anne . '" ORDER BY ms.id_semestre DESC LIMIT 1');
        $blooo = $blooo_req[0]->blocker ?? '';

        $semestre_req = 'SELECT DISTINCT id_semestre FROM module_ds_semestre WHERE id_filiere =' . $request->id_fi;
        $ids_se = DB::select($semestre_req);
        //header text
        $col_reslt = "";
        $k = 4;

        $i = 0;
        foreach ($ids_se as $id_se) {
            $modulesname_req = 'SELECT DISTINCT m.nom_module,m.id_module FROM module m,module_ds_semestre ms WHERE
            ms.id_module = m.id_module AND ms.id_filiere =' . $request->id_fi . ' AND ms.id_semestre=' . $id_se->id_semestre . '';
            $nom_modules = DB::select($modulesname_req);

            foreach ($nom_modules as $nom_module) {
                $value = $sheet->getCellByColumnAndRow($k++, 4)->setValue($nom_module->nom_module);
                $value = $sheet->getCellByColumnAndRow($k++, 4)->setValue("Resultat");
            }
            $col_moy = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($k++);
            $col_reslt = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($k++);
            $sheet->setCellValue($col_moy . '4', "Moyenne de semestre")->setCellValue($col_reslt . '4', "Resultat de semestre");
        }
        //$columString=$sheet->getHighestColumn();
        //$columString = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($k - 1);

        $sheet->setCellValue('A4', "CNE")->setCellValue('B4', "Nom")->setCellValue('C4', "Prenom");

        //set font style and background color
        $spreadsheet->getActiveSheet()->getStyle('A4:' . $col_reslt . '4')->applyFromArray($tableHead);
        $spreadsheet->getActiveSheet()->getStyle('D4:' . $col_reslt . '4')->applyFromArray($tableHead_modules);
        /**looping data */
        $row = 5;
        $max_niveau_fil = 'SELECT MAX(niveau) AS niveau FROM inscription WHERE id_filiere =' . $request->id_fi;
        $max_niveau = DB::select($max_niveau_fil);
        $max_niveau_fil = $max_niveau[0]->niveau ?? 0;

        $req1 = 'SELECT et.CNE,et.nom,et.prenom FROM etudiant et ,inscription i WHERE et.CNE=i.CNE AND
                i.niveau =' . $max_niveau_fil . ' AND i.année_universitaire ="' . $request->anne . '"
                AND i.id_filiere =' . $request->id_fi;


        $studentData = DB::select($req1);
        ///////req seuil de validation ////////
        $id_filiere = $request->id_fi;
        $seuil_req = 'SELECT seuil_v,id_cycle FROM cycle WHERE id_cycle IN (SELECT id_cycle FROM filière  WHERE id_filiere=' . $id_filiere . ')';
        $seuil1 = DB::select($seuil_req);
        $seuil = $seuil1[0]->seuil_v;
        $id_cycle = $seuil1[0]->id_cycle;
        ///////req seuil devalidation ////////


        foreach ($studentData as $student) {
            $i = 4;

            foreach ($ids_se as $id_se) {
                /////////////////////
                $req = 'SELECT DISTINCT m.id_module,m.nom_module FROM module m,module_ds_semestre ms WHERE m.id_module=ms.id_module AND
                ms.id_filiere=' . $request->id_fi . ' AND ms.id_semestre=' . $id_se->id_semestre;
                $modules = DB::select($req);
                $nb_modules = count($modules);
                ////////////////////

                $sheet->setCellValue('A' . $row, $student->CNE)
                    ->setCellValue('B' . $row, $student->nom)
                    ->setCellValue('C' . $row, $student->prenom);
                //////fitching modules
                $moy_sem = 0;
                $nb_module_valide = 0;
                $module_nv_lst = 0;
                $module_nv_m = 0;
                $moy_sem_test = 0;
                $nb_module_ds_semestre = 6;
                /////////combien de modules prend le pfe
                $req = 'SELECT id_module FROM module_ds_semestre WHERE id_filiere=' . $request->id_fi . ' AND id_semestre=' . $id_se->id_semestre;
                $nb_module_pfe = DB::select($req);
                $nb_module_pfe = count($nb_module_pfe);
                $nb_module_pfe = $nb_module_ds_semestre - $nb_module_pfe + 1;
                /////////combien de modules prend le pfe

                foreach ($modules as $module) {

                    $last_Y_s_mod_req = 'SELECT n.année FROM note n ,elements e WHERE e.id_element=n.id_element AND
                        e.id_module=' . $module->id_module . ' AND n.CNE="' . $student->CNE . '" ORDER BY n.année DESC LIMIT 1';
                    $year = DB::select($last_Y_s_mod_req);
                    /////
                    $year = $year[0]->année ?? "";
                    /////
                    $note = $this->note_module($student->CNE, $module->id_module, $year);
                    $sheet->getCellByColumnAndRow($i++, $row)->setValue($note);
                    $result = ($note < $seuil ||  $note == "ABS") ? "Non validé" :  "Validé";
                    if ($note < 7) $module_nv_lst = 1;
                    if ($note < 8) $module_nv_m = 1;
                    if ($result == "Validé") $nb_module_valide = ($module->nom_module == "Projet de fin d'études") ? ($nb_module_pfe + $nb_module_valide) : ($nb_module_valide + 1);
                    ///////////////////////////
                    if ($note === " ") $result = "";
                    ///////////////////////////

                    $sheet->getCellByColumnAndRow($i++, $row)->setValue($result);

                    ////////////
                    if ($note != "ABS" && $note != " ")
                        $moy_sem += ($module->nom_module == "Projet de fin d'études") ? $note * $nb_module_pfe : $note;
                    ////////////
                }
                $moy_sem /= $nb_module_ds_semestre;
                $sem_result = ($moy_sem < 10) ? "non validé" : "validé";
                $sheet->getCellByColumnAndRow($i++, $row)->setValue($moy_sem);
                if ($id_cycle == 1 || $id_cycle == 2 || $id_cycle == 4) $sem_result = ($moy_sem >= $seuil && $module_nv_lst = 0) ? "validé" : "non validé";
                if ($id_cycle == 3) $sem_result = ($moy_sem >= $seuil && $module_nv_m = 0 && $nb_module_valide >= 5) ? "validé" : "non validé";

                $sheet->getCellByColumnAndRow($i++, $row)->setValue($sem_result);
            }

            //set row style
            if ($row % 2 == 0) {
                //even row
                $sheet->getStyle('A' . $row . ':' . $col_reslt . $row)->applyFromArray($evenRow);
            } else {
                //odd row
                $sheet->getStyle('A' . $row . ':' . $col_reslt . $row)->applyFromArray($oddRow);
            }
            //increment row
            $row++;
        }

        $fileName = 'PV_Filiere.xlsx';
        $response = response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        });
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . urlencode($fileName) . '"');
        $response->send();
    }
    public function is_ratt($CNE, $id_element)
    {
        $req = 'SELECT ms.id_filiere,ms.id_module FROM elements e,module_ds_semestre ms
            WHERE ms.id_module=e.id_module AND e.id_element=' . $id_element;
        $id_fil = DB::select($req);
        $id_module = $id_fil[0]->id_module;
        $id_fil = $id_fil[0]->id_filiere;
        ///////req seuil devalidation ////////
        $seuil_req = 'SELECT seuil_v,id_cycle FROM cycle WHERE id_cycle=(SELECT id_cycle FROM filière  WHERE id_filiere=' . $id_fil . ')';
        $seuil_data = DB::select($seuil_req);
        $seuil = $seuil_data[0]->seuil_v;
        $id_cycle = $seuil_data[0]->id_cycle;
        $x = 0;
        ///////req current  univer  year////////
        $anne_req = 'SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
        $annee = DB::select($anne_req);
        $anne = $annee[0]->année_universitaire ?? "";
        $module_nf = $this->note_module($CNE, $id_module, $anne);
        /////////////////////////
        $req1 = 'SELECT Co_cntr,Co_tp ,Co_mini_project,Co_examen FROM elements WHERE id_element =' . $id_element;
        $co = DB::select($req1);


        $co_ctr = $co[0]->Co_cntr / 100;
        $co_tp = $co[0]->Co_tp / 100;
        $co_minip = $co[0]->Co_mini_project / 100;
        $co_ex = $co[0]->Co_examen / 100;

        //$note_elem = '';
        $req = 'SELECT n.N_cntr,n.N_tp,n.N_mini_project,n.N_examen_nor,n.N_examen_ratt
        FROM note n
        WHERE n.CNE="' . $CNE . '"  AND n.id_element=' . $id_element . '  AND n.année="' . $anne . '"';
        $data = DB::select($req);
        //////
        if (isset($data[0]->N_cntr)) {
            $no_cntr = $data[0]->N_cntr;
        } else {
            $no_cntr =  "";
        }
        if (isset($data[0]->N_tp)) {
            $no_tp = $data[0]->N_tp;
        } else {
            $no_tp = "";
        }
        if (isset($data[0]->N_mini_project)) {
            $no_minip =  $data[0]->N_mini_project;
        } else {
            $no_minip =  "";
        }
        if (isset($data[0]->N_examen_nor)) {
            $no_ex_n = $data[0]->N_examen_nor;
        } else {
            $no_ex_n =  "";
        }
        ////////


        $no_ex = $no_ex_n;
        ////////////////////////////////////////////////////////////////////////////////////////////

        ///////////////////////////////////////////////////////////////////////////////////////////////
        if ($no_cntr < 0 || $no_tp < 0 || $no_minip < 0 || $no_ex_n < 0) {
            $note_element = -1;
        } else {
            $note_element = (($no_cntr == "") ?  0 : $no_cntr) * $co_ctr + (($no_tp == "") ?  0 : $no_tp) * $co_tp + (($no_minip == "") ?  0 : $no_minip) * $co_minip + (($no_ex == "") ?  0 : $no_ex) * $co_ex;
        }
        /////////////////////////
        //$note_element = $this->note_element($CNE, $id_module, $anne, $x);
        $result = '';
        if ($id_cycle == 1 || $id_cycle == 2)
            $result = ($module_nf < 5 ||  $module_nf == "ABS") ? "Non validé" : (($module_nf < $seuil) ? "rattrapage" : "Validé");
        if ($id_cycle == 3)
            $result = ($module_nf < 7 ||  $module_nf == "ABS") ? "Non validé" : (($module_nf < $seuil) ? "rattrapage" : "Validé");
        if ($id_cycle == 4)
            $result = ($module_nf == "ABS" ) ? "Non validé" : (($module_nf < $seuil) ? "rattrapage" : "Validé");
//modules that have more than 1 element
        if ($result == "rattrapage" /*|| $result == "Validé" */|| ($result == "Validé" && $id_cycle == 4)) {
            if ($note_element < 10 && $result == "rattrapage") {
                return (object) ['ana' => true, 'note' => $module_nf,'ratt'=> ($result == "rattrapage")];
            }
             if ($note_element < 5 && $id_cycle == 4) {
                $result == "rattrapage";
                return (object) ['ana' => true, 'note' => $module_nf, 'ratt' => ($result == "rattrapage")];
            }
        }
        return (object) ['ana' => false, 'note' => $module_nf,$result == "rattrapage",'ratt' => ($result == "rattrapage")];
    }
}
