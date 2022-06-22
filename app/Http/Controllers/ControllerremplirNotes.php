<?php

namespace App\Http\Controllers;

use App\Exports\NotesExport;
use App\Imports\NotesImport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Psy\Util\Json;

class ControllerremplirNotes extends Controller
{
    public function show($menu = 'Remplissage Des Notes')
    {
        //$CIN= DB::select('select CIN from users where email=:email', ['email'=>""]);
        $CIN = Auth::user()->CIN;
        $nom = Auth::user()->nom;
        $prenom = Auth::user()->prenom;
        $poste = null;
        $anne_req = 'SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
        $annee = DB::select($anne_req);
        $anne = $annee[0]->année_universitaire ?? "";
        $element = DB::select('SELECT DISTINCT e.id_element , e.nom_element FROM enseinganant_de_module em,elements e WHERE e.id_element=em.id_element AND em.id_enseignant="' . $CIN . '" AND em.année="' . $anne . '"');
        $admin = DB::select('select CIN from admin where CIN=:cin', ['cin' => $CIN]);
        $coordinateur = DB::select('select coordinateur from enseignant where CIN=:cin ', ['cin' => $CIN]);
        foreach ($admin as $keye) {
            $admin = $keye->CIN;
        }
        //dd($admin);
        foreach ($coordinateur as $keeye) {
            $coordinateur = $keeye->coordinateur;
        }
        if ($admin != []) {
            $poste = "-1";
        } else if ($coordinateur != []) {
            $poste = $coordinateur;
        } else {
            $poste = -2;
        }
        $name = $menu;
        view('inc.nav');
        return view('remplirNotes', compact('name', 'nom', 'prenom',  'poste', 'element'));
    }

