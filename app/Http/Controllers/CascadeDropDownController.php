<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;


class CascadeDropDownController extends Controller
{
    public function GetFiliere(Request $request)
    {
        $rst = DB::table('filière')->where('id_cycle', $request->id)->get();
        return response()->json($rst);
    }
    public function GetAnnne(Request $request)
    {
        $rst = DB::select('SELECT DISTINCT année_universitaire as annee FROM inscription');
        return response()->json($rst);
    }
    public function getSection(Request $request)
    {

        if ($request->id_se <= 2) {
            $rst = DB::select('SELECT DISTINCT  LEFT(section,1) section FROM inscription
            WHERE année_universitaire LIKE "' . $request->anne . '" AND id_filiere =' . $request->id_fi );
            return response()->json($rst);
        } else {
            $rst = DB::select('SELECT DISTINCT  RIGHT(section,1) section FROM inscription
            WHERE année_universitaire LIKE "' . $request->anne . '" AND id_filiere =' . $request->id_fi );
            return response()->json($rst);
        }
    }

    public function getSection1(Request $request)
    {
        $rst = DB::select('SELECT ms.id_semestre FROM module_ds_semestre ms,elements e WHERE
        ms.id_module =e.id_module AND e.id_element='.$request->id_el);
        $id_se = $rst[0]->id_semestre ?? "";
        if ($id_se <= 2) {
            $rst = DB::select('SELECT DISTINCT  LEFT(section,1) section FROM inscription
            WHERE année_universitaire LIKE "' . $request->anne . '" AND id_filiere =' . $request->id_fi );
            return response()->json($rst);
        } else {
            $rst = DB::select('SELECT DISTINCT  RIGHT(section,1) section FROM inscription
            WHERE année_universitaire LIKE "' . $request->anne . '" AND id_filiere =' . $request->id_fi );
            return response()->json($rst);
        }
    }
    public function getSection_by_element(Request $request)
    {
        $CIN = Auth::user()->CIN;
        $anne_req = 'SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
        $annee = DB::select($anne_req);
        $anne = $annee[0]->année_universitaire ?? "";
        $rst = DB::select('SELECT DISTINCT section FROM enseinganant_de_module WHERE id_element=' . $request->id . ' AND
        id_enseignant="' . $CIN . '" AND année="' . $anne . '"');
        return response()->json($rst);
    }

    public function get_element(Request $request)
    {
        $rst = DB::select('SELECT DISTINCT e.id_element,e.nom_element FROM elements e , enseinganant_de_module em WHERE e.id_element = em.id_element AND em.année LIKE "' . $request->anne . '" AND em.id_filiere =' . $request->id_fi . '');
        return response()->json($rst);
    }
    public function get_sec(Request $request)
    {
        $rst = DB::select('SELECT DISTINCT section FROM enseinganant_de_module  WHERE année LIKE "' . $request->anne . '" AND id_element =' . $request->id_el . '');
        return response()->json($rst);
    }
    public function get_Modules(Request $request)
    {
        if ($request->anne == -1) {
            $anne_req = 'SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
            $annee = DB::select($anne_req);
            $anne = $annee[0]->année_universitaire ?? "";
            $request->anne = $anne;
        }
        $rst = DB::select('SELECT DISTINCT m.id_module,m.nom_module FROM module m,elements e,enseinganant_de_module em  WHERE m.id_module=e.id_module AND e.id_element=em.id_element AND em.année="' . $request->anne . '" AND em.id_filiere=' . $request->id_fi . ' AND em.blocker IN (3,6)');
        return response()->json($rst);
    }

    public function get_secc(Request $request)
    {
        if ($request->anne == -1) {
            $anne_req = 'SELECT année_universitaire FROM inscription ORDER BY année_universitaire DESC LIMIT 1';
            $annee = DB::select($anne_req);
            $anne = $annee[0]->année_universitaire ?? "";
            $request->anne = $anne;
        }
        $rst = DB::select('SELECT DISTINCT section FROM enseinganant_de_module
        WHERE id_element IN (SELECT id_element FROM elements WHERE id_module=' . $request->id_mo . ')
        AND année ="' . $request->anne . '"');
        return response()->json($rst);
    }
}
