<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\DB;

class ControllerGestionNote extends Controller
{
    public function show($menu = 'Gestion des notes')
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
        $cycle = DB::select('SELECT * FROM cycle');
        $anne = DB::select('SELECT DISTINCT année_universitaire as annee FROM inscription');

        $anne_req = 'SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
        $annee = DB::select($anne_req);
        $anne1 = $annee[0]->année_universitaire ?? "";
        $name = $menu;

        view('inc.nav');
        return view('gestionNotes', compact('name', 'nom', 'prenom',  'cycle', 'anne', 'anne1', 'poste'));
    }


    public function get_notes(Request $request)
    {
        $req1 = 'SELECT Co_cntr,Co_tp ,Co_mini_project,Co_examen FROM elements WHERE id_element =' . $request->id_el;
        $hh = DB::select('SELECT blocker FROM enseinganant_de_module WHERE id_element=' . $request->id_el . '  AND
        section ="' . $request->id_sec . '" AND année ="' . $request->annee . '"');
        $co = DB::select($req1);
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
        if ($minip) $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">mini project</th>';
        if ($ex) $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">examen normale</th>';
        if ($hh[0]->blocker > 4) $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">examen rat</th>';
        $output .= '</tr></thead><tbody>';


        $req = 'SELECT et.CNE,et.nom,et.prenom,';
        if ($ctr) $req .= 'IF(n.N_cntr=-1,"ABS",n.N_cntr) as N_cntr,';
        if ($tp) $req .= 'IF(n.N_tp=-1,"ABS",n.N_tp) as N_tp,';
        if ($minip) $req .= 'IF(n.N_mini_project=-1,"ABS",n.N_mini_project) as N_mini_project';
        if ($minip && $ex) $req .= ',';
        if ($ex) $req .= 'IF(n.N_examen_nor=-1,"ABS",n.N_examen_nor) as N_examen_nor,IF(n.N_examen_ratt=-1,"ABS",n.N_examen_ratt) as N_examen_ratt';

        $req .= ' FROM inscription i,note n,etudiant et
        WHERE et.CNE=n.CNE AND i.CNE=n.CNE AND n.id_element=' . $request->id_el . '
        AND i.section like"%' . $request->id_sec . '%" AND n.année="' . $request->annee . '" AND
        ( et.CNE LIKE "%' . $request->coco . '%" OR concat(et.nom," ",et.prenom) LIKE "%' . $request->coco . '%" OR  concat(et.prenom," ",et.nom) LIKE "%' . $request->coco . '%")';
        if ($request->check) {
            $req .= 'AND (';
            if ($ctr) $req .= 'n.N_cntr is null OR ';
            if ($tp) $req .= 'n.N_tp is null OR ';
            if ($minip) $req .= 'n.N_mini_project is null OR ';
            if ($ex) {
                $hh = DB::select('SELECT blocker FROM enseinganant_de_module WHERE id_element=' . $request->id_el . ' AND
                    section ="' . $request->id_sec . '" AND année ="' . $request->annee . '"');
                    $req .= 'n.N_examen_nor is null ';
                    if($hh[0]->blocker > 3)
                        $req .= ' OR n.N_examen_ratt is null ';
                    $req .= ')';
                }

        }
        $data = DB::select($req);
        $count = count($data);
        if ($count == 0) {
            $output .= '
                <tr>
                <td><h5 class="text-center mt-4">No result found</h5></td>
                </tr>
                ';
        } else {
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

                if ($tp) $output .= '<td>
                                <p class="text-sm font-weight-bold mb-0">' . $kee->N_tp . '</p>
                            </td>';
                if ($ctr) $output .= '<td>
                                <p class="text-sm font-weight-bold mb-0">' . $kee->N_cntr . '</p>
                            </td>';
                if ($minip) $output .= '<td>
                                <p class="text-sm font-weight-bold mb-0">' . $kee->N_mini_project . '</p>
                            </td>';
                if ($ex) $output .= '<td>
                                    <p class="text-sm font-weight-bold mb-0">' . $kee->N_examen_nor . '</p>
                                </td>';
                if ($hh[0]->blocker > 4) $output .= '<td>
                                    <p class="text-sm font-weight-bold mb-0">' . $kee->N_examen_ratt . '</p>
                                </td>';
                $output .= '</tr>';
            }
        }
        $output .= '</tbody>';
        echo $output;
    }
    public function getrequests()
    {
        $anne_req = 'SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
        $annee = DB::select($anne_req);
        $anne = $annee[0]->année_universitaire ?? "";

        $req_ele_activer = 'SELECT em.id, u.nom,u.prenom,em.section,f.nom_filiere,e.nom_element FROM
        filière f,users u,enseinganant_de_module em,elements e WHERE
        em.id_element=e.id_element AND u.CIN=em.id_enseignant AND
        f.id_filiere = em.id_filiere AND em.blocker IN (2,5) and année="' . $anne . '"';
        $data = DB::select($req_ele_activer);
        $count = count($data);
        $output = "";
        if ($count == 0) {
            $output .= '<h5 class="text-center mt-4">No result found</h5>';
        } else {
            foreach ($data as $kee) {

                $output .= '<li
                        class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg row">
                        <div class="col-md-7 d-flex flex-column">
                            <h6 class="mb-3 text-uppercase text-sm">' . $kee->nom_element . '
                            </h6>
                            <span class="mb-2 text-xs">Enseignant : <span
                                    class="text-dark text-uppercase font-weight-bold ms-2">' . $kee->nom . ' ' . $kee->prenom . '</span></span>
                            <span class="mb-2 text-xs">Filiere: <span
                                    class="text-dark ms-2 font-weight-bold text-uppercase">' . $kee->nom_filiere . '</span></span>
                            <span class="text-xs">Section : <span
                                    class="text-dark ms-2 font-weight-bold text-uppercase">' . $kee->section . '</span></span>
                        </div>
                        <div class="col-md-5 mx-auto text-center mt-3">
                            <a class="btn btn-link text-primary text-gradient px-3 mb-0 ha-view" id="' . $kee->id . '"
                                href="javascript:;" data-bs-toggle="modal"
                                data-bs-target="#Modal_view"><i
                                    class="fal fa-eye me-2"></i>View</a>
                            <a class="btn btn-link text-danger px-3 mb-0 ha-refuser" id="ha-' . $kee->id . '"
                            href="javascript:;"><i
                                    class="fas fa-times me-2"
                                    aria-hidden="true"></i>Refuser</a>
                            <a class="btn btn-link text-success px-3 mb-0 ha-valider" id="' . $kee->id . '"
                                href="javascript:;"><i
                                    class="fad fa-check-circle me-2 "
                                    aria-hidden="true"></i>Valider</a>
                        </div>
                    </li>';
            }
        }
        echo $output;
    }
    public function get_note(Request $request)
    {
        $req1 = 'SELECT Co_cntr,Co_tp ,Co_mini_project,Co_examen FROM elements WHERE id_element = (SELECT id_element FROM enseinganant_de_module WHERE id=' . $request->id . ')';
        $hh = DB::select('SELECT blocker FROM enseinganant_de_module WHERE id=' . $request->id);
        $co = DB::select($req1);
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
        if ($minip) $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">mini project</th>';
        if ($ex) $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">examen normale</th>';
        if ($hh[0]->blocker > 4) $output .= '<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">examen rat</th>';
        $output .= '</tr></thead><tbody>';

        $whitch_se = DB::select('SELECT ms.id_semestre FROM enseinganant_de_module em,module_ds_semestre ms ,elements e WHERE em.id_element=e.id_element AND e.id_module=ms.id_module AND em.id=' . $request->id);

        $req = 'SELECT et.CNE,et.nom,et.prenom,';
        if ($ctr) $req .= 'IF(n.N_cntr=-1,"ABS",n.N_cntr) as N_cntr,';
        if ($tp) $req .= 'IF(n.N_tp=-1,"ABS",n.N_tp) as N_tp,';
        if ($minip) $req .= 'IF(n.N_mini_project=-1,"ABS",n.N_mini_project) as N_mini_project';
        if ($minip && $ex) $req .= ',';

        if ($ex) $req .= 'IF(n.N_examen_nor=-1,"ABS",n.N_examen_nor) as N_examen_nor,IF(n.N_examen_ratt=-1,"ABS",n.N_examen_ratt) as N_examen_ratt';
        if ($whitch_se[0]->id_semestre <= 2) {
            $req .= ' FROM note n,etudiant et,enseinganant_de_module em,inscription i
            WHERE et.CNE=n.CNE AND n.CNE=i.CNE AND LEFT(i.section,1) = em.section AND n.id_element=em.id_element AND n.année = em.année AND em.id = ' . $request->id;
        } else {
            $req .= ' FROM note n,etudiant et,enseinganant_de_module em,inscription i
            WHERE et.CNE=n.CNE AND n.CNE=i.CNE AND RIGHT(i.section,1) = em.section AND n.id_element=em.id_element AND n.année = em.année AND em.id = ' . $request->id;
        }
        $data = DB::select($req);
        $count = count($data);
        if ($count == 0) {
            $output .= '
                <tr>
                <td><h5 class="text-center mt-4">No result found</h5></td>
                </tr>
                ';
        } else {
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

                if ($tp) $output .= '<td>
                                <p class="text-sm font-weight-bold mb-0">' . $kee->N_tp . '</p>
                            </td>';
                if ($ctr) $output .= '<td>
                                <p class="text-sm font-weight-bold mb-0">' . $kee->N_cntr . '</p>
                            </td>';
                if ($minip) $output .= '<td>
                                <p class="text-sm font-weight-bold mb-0">' . $kee->N_mini_project . '</p>
                            </td>';
                if ($ex) $output .= '<td>
                                    <p class="text-sm font-weight-bold mb-0">' . $kee->N_examen_nor . '</p>
                                </td>';
                if ($hh[0]->blocker > 4) $output .= '<td>
                                    <p class="text-sm font-weight-bold mb-0">' . $kee->N_examen_ratt . '</p>
                                </td>';
                $output .= '</tr>';
            }
        }
        $output .= '</tbody>';
        echo $output;
    }
    public function modifierStatut(Request $request)
    {

        $hh = DB::select('SELECT blocker FROM enseinganant_de_module WHERE id=' . $request->id);
        $id = 3;
        if ($hh[0]->blocker == 5) {
            $id = 6;
        }
        DB::table('enseinganant_de_module')->where('id', $request->id)->update(['blocker' => $id]);
    }
    public function modifierStatutsst(Request $request)
    {
        $hh = DB::select('SELECT blocker FROM enseinganant_de_module WHERE id=' . $request->id);
        $id = 1;
        if ($hh[0]->blocker == 5) {
            $id = 4;
        }
        DB::table('enseinganant_de_module')->where('id', $request->id)->update(['blocker' => $id]);
    }
    public function modifierStatutee(Request $request)
    {
        $request->validate([
            'id_fi' => ['required'],
        ]);
        $anne_req = 'SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
        $annee = DB::select($anne_req);
        $anne = $annee[0]->année_universitaire ?? "";
        $hh = DB::select('SELECT blocker FROM enseinganant_de_module WHERE id_element LIKE "%' . $request->id_el . '%"  AND
        section LIKE "%' . $request->id_sec . '%" AND année ="' . $anne . '" AND id_filiere =' . $request->id_fi );
        $blocker = $hh[0]->blocker ?? '';
        $idd = 0;
        if ($request->id == 1) {
            // deblocker
            if ($request->check == 1) {
                //
                if ($blocker > 2) {
                    //
                    $idd = 4;
                } else {
                    $idd = 1;
                }
            } else {
                if ($blocker > 3) {
                    //
                    $idd = 4;
                } else {
                    $idd = 1;
                }
            }
        } else {
            // blocker
            if ($blocker == 4) {
                //
                $idd = 7;
            }
            if ($blocker == 1) {
                //
                $idd = 0;
            }
        }

        DB::table('enseinganant_de_module')->where('id_element', 'like', '%' . $request->id_el . '%')
            ->where('section', 'like', '%' . $request->id_sec . '%')
            ->where('id_filiere', $request->id_fi)
            ->where('année', $request->anne)
            ->update(['blocker' => $idd]);
    }
}
