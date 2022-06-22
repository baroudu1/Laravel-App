<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Exports\AffectationExport;
use App\Imports\AffectatioImport;
use Maatwebsite\Excel\Facades\Excel;


use Illuminate\Support\Facades\DB;

class AffectationController extends Controller
{
    public function show($menu = 'Affictation Des Modules')
    {
        //$CIN= DB::select('select CIN from users where email=:email', ['email'=>""]);
        $CIN = Auth::user()->CIN;
        $nom = Auth::user()->nom;
        $prenom = Auth::user()->prenom;
        $poste = null;
        $element = null;
        $departement = null;
        $semestre = null;
        $admin = DB::select('select  CIN from admin where CIN=:cin', ['cin' => $CIN]);
        $coordinateur = DB::select('select coordinateur from enseignant where CIN=:cin ', ['cin' => $CIN]);
        $anne_req = 'SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
        $annee = DB::select($anne_req);
        $anne = $annee[0]->année_universitaire ?? "";
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
            $element = DB::select('SELECT DISTINCT e.id_element,e.nom_element FROM elements e, module_ds_semestre ms WHERE ms.id_module = e.id_module AND ms.id_filiere = :id_filiere', ['id_filiere' => $coordinateur]);
            $departement = DB::select('SELECT * FROM département');
        } else {
            $poste = -2;
        }
        $name = $menu;
        view('inc.nav');
        return view('affectation', compact('name', 'nom', 'prenom',  'poste', 'departement', 'element', 'anne'));
    }
    public function getenbydep(Request $request)
    {
        $rst = DB::select('SELECT u.CIN ,u.nom , u.prenom FROM users u ,enseignant e WHERE u.CIN = e.CIN AND e.id_departement = ' . $request->id);
        return response()->json($rst);
    }
    public function GetAffectationInfo(Request $request)
    {
        $rst = DB::select('SELECT u.CIN,u.nom,u.prenom,d.nom_departement,d.id_departement,em.section,e.nom_element
        FROM users u,département d ,enseinganant_de_module em,enseignant en,elements e
        WHERE u.CIN=en.CIN AND en.CIN=em.id_enseignant AND d.id_departement=en.id_departement AND e.id_element=em.id_element
        AND em.id=' . $request->id);
        return response()->json(['rst' => $rst]);
    }
    public function AjouterAffectation(Request $request)
    {
        $coordinateur = DB::select('select coordinateur from enseignant where CIN=:cin ', ['cin' => Auth::user()->CIN]);
        foreach ($coordinateur as $kalue) {
            $coordinateur = $kalue->coordinateur;
        }
        $anne_req = 'SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
        $anne = DB::select($anne_req);
        $date = $anne[0]->année_universitaire ?? "";
        if ($request->id == 0) {
            DB::table('enseinganant_de_module')->insert(
                [
                    'id_enseignant' => $request->CIN,
                    'id_element' => $request->id_el,
                    'id_filiere' => $coordinateur,
                    'section' => $request->id_sec,
                    'année' => $date
                ]
            );
        } else {
            if ($request->CIN != "" && $request->id_sec != "") {
                DB::table('enseinganant_de_module')->where('id', $request->id)->update(
                    [
                        'id_enseignant' => $request->CIN,
                        'id_element' => $request->id_el,
                        'section' => $request->id_sec,
                    ]
                );
            } else if ($request->CIN == "" && $request->id_sec == "") {
                DB::table('enseinganant_de_module')->where('id', $request->id)->update(
                    [
                        'id_element' => $request->id_el,
                    ]
                );
            } else if ($request->CIN == "") {
                DB::table('enseinganant_de_module')->where('id', $request->id)->update(
                    [
                        'id_element' => $request->id_el,
                        'section' => $request->id_sec,
                    ]
                );
            } else if ($request->id_sec == "") {
                DB::table('enseinganant_de_module')->where('id', $request->id)->update(
                    [
                        'id_enseignant' => $request->CIN,
                        'id_element' => $request->id_el,
                    ]
                );
            }
        }
    }
    public function GetAffectation(Request $request)
    {
        $anne_req = 'SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
        $anne = DB::select($anne_req);
        $coordinateur = DB::select('select coordinateur from enseignant where CIN=:cin ', ['cin' => Auth::user()->CIN]);
        foreach ($coordinateur as $kalue) {
            $coordinateur = $kalue->coordinateur;
        }

        $rst = DB::select('SELECT em.id,u.CIN,u.nom,u.prenom,d.nom_departement,d.id_departement,em.section,e.nom_element FROM users u,département d ,enseinganant_de_module em,enseignant en,elements e WHERE u.CIN=em.id_enseignant AND en.CIN=em.id_enseignant AND d.id_departement=en.id_departement AND e.id_element=em.id_element AND em.année LIKE "%' . ($anne[0]->année_universitaire ?? "") . '%" AND em.id_filiere = ' . $coordinateur . ' AND
        (d.nom_departement LIKE "%' . $request->coco . '%" OR e.nom_element LIKE "%' . $request->coco . '%" OR em.section LIKE "%' . $request->coco . '%" OR u.CIN LIKE "%' . $request->coco . '%" OR
        CONCAT(u.nom," ",u.prenom) LIKE "%' . $request->coco . '%" OR CONCAT(u.prenom," ",u.nom) LIKE "%' . $request->coco . '%")');

        $count = count($rst);
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
            foreach ($rst as $element) {
                $arr = explode(' ', trim($element->nom_element));
                $element->nom_element = '';
                $ii = 0;
                foreach ($arr as $aa) {
                    if ($ii < 6) {
                        $element->nom_element .= ' ' . $aa;
                    }
                    $ii++;
                }
                $output .= '<tr>
                    <td>
                        <p>' . $element->nom_element . '</p>
                    </td>
                    <td>
                        <p>' . $element->CIN . '</p>
                    </td>
                    <td>
                        <p>' . $element->nom . '</p>
                    </td>
                    <td>
                        <p>' . $element->prenom . '</p>
                    </td>
                    <td>
                        <p>' . $element->nom_departement . '</p>
                    </td>
                    <td>
                        <p>' . $element->section . '</p>
                    </td>
                    <td class="align-middle">
                        <button
                            class="btn btn-link text-secondary mb-0 ha-me" data-bs-toggle="modal"
                            data-bs-target="#exampleModalMessage"
                            id="edit-' . $element->id . '">
                            <i class="far fa-edit fa-lg text-info"></i>
                        </button>
                        <button  class="btn btn-link text-secondary mb-0 ha-sup"data-bs-toggle="modal"
                        data-bs-target="#exampleModalMessage1"
                            id="supp-' . $element->id . '">
                            <i class="fas fa-minus-circle fa-lg text-x text-danger"></i>
                        </button>
                    </td>
                </tr>';
            }
            echo $output;
        }
    }
    public function SuppAffectation(Request $request)
    {
        DB::table('enseinganant_de_module')->where('id', $request->id)->delete();

        return response()->json(['success' => "Etudiant Deleted successfully."]);
    }

    public function import(Request $request)
    {
        $imageName = 'Affectation.xlsx';
        $imageTmp = $request->file('excel');
        $imageFull = rand(100,1000000000)."_".$imageName;
        move_uploaded_file($imageTmp,"upload/".$imageFull);
        $k = "upload/".$imageFull;
        Excel::import(new AffectatioImport,  $k);
    }

    public function export(Request $request)
    {

        return Excel::download(new AffectationExport($request), 'Affectation.xlsx');
    }
}
