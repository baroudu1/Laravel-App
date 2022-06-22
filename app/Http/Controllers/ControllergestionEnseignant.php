<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Exports\enseignantExport;
use App\Imports\enseignantImport;
use Maatwebsite\Excel\Facades\Excel;


class ControllergestionEnseignant extends Controller
{
    public function show($menu = 'Gestion des Enseignant')
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
        $data = DB::select('SELECT u.id,u.CIN,u.nom,u.prenom,u.email,d.nom_departement FROM users u , enseignant e, département d WHERE u.CIN=e.CIN and d.id_departement=e.id_departement');
        $data1 = DB::select('SELECT u.id,u.CIN,u.nom,u.prenom,u.email,d.nom_departement,f.nom_filiere FROM users u , enseignant e, département d,filière f WHERE u.CIN=e.CIN and d.id_departement=e.id_departement
        AND e.coordinateur=f.id_filiere');
        $cycle = DB::select('SELECT * FROM cycle');
        $departement = DB::select('SELECT * FROM département');
        view('inc.nav');
        return view('gestionEnseignant', compact('name', 'nom', 'prenom',  'data', 'data1', 'cycle', 'departement', 'poste'));
    }
    public function getEnseignants(Request $request)
    {
        $data = DB::select('SELECT u.id,u.CIN,u.nom,u.prenom,u.email,d.nom_departement FROM users u , enseignant e, département d WHERE u.CIN=e.CIN and d.id_departement=e.id_departement AND (u.CIN LIKE "%' . $request->coco . '%" OR CONCAT(u.nom," ",u.prenom) LIKE "%' . $request->coco . '%" OR CONCAT(u.prenom," ",u.nom) LIKE "%' . $request->coco . '%" OR d.nom_departement LIKE "%' . $request->coco . '%")');
        $data1 = DB::select('SELECT u.id,u.CIN,u.nom,u.prenom,u.email,d.nom_departement,f.nom_filiere FROM users u , enseignant e, département d,filière f WHERE u.CIN=e.CIN and d.id_departement=e.id_departement
        AND e.coordinateur=f.id_filiere AND (u.CIN LIKE "%' . $request->coco . '%" OR CONCAT(u.nom," ",u.prenom) LIKE "%' . $request->coco . '%" OR CONCAT(u.prenom," ",u.nom) LIKE "%' . $request->coco . '%" OR d.nom_departement LIKE "%' . $request->coco . '%" OR f.nom_filiere LIKE "%' . $request->coco . '%")');
        $count = count($data);
        $count1 = count($data1);
        $output = '<thead>
                <tr>
                    <th
                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                        CIN</th>
                    <th
                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                        Nom</th>
                    <th
                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                        Prenom</th>
                    <th
                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                        Departement
                    </th>
                    <th id="hah"
                        class=" text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"
                        style="display: none">
                        Filiere
                    </th>
                    <th></th>
                </tr>
            </thead>
            ';
        if ($count != 0) {
            if ($count1 != 0) {
                $output .= '<tbody class=" ha-mam">';
                foreach ($data1 as $keue) {
                    $output .= '
                            <tr class="' . $keue->CIN . ' coor-' . $keue->CIN . '">
                            <td>
                                <p>' . $keue->CIN . '</p>
                            </td>
                            <td>
                                <p>' . $keue->nom . '</p>
                            </td>
                            <td>
                                <p>' . $keue->prenom . '</p>
                            </td>
                            <td>
                                <p>' . $keue->nom_departement . '
                                </p>
                            </td>
                            <td>
                                <p>' . $keue->nom_filiere . '</p>
                            </td>

                            <td class="align-middle">
                                <button class="btn btn-link text-secondary mb-0 show-user"
                                    id="update-' . $keue->CIN . '" data-bs-toggle="modal"
                                    data-bs-target="#exampleModalMessage">
                                    <i class="far fa-edit fa-lg text-info"></i>
                                </button>
                                <button class="btn btn-link text-secondary mb-0  supprimer_btn "
                                    data-bs-toggle="modal" data-bs-target="#exampleModalMessage1"
                                    id="delete-' . $keue->CIN . '">
                                    <i class="fas fa-minus-circle fa-lg text-danger"></i>
                                </button>
                            </td>
                        </tr>';
                }
            } else {
                $output .= '<tbody class=" ha-mam">
                    <tr>
                    <td></td>
                    <td><h5 class="text-center mt-4">No result found</h5></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    </tr>';
            }
            $output .= '</tbody>
                <tbody class="ha-mam1">';
            foreach ($data as $keue) {
                $output .= '<tr class="' . $keue->CIN . ' ">
                        <td>
                            <p>' . $keue->CIN . '</p>
                        </td>
                        <td>
                            <p>' . $keue->nom . '</p>
                        </td>
                        <td>
                            <p>' . $keue->prenom . '</p>
                        </td>
                        <td>
                            <p>' . $keue->nom_departement . '
                            </p>
                        </td>
                        <td class="align-middle">
                            <button class="btn btn-link text-secondary mb-0 show-user"
                                id="update-' . $keue->CIN . '" data-bs-toggle="modal"
                                data-bs-target="#exampleModalMessage">
                                <i class="far fa-edit fa-lg text-info"></i>
                            </button>

                            <button class="btn btn-link text-secondary mb-0  supprimer_btn "
                                data-bs-toggle="modal" data-bs-target="#exampleModalMessage1"
                                id="delete-' . $keue->CIN . '">
                                <i class="fas fa-minus-circle fa-lg text-danger"></i>
                            </button>
                        </td>
                    </tr>
                    ';
            }
        } else {
            $output .= '<tr>
                <td></td>
                <td><h5 class="text-center mt-4">No result found</h5></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                </tr>
                ';
        }
        $output .= '</tbody>';
        echo $output;
    }

    public function showelement(Request $request)
    {
        $enseignant = DB::select('SELECT u.CIN,u.nom,u.prenom,u.email,d.id_departement FROM users u , enseignant e, département d WHERE u.CIN=e.CIN and d.id_departement=e.id_departement AND u.CIN= :CIN', ['CIN' => $request->CIN]);
        $coordinateur = DB::select('SELECT u.CIN,u.nom,u.prenom,u.email,d.id_departement,f.id_cycle,f.id_filiere,f.nom_filiere FROM users u , enseignant e, département d,filière f WHERE u.CIN=e.CIN and d.id_departement=e.id_departement
        AND e.coordinateur=f.id_filiere AND u.CIN= :CIN', ['CIN' => $request->CIN]);
        return response()->json(['enseignant' => $enseignant, 'coordinateur' => $coordinateur]);
    }

    public function suppEnseignant(Request $request)
    {
        DB::table('users')->where('CIN', $request->id)->delete();
        DB::table('enseignant')->where('CIN', $request->id)->delete();
        return response()->json([
            'succses' => true,
        ]);
    }

    public function insertEnseignant(Request $request)
    {
        $request->validate([
            'CIN' => ['required', 'string', 'max:50'],
            'nom' => ['required', 'string', 'max:50'],
            'prenom' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
        if ($request->coordinateur != 0) {

            $reqq = 'SELECT CIN from enseignant WHERE coordinateur=' . $request->coordinateur;
            $exist = DB::select($reqq);
            $exist = $exist[0]->CIN ?? "";
            if ($exist != "") {
                $coor = DB::select('SELECT nom,prenom FROM users WHERE CIN ="' . $exist . '"');
                if ($request->ver == 0) {
                    return response()->json(['hasuccess' => 1, 'nom' => $coor[0]->nom, 'prenom' => $coor[0]->prenom]);
                } else {
                    DB::table('enseignant')->where('CIN', $exist)->update([
                        'coordinateur' => 0,
                    ]);
                }
            }
        }

        DB::table('users')->insert([
            'CIN' => $request->CIN,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->CIN),
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('enseignant')->insert([
            'CIN' => $request->CIN,
            'id_departement' => $request->id_departement,
            'coordinateur' => $request->coordinateur,
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $filiere = "";
        $nom_departement = DB::table('département')->where('id_departement', $request->id_departement)->value('nom_departement');
        if ($request->coordinateur) {
            $filiere = DB::table('filière',)->where('id_filiere', $request->coordinateur)->value('nom_filiere');
        }

        return response()->json(['hasuccess' => 0, 'requestCIN' => $request->CIN, 'requestnom' => $request->nom, 'requestprenom' => $request->prenom, 'nom_departement' => $nom_departement, 'filiere' => $filiere]);
    }
    public function UpdateEnseignant(Request $request)
    {
        $request->validate([
            'CIN' => ['required', 'string', 'max:50'],
            'nom' => ['required', 'string', 'max:50'],
            'prenom' => ['required', 'string', 'max:50'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        if ($request->coordinateur != "") {
            if ($request->coordinateur != 0) {
                $reqq = 'SELECT CIN from enseignant WHERE coordinateur=' . $request->coordinateur;
                $exist = DB::select($reqq);
                $exist = $exist[0]->CIN ?? "";
                if ($exist != "") {
                    $coor = DB::select('SELECT nom,prenom FROM users WHERE CIN ="' . $exist . '"');
                    if ($request->ver == 0) {
                        return response()->json(['hasuccess' => 1, 'nom' => $coor[0]->nom, 'prenom' => $coor[0]->prenom]);
                    } else {
                        DB::table('enseignant')->where('CIN', $exist)->update([
                            'coordinateur' => 0,
                        ]);
                    }
                }
            }

            DB::table('enseignant')->where('CIN', $request->CIN1)->update([
                'CIN' => $request->CIN,
                'id_departement' => $request->id_departement,
                'coordinateur' => $request->coordinateur,
            ]);
        } else {

            DB::table('enseignant')->where('CIN', $request->CIN1)->update([
                'CIN' => $request->CIN,
                'id_departement' => $request->id_departement,
            ]);
        }
        DB::table('users')->where('CIN', $request->CIN1)->update([
            'CIN' => $request->CIN,
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,

        ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');


        $filiere = "";
        $nom_departement = DB::table('département')->where('id_departement', $request->id_departement)->value('nom_departement');
        if ($request->coordinateur) {
            $filiere = DB::table('filière')->where('id_filiere', $request->coordinateur)->value('nom_filiere');
        }

        return response()->json(['hasuccess' => 0, 'requestCIN' => $request->CIN, 'requestnom' => $request->nom, 'requestprenom' => $request->prenom, 'nom_departement' => $nom_departement, 'filiere' => $filiere]);
    }
    public function export()
    {

        return Excel::download(new enseignantExport, 'Enseignats.xlsx');
    }

    public function import(Request $request)
    {
        $imageName = 'Enseignats.xlsx';
        $imageTmp = $request->file('excel');
        $imageFull = rand(100,1000000000)."_".$imageName;
        move_uploaded_file($imageTmp,"upload/".$imageFull);
        $k = "upload/".$imageFull;
        Excel::import(new enseignantImport,  $k);
    }
}