    public function fetch(Request $request)
    {

        $test = new ControllerGenerePV;
        $anne_req = 'SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
        $annee = DB::select($anne_req);
        $anne = $annee[0]->année_universitaire ?? "";
        $CIN = Auth::user()->CIN;
        $hh = DB::select('SELECT blocker FROM enseinganant_de_module WHERE id_element=' . $request->id_el . ' AND id_enseignant="' . $CIN . '" AND
        section ="' . $request->id_sec . '" AND année ="' . $anne . '"');
        $req1 = 'SELECT Co_cntr,Co_tp ,Co_mini_project,Co_examen FROM elements WHERE id_element =' . $request->id_el;
        $co = DB::select($req1);
        $ctr = ($co[0]->Co_cntr != 0);
        $tp = ($co[0]->Co_tp != 0);
        $minip = ($co[0]->Co_mini_project != 0);
        $ex = ($co[0]->Co_examen != 0);
        $output = '<thead>
        <tr>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">CNE</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nom</th>
            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"> Prenom</th>';
        if ($tp) $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TP&nbsp;&nbsp;&nbsp;</th>';
        if ($ctr) $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">controle</th>';
        if ($minip) $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">' . (($co[0]->Co_mini_project == 100) ? 'PFE' : 'mini project') . '</th>';
        if ($ex) {
            $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">examen nor</th>';
            if ($hh[0]->blocker >= 4) {
                $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">examen rat</th>';
            }
        }
        if ($hh[0]->blocker == 1 || $hh[0]->blocker == 4) {
            $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Note Finale</th>';
        }
        $output .= '</tr></thead><tbody>';

        /* * * * * * * * * * * * * * * * * * * * * * * * */



        $req = 'SELECT n.id,et.CNE,et.nom,et.prenom,';
        if ($ctr) $req .= 'IF(n.N_cntr=-1,"ABS",n.N_cntr) as N_cntr,';
        if ($tp) $req .= 'IF(n.N_tp=-1,"ABS",n.N_tp) as N_tp,';
        if ($minip) $req .= 'IF(n.N_mini_project=-1,"ABS",n.N_mini_project) as N_mini_project ';
        if ($minip && $ex) $req .= ',';
        if ($ex) $req .= 'IF(n.N_examen_nor=-1,"ABS",n.N_examen_nor) as N_examen_nor,IF(n.N_examen_ratt=-1,"ABS",n.N_examen_ratt) as N_examen_ratt';
        $req .= ' FROM inscription i,note n,etudiant et WHERE et.CNE=n.CNE AND i.CNE=et.CNE AND n.id_element=' . $request->id_el . '
            AND i.section LIKE "%' . $request->id_sec . '%" AND n.année="' . $anne . '" AND ( et.CNE LIKE "%' . $request->coco . '%" OR concat(et.nom," ",et.prenom) LIKE "%' . $request->coco . '%" OR  concat(et.prenom," ",et.nom) LIKE "%' . $request->coco . '%") order by et.nom';

        $data = DB::select($req);

        $count = count($data);
        if ($count == 0) {
            $output .= '
            <tr>
            <td><h5 class="text-center mt-4">No result found</h5></td>
            </tr>
            ';
        } else {
            $output .= '<input type="hidden" id="' . $hh[0]->blocker . '" class="ha_access">';
            foreach ($data as $kee) {
                $output .= '<tr>
                        <td>
                            <p>' . $kee->CNE . '</p>
                        </td>
                        <td>
                            <p>' . $kee->nom . '</p>
                        </td>
                        <td>
                            <p>' . $kee->prenom . '</p>
                        </td>';

                if ($hh[0]->blocker == 1 || $hh[0]->blocker == 4) {
                    $output .= '<input type="hidden" class="close_element" id="' . $kee->id . ' ">';
                    if ($tp) $output .= '<td class=" ha-wlwo">
                            <input type="text" id="tp-' . $kee->id . '" ' . (($hh[0]->blocker == 4) ? "disabled" : "") . ' value="' . $kee->N_tp . '" class=" form-control text-sm font-weight-bold mb-0" style="width:60px"/>
                        </td>';
                    if ($ctr) $output .= '<td class="ha-wlwo">
                        <input type="text" id="ctr-' . $kee->id . '" ' . (($hh[0]->blocker == 4) ? "disabled" : "") . ' value="' . $kee->N_cntr . '" class=" form-control text-sm font-weight-bold mb-0" style="width:60px"/>
                    </td>';
                    if ($minip) $output .= '<td class="mx-auto ha-wlwo">
                            <input type="text" id="minip-' . $kee->id . '" ' . (($hh[0]->blocker == 4) ? "disabled" : "") . ' value="' . $kee->N_mini_project . '" class=" form-control text-sm font-weight-bold mb-0" style="width:60px"/>
                        </td>';
                    if ($ex) {
                        $output .= '<td class="ha-wlwo">
                            <input type="text" id="exn-' . $kee->id . '" ' . (($hh[0]->blocker == 4) ? "disabled" : "") . ' value="' . $kee->N_examen_nor . '" class=" form-control text-sm font-weight-bold mb-0" style="width:60px"/>
                        </td>';
                       /* if ($hh[0]->blocker == 4) {
                            $output .= '<td class="' . (($hh[0]->blocker == 4 && $test->is_ratt($kee->CNE, $request->id_el)->ana) ? "ha-dis" : "") . ' ">';
                            if ($hh[0]->blocker == 4 && $test->is_ratt($kee->CNE, $request->id_el)->ana) {
                                $output .= '<input type="text" id="exrat-' . $kee->id . '" value="' . $kee->N_examen_ratt . '" class="form-control text-sm font-weight-bold mb-0" style="width:60px"/>
                            </td>';
                            } else if ($hh[0]->blocker == 4  ) {
                                $output .= '<p id="exrat-' . $kee->id . '" class="text-sm font-weight-bold mb-0">' . $kee->N_examen_ratt . '</p>
                            </td>';
                            }
                        }*/
                        /////
                        if ($hh[0]->blocker == 4) {
                            $output .= '<td class="' . (($test->is_ratt($kee->CNE, $request->id_el)->ana) ? "ha-dis" : "") . ' ">';

                            $output1 = '<input type="text" id="exrat-' . $kee->id . '" value="' . $kee->N_examen_ratt . '" class="form-control text-sm font-weight-bold mb-0" style="width:60px"/>
                            </td>';
                           if ( $kee->N_examen_ratt=="" && !$test->is_ratt($kee->CNE, $request->id_el)->ana) {
                                $output1 = '<p id="exrat-' . $kee->id . '" class="text-sm font-weight-bold mb-0">' . $kee->N_examen_ratt . '</p>
                            </td>';
                            }
                            $output .=$output1;
                        }
                    }
                    $ss = "bg-success";
                    if($test->is_ratt($kee->CNE, $request->id_el)->ana || $test->is_ratt($kee->CNE, $request->id_el)->ratt){
                        $ss = "bg-info";
                    }else {
                        if($test->is_ratt($kee->CNE, $request->id_el)->note < 10){
                            $ss = "bg-danger";
                        }
                    }
                   /* if($test->is_ratt($kee->CNE, $request->id_el)->ana && $hh[0]->blocker < 4 ){
                        $ss = "bg-info";
                    }

                    if(!$test->is_ratt($kee->CNE, $request->id_el)->ana && $test->is_ratt($kee->CNE, $request->id_el)->note < 10 && $hh[0]->blocker >= 4)
                            $ss = "bg-danger";*/

                    $output .= '<td>
                                <p class="text-sm font-weight-bold mb-0 '.$ss.' text-center text-white" style="width:60px">' . ($test->is_ratt($kee->CNE, $request->id_el)->note) . '</p>
                            </td>';
                } else {
                    if ($ctr) $output .= '<td>
                            <p class="text-sm font-weight-bold mb-0">' . $kee->N_tp . '</p>
                        </td>';
                    if ($tp) $output .= '<td>
                            <p class="text-sm font-weight-bold mb-0">' . $kee->N_cntr . '</p>
                        </td>';
                    if ($minip) $output .= '<td >
                            <p class="text-sm font-weight-bold mb-0">' . $kee->N_mini_project . '</p>
                        </td>';
                    if ($ex) $output .= '<td>
                            <p class="text-sm font-weight-bold mb-0">' . $kee->N_examen_nor . '</p>
                        </td>
                        <td>
                            <p class="text-sm font-weight-bold mb-0">' . $kee->N_examen_ratt . '</p>
                        </td>';
                }
                $output .= '</tr>';
            }
        }
        $output .= '</tbody>';
        echo $output;
    }
    public function updateNotes(Request $request)
    {
        //multiple inputs
        for ($i = 0; $i < $request->lenghtt; $i++) {

            DB::table('note')->where('id', $request->content[$i][0])->update([

                'N_tp' => $request->content[$i][1],
                'N_cntr' => $request->content[$i][2],
                'N_mini_project' => $request->content[$i][3],
                'N_examen_nor' => $request->content[$i][4],
                'N_examen_ratt' => $request->content[$i][5],
            ]);
        }
        return response()->json(['success' => true]);
    }
    public function modifierStatut(Request $request)
    {
        $anne_req = 'SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
        $annee = DB::select($anne_req);
        $anne = $annee[0]->année_universitaire ?? "";
        $hh = DB::select('SELECT blocker FROM enseinganant_de_module WHERE id_element=' . $request->id_el . ' AND id_enseignant="' . Auth::user()->CIN . '" AND
        section ="' . $request->id_sec . '" AND année ="' . $anne . '"');
        $id = 2;
        if ($hh[0]->blocker == 4) {
            $id = 5;
        }
        DB::table('enseinganant_de_module')->where('id_element', $request->id_el)
            ->where('section', $request->id_sec)
            ->where('année', $anne)
            ->update(['blocker' => $id]);
    }

    public function export(Request $request)
    {
        return Excel::download(new NotesExport($request), 'notes.xlsx');
    }
}
