<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;




use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class getDirection extends Controller
{

    public function show($menu = 'Accueil')
    {
        //$CIN= DB::select('select CIN from users where email=:email', ['email'=>""]);
        $CIN = Auth::user()->CIN;
        $nom = Auth::user()->nom;
        $prenom = Auth::user()->prenom;
        $poste = null;
        $fill = null;
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
            $poste = -1;
        } else if ($coordinateur != []) {
            $poste = $coordinateur;
            if ($coordinateur > 0) {
                $fil = DB::select('select nom_filiere from filière where id_filiere=:id_filiere', ['id_filiere' => $coordinateur]);
                foreach ($fil as $keye) {
                    $fill = $keye->nom_filiere;
                }
            }
        } else {
            $poste = -2;
        }

        $anne_req = 'SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
        $annee = DB::select($anne_req);
        $anneee = $annee[0]->année_universitaire ?? "";
        $name = $menu;
        view('inc.nav');
        return view('dashboard', compact('name', 'nom', 'prenom',  'poste', 'fill', 'anneee'));
    }
    public function fill_progress(Request $request)
    {
        $output = '';
        $req_module = 'SELECT DISTINCT m.id_module,m.nom_module,em.id_filiere  FROM module m,elements e,enseinganant_de_module em WHERE m.id_module=e.id_module AND e.id_element=em.id_element AND em.année ="' . $request->annee . '" AND
            em.id_filiere = ' . $request->id_fi . ' AND em.blocker IN (1,2,4,5)';
        $modules = DB::select($req_module);
        if (count($modules) != 0) {
            foreach ($modules as $module) {
                $elements_req = 'SELECT id_element,Co_cntr,Co_tp ,Co_mini_project,Co_examen FROM elements WHERE id_module =' . $module->id_module;
                $elements = DB::select($elements_req);
                $nb_total = 0;
                $nb_remplis = 0;
                foreach ($elements as $element) {
                    $elements_req = 'SELECT id_element,Co_cntr,Co_tp ,Co_mini_project,Co_examen FROM elements WHERE id_module =' . $module->id_module;
                    $elements = DB::select($elements_req);
                    $req = 'SELECT N_cntr ,N_examen_nor, N_examen_ratt ,N_mini_project ,N_tp FROM note
                        WHERE id_element =' . $element->id_element . ' AND année  = "' . $request->annee . '"';
                    $notes = DB::select($req);
                    $ctr = ($element->Co_cntr != 0);
                    $tp = ($element->Co_tp != 0);
                    $minip = ($element->Co_mini_project != 0);
                    $ex = ($element->Co_examen != 0);
                    foreach ($notes as $note) {
                        if ($ctr) {
                            $nb_remplis = ($note->N_cntr !== null) ? ($nb_remplis + 1) : $nb_remplis;
                            $nb_total++;
                        }
                        if ($tp) {
                            $nb_remplis = ($note->N_tp !== null) ? ($nb_remplis + 1) : $nb_remplis;
                            $nb_total++;
                        }
                        if ($minip) {
                            $nb_remplis = ($note->N_mini_project !== null) ? ($nb_remplis + 1) : $nb_remplis;
                            $nb_total++;
                        }
                        if ($ex) {
                            $nb_remplis = ($note->N_examen_nor !== null) ? ($nb_remplis + 1) : $nb_remplis;
                            $nb_total++;
                        }
                    }
                }
                $por =  intval($nb_remplis / $nb_total * 100);
                $backk = '';
                if ($por <= 25) {
                    $backk = 'danger';
                } else if ($por <= 50) {
                    $backk = 'warning';
                } else if ($por <= 75) {
                    $backk = 'info';
                } else if ($por <= 100) {
                    $backk = 'success';
                }
                $arr = explode(' ',trim($module->nom_module));
                $module->nom_module = '';
                $ii =0;
                foreach($arr as $aa){
                    if($ii<6){
                        $module->nom_module .=' '.$aa;
                    }
                    $ii++;
                }
                $output .= '<tr>
                        <td>
                            <div class="d-flex px-2 py-1">
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm">' .$module->nom_module. '</h6>
                                </div>
                            </div>
                        </td>

                        <td class="align-middle">
                            <div class="progress-wrapper w-75 mx-auto">
                                <div class="progress-info">
                                    <div class="progress-percentage">
                                        <span class="text-xs font-weight-bold">' . $por . '%</span>
                                    </div>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-gradient-' . $backk . ' " style="width: ' . $por . '%" role="progressbar"
                                        aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </td>
                    </tr>';
            }
        } else {
            $output .= '<tr class="text-center">
                            <td colspan="2" >
                                <img src="img/nothing.jpg" alt="nothing" style="overflow:hidden;height:35vh">
                            </td>
                        </tr>';
        }

        echo $output;
    }

    public function fill_progress1(Request $request)
    {
        $output = '';
        $elements_req = 'SELECT e.id_element,e.nom_element ,em.section FROM elements e,enseinganant_de_module em WHERE
        e.id_element=em.id_element AND em.année ="' . $request->annee . '" AND em.id_enseignant="' . Auth::user()->CIN . '" AND em.blocker IN (1,2,4,5)';
        $elements = DB::select($elements_req);
        if (count($elements) != 0) {
            foreach ($elements as $element) {
                $nb_total = 0;
                $nb_remplis = 0;
                $elements_req = 'SELECT id_element,Co_cntr,Co_tp ,Co_mini_project,Co_examen FROM elements WHERE id_element =' . $element->id_element;
                $elements = DB::select($elements_req);
                $req = 'SELECT n.N_cntr ,n.N_examen_nor, n.N_examen_ratt ,n.N_mini_project ,n.N_tp
                FROM note n,inscription i WHERE
                n.CNE=i.CNE AND i.section LIKE "%' . $element->section . '%" AND id_element =' . $element->id_element . ' AND année  = "' . $request->annee . '"';
                $notes = DB::select($req);
                $ctr = ($elements[0]->Co_cntr != 0);
                $tp = ($elements[0]->Co_tp != 0);
                $minip = ($elements[0]->Co_mini_project != 0);
                $ex = ($elements[0]->Co_examen != 0);
                foreach ($notes as $note) {
                    if ($ctr) {
                        $nb_remplis = ($note->N_cntr !== null) ? ($nb_remplis + 1) : $nb_remplis;
                        $nb_total++;
                    }
                    if ($tp) {
                        $nb_remplis = ($note->N_tp !== null) ? ($nb_remplis + 1) : $nb_remplis;
                        $nb_total++;
                    }
                    if ($minip) {
                        $nb_remplis = ($note->N_mini_project !== null) ? ($nb_remplis + 1) : $nb_remplis;
                        $nb_total++;
                    }
                    if ($ex) {
                        $nb_remplis = ($note->N_examen_nor !== null) ? ($nb_remplis + 1) : $nb_remplis;
                        $nb_total++;
                    }
                }
                $por =  intval($nb_remplis / $nb_total * 100);
                $backk = '';
                if ($por <= 25) {
                    $backk = 'danger';
                } else if ($por <= 50) {
                    $backk = 'warning';
                } else if ($por <= 75) {
                    $backk = 'info';
                } else if ($por <= 100) {
                    $backk = 'success';
                }
                $arr = explode(' ',trim($element->nom_element));
                $element->nom_element = '';
                $ii =0;
                foreach($arr as $aa){
                    if($ii<6){
                        $element->nom_element .=' '.$aa;
                    }
                    $ii++;
                }
                $output .= '<tr>
                            <td>
                                <div class="d-flex px-2 py-1">

                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">' . $element->nom_element . '</h6>
                                    </div>
                                </div>
                            </td>

                            <td class="align-middle">
                                <div class="progress-wrapper w-75 mx-auto">
                                    <div class="progress-info">
                                        <div class="progress-percentage">
                                            <span class="text-xs font-weight-bold">' . $por . '%</span>
                                        </div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-gradient-' . $backk . ' " style="width: ' . $por . '%" role="progressbar"
                                            aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>';
            }
        } else {
            $output .= '<tr class="text-center">
                <td colspan="2" >
                    <img src="img/nothing.jpg" alt="nothing" style="overflow:hidden;height:35vh">
                </td>
            </tr>';
        }
        echo $output;
    }
}
