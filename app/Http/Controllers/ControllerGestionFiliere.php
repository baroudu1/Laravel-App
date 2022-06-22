<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\CycleExport;
use Illuminate\Support\Facades\Auth;
use App\Imports\CycleImport;

use Maatwebsite\Excel\Facades\Excel;

class ControllerGestionFiliere extends Controller
{
    public function show($menu = 'Gestion des Filières')
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
        view('inc.nav');
        return view('gestionFiliere', compact('name', 'nom', 'prenom', 'poste'));
    }
    public function getCycle(Request $req)
    {
        $request = $req->coco;
        $cycle = DB::select(
            'SELECT c.id_cycle,c.seuil_v,c.nom_cycle,f.nom_filiere FROM filière f,cycle c WHERE c.id_cycle=f.id_cycle AND (c.nom_cycle LIKE "%'.$request.'%"  OR
		    c.id_cycle IN (SELECT cc.id_cycle FROM filière ff,cycle cc WHERE cc.id_cycle=ff.id_cycle AND ff.nom_filiere LIKE "%' . $request . '%" )) ORDER BY c.id_cycle DESC'
        );
        $count = count($cycle);
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
            foreach ($cycle as $element) {
                $count1 = DB::table('filière')->where('id_cycle', $element->id_cycle)->count();
                if ($count1 != 0) {
                    if ($element->nom_cycle != $nom_mm) {
                        $nom_mm = $element->nom_cycle;
                        $output .= '<tr>
                            <td class="align-middle col-1" rowspan=' . $count1 . '>
                                <div class="form-check px-5">
                                    <input class="form-check-input d-flex flex-column check"
                                        type="checkbox" value="" data-id="' . $element->id_cycle . '">
                                </div>
                            </td>
                            <td class="align-middle " rowspan=' . $count1 . '>
                                <div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">' . $element->nom_cycle . '</h6>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle ">
                                <span class="text-secondary text-xs font-weight-bold">' . $element->nom_filiere . '</span>
                            </td>
                            <td class="align-middle text-center" rowspan=' . $count1 . '>
                                <span class="text-secondary text-xs font-weight-bold">' . $element->seuil_v . '</span>
                            </td>
                            <td class="align-middle" rowspan=' . $count1 . '>
                                <button class="btn btn-link text-secondary mb-0 ha-edit-cycle" id="edit-' . $element->id_cycle . '" data-bs-toggle="modal"
                                    data-bs-target="#exampleModalMessage">
                                    <i class="far fa-edit fa-lg text-info"></i>
                                </button>
                            </td>
                        </tr>';
                    } else {
                        $output .= '<tr> <td class="align-middle">
                            <span class="text-secondary text-xs font-weight-bold">' . $element->nom_filiere . '</span>
                            </td></tr>';
                    }
                }
            }
            echo $output;
        }
    }

    public function info_Cycle(Request $requests)
    {
        $data = DB::select(
            'SELECT c.id_cycle,c.nom_cycle,c.seuil_v,f.id_filiere,f.nom_filiere FROM filière f,cycle c WHERE c.id_cycle=f.id_cycle AND f.id_cycle='.$requests->id_cycle
        );
        $num = 0;
        $output = "";
        $id_cy = 0;
        $nom_cy = "";
        $seuil_v = 0;
        foreach ($data as $item) {
            $id_cy = $item->id_cycle;
            $nom_cy = $item->nom_cycle;
            $seuil_v = $item->seuil_v;
            $output .= '<div class="col-md-7 mx-auto row mb-3 ha-check" id="'.$item->id_filiere.'">' .
            '<input class="form-control" type="text" style="width:94%" placeholder="Nom de Filiere" value="'.$item->nom_filiere.'" id="nom_fi' . $num .
            '" required>' .
            '<a href="javascript:;" id="btnSup' . $num .
            '" style="width:1%" class="mt-2 mx-auto close_element">' .
            '<i class="fas fa-minus-circle text-danger fa-lg"></i>' .
            '</a>' .
            '</div>';
            $num++;
        }
        return response()->json(['num' => $num, 'nom_cy' => $nom_cy, 'id_cy' => $id_cy, 'seuil_v' => $seuil_v, 'output' => $output]);
    }

    public function updateCycle(Request $request)
    {
        $request->validate([
            'nom_cy' => ['required', 'string', 'max:80'],
            'seuil_v' => ['required'],
        ]);

        DB::table('cycle')->where('id_cycle', $request->id_cycle)->update([
            'nom_cycle' => $request->nom_cy,
            'seuil_v' => $request->seuil_v,
        ]);

        DB::table('filière')->whereNotIn('id_filiere', $request->id_elements1)->where('id_cycle', $request->id_cycle)->delete();
        for ($i = 0; $i < $request->nbr_ele; $i++) {
            DB::table('filière')->upsert([
                'id_cycle' => $request->id_cycle,
                'id_filiere' => $request->id_elements1[$i],
                'nom_filiere' => $request->content[$i],
            ], ['id_filiere'], ['nom_filiere']);
        }
        return response()->json(['success' => true]);
    }

    public function insertFiliere(Request $request)
    {
        $request->validate([
            'nom_cy' => ['required', 'string', 'max:80'],
            'seuil_v' => ['required'],
        ]);

        DB::table('cycle')->insert([
            'nom_cycle' => $request->nom_cy,
            'seuil_v' => $request->seuil_v,
        ]);
        $id_cycle = DB::table('cycle')->where('nom_cycle', $request->nom_cy)->value('id_cycle');

        for ($i = 0; $i < $request->nbr_ele; $i++) {
            DB::table('filière')->insert([
                'id_cycle' => $id_cycle,
                'nom_filiere' => $request->content[$i],
            ]);
        }
        return response()->json(['success' => true]);
    }
    public function SupprimerCycle(Request $request)
    {
        DB::table('cycle')->whereIn('id_cycle', explode(",", $request->id))->delete();
        DB::table('filière')->whereIn('id_cycle', explode(",", $request->id))->delete();
        return response()->json(['success' => "Module Deleted successfully."]);
    }
    public function export()
    {
        return Excel::download(new CycleExport, 'Cycle.xlsx');
    }
    public function import(Request $request)
    {
        $imageName = 'Cycle.xlsx';
        $imageTmp = $request->file('excel');
        $imageFull = rand(100,1000000000)."_".$imageName;
        move_uploaded_file($imageTmp,"upload/".$imageFull);
        $k = "upload/".$imageFull;
        Excel::import(new CycleImport, $k);
    }
}
