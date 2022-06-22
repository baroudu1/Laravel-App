<?php

namespace App\Http\Controllers;

use App\Rules\MatchOldPassword;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Models\User;



use Illuminate\Support\Facades\DB;

class ControllerProfile extends Controller
{
    public function show($menu = 'Profil')
    {
        $name = $menu;
        $nom = Auth::user()->nom;
        $prenom = Auth::user()->prenom;
        $email = Auth::user()->email;
        $CIN = Auth::user()->CIN;
        $poste = null;
        $depp = null;
        $fill = null;
        $admin = DB::select('select  CIN from admin where CIN=:cin', ['cin' => $CIN]);
        $coordinateur = DB::select('select id_departement ,coordinateur from enseignant where CIN=:cin ', ['cin' => $CIN]);
        foreach ($admin as $keye) {
            $admin = $keye->CIN;
        }
        //dd($admin);
        foreach ($coordinateur as $keeye) {
            $coordinateur = $keeye->coordinateur;
            $dep = $keeye->id_departement;
        }
        if ($admin != []) {
            $poste = -1;
        } else if ($coordinateur != []) {
            $poste = $coordinateur;
            $dep = DB::select('select  nom_departement from département where id_departement=:id_departement', ['id_departement' => $dep]);
            foreach ($dep as $keye) {
                $depp = $keye->nom_departement;
            }
            if ($coordinateur > 0) {
                $fil = DB::select('select nom_filiere from filière where id_filiere=:id_filiere', ['id_filiere' => $coordinateur]);
                foreach ($fil as $keye) {
                    $fill = $keye->nom_filiere;
                }
            }
        } else {
            $poste = -2;
        }
        view('inc.nav');
        return view('profile', compact('name', 'nom', 'prenom', 'email', 'poste', 'depp', 'fill'));
    }
    public function changePass(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'New_Password' => ['required'],
            'Confirm_Password' => ['same:New_Password'],
        ]);

        $hashedPassword = Auth::user()->password;

        if (Hash::check($request->current_password, $hashedPassword)) {

            User::find(Auth::user()->id)->update(['password' => Hash::make($request->New_Password)]);
            return response()->json(['ha'=>true ,'message' => 'Mot de passe mis à jour avec succès']);
        } else {
            return response()->json(['ha'=>false ,'message' => 'l\'ancien mot de passe ne correspond pas. Veuillez réessayer']);
        }
    }
}
