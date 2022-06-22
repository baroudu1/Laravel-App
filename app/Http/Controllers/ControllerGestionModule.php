<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;

use App\Exports\modulesExport;
use App\Imports\modulesImport;
use Maatwebsite\Excel\Facades\Excel;

class ControllerGestionModule extends Controller
{
    public function show($menu = 'Gestion des modules')
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
        view('inc.nav');
        return view('gestionModule', compact('name', 'nom', 'prenom',  'cycle', 'poste'));
    }

    public function getModule(Request $req)
    {
        $request = $req->coco;
        $modules = DB::select(
            ' SELECT  m.id_module,m.nom_module,e.nom_element,f.nom_filiere,s.nom_semestre
            FROM module m, semetre s,elements e,module_ds_semestre ms,filière f  WHERE
            m.id_module=e.id_module AND m.id_module=ms.id_module AND s.id_semestre=ms.id_semestre AND f.id_filiere=ms.id_filiere
			AND
	        (m.nom_module LIKE "%' . $request . '%"  OR f.nom_filiere LIKE "%' . $request . '%" OR s.nom_semestre LIKE "%' . $request . '%"
	        OR
		    m.nom_module IN (SELECT m.nom_module FROM module m, elements e WHERE m.id_module=e.id_module AND e.nom_element LIKE "%' . $request . '%" )
		    )ORDER BY id_module DESC'
        );
        $count = count($modules);
        if ($count == 0) {
            echo '
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
            $output = "";
            $nom_mm = "";
            foreach ($modules as $element) {
                $count1 = DB::table('elements')->where('id_module', $element->id_module)->count();
                /*$count3 = DB::select('SELECT id_element from elements  WHERE
                id_module=' . $element->id_module . ' and nom_element LIKE "%' . $request . '%"');
                $count2 = count($count3);*/
                if ($count1 != 0) {
                    if ($element->nom_module != $nom_mm) {
                        $nom_mm = $element->nom_module;
                        $output .= '<tr>
                            <td class="align-middle col-1" rowspan=' . $count1 . '>
                                <div class="form-check px-5">
                                    <input class="form-check-input d-flex flex-column check"
                                        type="checkbox" value="" data-id="' . $element->id_module . '">
                                </div>
                            </td>
                            <td class="align-middle " rowspan=' . $count1 . '>
                                <div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">' . $element->nom_module . '</h6>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle ">
                                <span class="text-secondary text-xs font-weight-bold">' . $element->nom_element . '</span>
                            </td>
                            <td class="align-middle " rowspan=' . $count1 . '>
                                <span class="text-secondary text-xs font-weight-bold mx-3">' . $element->nom_filiere . '</span>
                            </td>
                            <td class="align-middle " rowspan=' . $count1 . '>
                                <span class="text-secondary text-xs font-weight-bold mx-3">' . $element->nom_semestre . '</span>
                            </td>
                            <td class="align-middle"rowspan=' . $count1 . '>
                                <button class="btn btn-link text-secondary mb-0 ha-view-module" id="view-' . $element->id_module . '" data-bs-toggle="modal"
                                    data-bs-target="#Modal_view">
                                    <i class="fas fa-eye fa-lg"  style="color:#fd7e14"></i>
                                </button>
                                <button class="btn btn-link text-secondary mb-0 ha-edit-module" id="edit-' . $element->id_module . '" data-bs-toggle="modal"
                                    data-bs-target="#exampleModalMessage">
                                    <i class="far fa-edit fa-lg text-info"></i>
                                </button>
                            </td>
                        </tr>';
                    } else {
                        $output .= '<tr> <td class="align-middle">
                            <span class="text-secondary text-xs font-weight-bold">' . $element->nom_element . '</span>
                            </td></tr>';
                    }
                }
            }
            echo $output;
        }
    }
    public function updateModule(Request $request)
    {
        $request->validate([
            'nom_module' => ['required', 'string', 'max:80'],
            'id_se' => ['required'],
        ]);

        DB::table('module')->where('id_module', $request->id_module)->update([
            'nom_module' => $request->nom_module,
        ]);

        DB::table('elements')->whereNotIn('id_element', $request->id_elements1)->where('id_module', $request->id_module)->delete();
        for ($i = 0; $i < $request->nbr_ele; $i++) {
            DB::table('elements')->upsert([
                'id_module' => $request->id_module,
                'id_element' => $request->id_elements1[$i],
                'nom_element' => $request->content[$i][0],
                'Co_element' => $request->content[$i][1],
                'Co_cntr' => $request->content[$i][2],
                'Co_tp' => $request->content[$i][3],
                'Co_examen' => $request->content[$i][4],
                'Co_mini_project' => $request->content[$i][5],
            ], ['id_element'], ['nom_element', 'Co_element', 'Co_cntr', 'Co_tp', 'Co_examen', 'Co_mini_project']);
        }
        if ($request->id_fi != "") {
            DB::table('module_ds_semestre')->where('id_module', $request->id_module)->update([
                'id_semestre' => $request->id_se,
                'id_filiere' => $request->id_fi,
            ]);
        } else {
            DB::table('module_ds_semestre')->where('id_module', $request->id_module)->update([
                'id_semestre' => $request->id_se,
            ]);
        }


        return response()->json(['success' => true]);
    }

    public function insertModule(Request $request)
    {
        $request->validate([
            'nom_module' => ['required', 'string', 'max:80'],
            'id_fi' => ['required'],
            'id_se' => ['required'],
        ]);

        DB::table('module')->insert([
            'nom_module' => $request->nom_module,
        ]);
        $id_module = DB::table('module')->where('nom_module', $request->nom_module)->value('id_module');

        for ($i = 0; $i < $request->nbr_ele; $i++) {
            DB::table('elements')->insert([
                'id_module' => $id_module,
                'nom_element' => $request->content[$i][0],
                'Co_element' => $request->content[$i][1],
                'Co_cntr' => $request->content[$i][2],
                'Co_tp' => $request->content[$i][3],
                'Co_examen' => $request->content[$i][4],
                'Co_mini_project' => $request->content[$i][5],
            ]);
        }
        DB::table('module_ds_semestre')->insert([
            'id_module' => $id_module,
            'id_filiere' => $request->id_fi,
            'id_semestre' => $request->id_se,

        ]);
        return response()->json(['success' => true]);
    }

    public function getInfoModule(Request $request)
    {
        $module_info = DB::select(
            'SELECT  m.nom_module,e.nom_element,e.Co_element,e.Co_cntr,e.Co_tp,e.Co_examen,e.Co_mini_project,f.nom_filiere,s.nom_semestre
            FROM module m, semetre s,elements e,module_ds_semestre ms,filière f  WHERE
            m.id_module=e.id_module AND m.id_module=ms.id_module AND s.id_semestre=ms.id_semestre AND
            f.id_filiere=ms.id_filiere and m.id_module=' . $request->id_module
        );
        $count = count($module_info);
        if ($count == 0) {
            echo '<h5 class="text-center mt-4">No result found</h5>';
        } else {
            $output = "";
            $i = 0;
            foreach ($module_info as $element) {
                if ($i == 0) {
                    $output .= '<ul class="list-group " style="display: inline;">
                    <li class="list-group-item border-0 ps-0 pt-0 text-sm" style="display: inline;">
                        <strong class="text-dark">Filiere:</strong> &nbsp; ' . $element->nom_filiere . '
                    </li>
                    <li class="list-group-item border-0 ps-0 text-sm" style="display: inline;"><strong
                            class="text-dark">Semestre:</strong> &nbsp;
                            ' . $element->nom_semestre . '</li>
                    </ul>
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                <tr class="text-center">
                                        <th colspan="6"
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            ' . $element->nom_module . '</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Element</th>
                                        <th
                                            class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            %</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            %CC</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            %TP</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            %Examen</th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            %mini project/PFE</th>
                                    </tr>
                                </thead>
                                <tbody>';
                }
                $output .= '<tr class="text-center">
                        <td>
                            <div class="my-auto">
                                <h6 class="mb-0 text-xs">' . $element->nom_element . ' </h6>
                            </div>
                        </td>
                        <td>
                            <div class="my-auto">
                                <h6 class="mb-0 text-xs">' . $element->Co_element . '%</h6>
                            </div>
                        </td>
                        <td>
                            <div class="my-auto">
                                <h6 class="mb-0 text-xs">' . $element->Co_cntr . '%</h6>
                            </div>
                        </td>
                        <td>
                            <div class="my-auto">
                                <h6 class="mb-0 text-xs">' . $element->Co_tp . '%</h6>
                            </div>
                        </td>
                        <td>
                            <div class="my-auto">
                                <h6 class="mb-0 text-xs">' . $element->Co_examen . '%</h6>
                            </div>
                        </td>
                        <td>
                            <div class="my-auto">
                                <h6 class="mb-0 text-xs">' . $element->Co_mini_project . '%</h6>
                            </div>
                        </td>
                    </tr>';

                $i = 1;
            }
            $output .= '</tbody>
            </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary"
                    data-bs-dismiss="modal">Close</button>
            </div>';
            echo $output;
        }
    }

    public function SupprimerModule(Request $request)
    {
        DB::table('module_ds_semestre')->whereIn('id_module', explode(",", $request->id))->delete();
        DB::table('elements')->whereIn('id_module', explode(",", $request->id))->delete();
        DB::table('module')->whereIn('id_module', explode(",", $request->id))->delete();
        return response()->json(['success' => "Module Deleted successfully."]);
    }


    public function info_Module(Request $requests)
    {
        $data = DB::select(
            'SELECT e.id_element,m.nom_module, e.nom_element, e.Co_element, e.Co_cntr, e.Co_tp, e.Co_examen,
        e.Co_mini_project,ms.id_filiere ,f.nom_filiere,f.id_cycle, ms.id_semestre FROM module m, elements e,
        module_ds_semestre ms,filière f
        WHERE m.id_module=e.id_module AND m.id_module=ms.id_module  AND
        f.id_filiere=ms.id_filiere and m.id_module=:id_module',
            ['id_module' => $requests->id_module]
        );
        $num = 0;
        $output = "";
        $nom_modul = "";
        $id_cy = 0;
        $nom_fi = "";
        $id_se = 0;
        foreach ($data as $item) {
            $id_cy = $item->id_cycle;
            $nom_fi = $item->nom_filiere;
            $id_se = $item->id_semestre;
            $nom_modul = $item->nom_module;
            $output .= '<div class="row mx-auto ha-check" id="' . $item->id_element . '">
            <div class="input-group mt-3 mx-auto" style="width:95%;">
                <input class="form-control " id="element_name' . $num . '"  value="' . $item->nom_element . '"
                        placeholder="Nom d element" aria-describedby="button-addon1" required>
                <input type="number" min="0" max="100" class="form-control" id="co_m' . $num . '"    value="' . $item->Co_element . '"
                        placeholder="&nbsp;&nbsp;% dans module" aria-describedby="button-addon1">
                <input type="number" min="0" max="100" class="form-control" id="co_cc' . $num . '" value="' . $item->Co_cntr . '"
                        placeholder="&nbsp;&nbsp;% CC" aria-describedby="button-addon1">
                <input type="number" min="0" max="100" class="form-control" id="co_tp' . $num . '" value="' . $item->Co_tp . '"
                        placeholder="&nbsp;&nbsp;% TP" aria-describedby="button-addon1">
                <input type="number" min="0" max="100" class="form-control" id="co_examen' . $num . '" value="' . $item->Co_examen . '"
                        placeholder="&nbsp;&nbsp;% EXAMEN" aria-describedby="button-addon1">
                <input type="number" min="0" max="100" class="form-control" id="co_mini' . $num . '" value="' . $item->Co_mini_project . '"
                        placeholder="&nbsp;&nbsp;% Mini Projet" aria-describedby="button-addon1">
            </div>
            <a href="javascript:;" id="btnSup' . $num . '" style="width:4%" class="mt-4 mx-auto close_element">
                <i class="fas fa-minus-circle text-danger fa-lg" aria-hidden="true"></i>
            </a>
            </div>';
            $num++;
        }

        return response()->json(['num' => $num, 'nom_module' => $nom_modul, 'id_cy' => $id_cy, 'nom_fi' => $nom_fi, 'id_se' => $id_se, 'output' => $output]);
    }
    public function export()
    {
        $s = '';
        return Excel::download(new modulesExport($s), 'Modules.xlsx');
    }

    public function import(Request $request)
    {
        //$_FILES['userfile']['error'];
        $imageName = 'Modules.xlsx';
        $imageTmp = $request->file('excel');
        $imageFull = rand(100,1000000000)."_".$imageName;
        move_uploaded_file($imageTmp,"upload/".$imageFull);
        $k = "upload/".$imageFull;
        Excel::import(new modulesImport,  $k);
    }
}
