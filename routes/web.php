<?php

use App\Http\Controllers\ControllerGenerePV;
use App\Http\Controllers\ControllergestionEnseignant;
use App\Http\Controllers\ControllergestionEtudiant;
use App\Http\Controllers\ControllerGestionModule;
use App\Http\Controllers\ControllerGestionNote;
use App\Http\Controllers\ControllerProfile;
use App\Http\Controllers\getDirection;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ControllerRegister;
use App\Http\Controllers\CascadeDropDownController;
use App\Http\Controllers\AffectationController;
use App\Http\Controllers\ControllerremplirNotes;
use App\Http\Controllers\ControllerGestionFiliere;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|

Route::get('dashboard/{title?}', [getDirection::class,'getname'])->name('getname');

 */

Route::get('/', function () {
    return redirect('dashboard');
});
Route::group(['middleware' => ['auth', 'verified']], function () {

    Route::group(['middleware' => ['admin']], function () {

        Route::get('dashboard/gestionModule', [ControllerGestionModule::class, 'show'])->name('gestionModule');

        Route::get('dashboard/gestionFiliere', [ControllerGestionFiliere::class, 'show'])->name('gestionFiliere');

        Route::post('dashboard/gestionFiliere/getCycle', [ControllerGestionFiliere::class, 'getCycle'])->name('getCycle.hamza');

        Route::post('dashboard/gestionFiliere/info_Cycle', [ControllerGestionFiliere::class, 'info_Cycle'])->name('info_Cycle.hamza');

        Route::post('dashboard/gestionFiliere/updateCycle', [ControllerGestionFiliere::class, 'updateCycle'])->name('updateCycle.hamza');

        Route::post('dashboard/gestionFiliere/insertFiliere', [ControllerGestionFiliere::class, 'insertFiliere'])->name('insertFiliere.hamza');

        Route::post('dashboard/gestionFiliere/SupprimerCycle', [ControllerGestionFiliere::class, 'SupprimerCycle'])->name('SupprimerCycle.hamza');

        Route::get('dashboard/gestionFiliere/exportFiliere', [ControllerGestionFiliere::class, 'export'])->name('exportFiliere.hamza');

        Route::post('dashboard/gestionFiliere/importFiliere', [ControllerGestionFiliere::class, 'import'])->name('importFiliere');

        Route::post('gestionEnseignant/modifierStatutsst', [ControllerGestionNote::class, 'modifierStatutsst'])->name('modifierStatutsst.hamza');


        Route::post('dashboard/gestionModule/insertModule', [ControllerGestionModule::class, 'insertModule'])->name('insertModule.hamza');

        Route::post('dashboard/gestionModule/getModule', [ControllerGestionModule::class, 'getModule'])->name('getModule.hamza');

        Route::post('dashboard/gestionModule/getInfoModule', [ControllerGestionModule::class, 'getInfoModule'])->name('getInfoModule.hamza');

        Route::post('dashboard/gestionModule/SupprimerModule', [ControllerGestionModule::class, 'SupprimerModule'])->name('SupprimerModule.hamza');

        Route::post('dashboard/gestionModule/info_Module', [ControllerGestionModule::class, 'info_Module'])->name('info_Module.hamza');

        Route::post('dashboard/gestionModule/updateModule', [ControllerGestionModule::class, 'updateModule'])->name('updateModule.hamza');

        Route::get('dashboard/gestionNote', [ControllerGestionNote::class, 'show'])->name('gestionNote');

        Route::get('dashboard/gestionNote/getrequests', [ControllerGestionNote::class, 'getrequests'])->name('getrequests.hamza');

        Route::post('dashboard/gestionNote/get_note', [ControllerGestionNote::class, 'get_note'])->name('get_note.hamza');

        Route::post('dashboard/gestionNote/get_notes', [ControllerGestionNote::class, 'get_notes'])->name('get_notes.hamza');

        Route::post('dashboard/gestionNote/modifierStatut', [ControllerGestionNote::class, 'modifierStatut'])->name('modifierStatuts.hamza');

        Route::post('dashboard/gestionNote/modifierStatutee', [ControllerGestionNote::class, 'modifierStatutee'])->name('modifierStatutee.hamza');

        Route::get('dashboard/gestionNote/export', [ControllerGestionModule::class, 'export'])->name('exportModule.hamza');

        Route::post('dashboard/gestionNote/importModule', [ControllerGestionModule::class, 'import'])->name('importModule');

        Route::get('dashboard/gestionEtudiant', [ControllergestionEtudiant::class, 'show'])->name('gestionEtudiant');

        Route::post('dashboard/gestionEtudiant/upload', [ControllergestionEtudiant::class, 'upload'])->name('upload.hamza');

        Route::post('dashboard/gestionEtudiant/getEtudiant', [ControllergestionEtudiant::class, 'getEtudiant'])->name('getEtudiant.hamza');

        Route::post('dashboard/gestionEtudiant/getInfoEtudiant', [ControllergestionEtudiant::class, 'getInfoEtudiant'])->name('getInfoEtudiant.hamza');

        Route::post('dashboard/gestionEtudiant/SupprimerEtudiant', [ControllergestionEtudiant::class, 'SupprimerEtudiant'])->name('SupprimerEtudiant.hamza');

        Route::post('dashboard/gestionEtudiant/fetchInfoEtudiant', [ControllergestionEtudiant::class, 'fetchInfoEtudiant'])->name('fetchInfoEtudiant.hamza');

        Route::post('dashboard/gestionEtudiant/insert_update_etudiant', [ControllergestionEtudiant::class, 'insert_update_etudiant'])->name('insert_update_etudiant.hamza');

        Route::post('dashboard/gestionEtudiant/exportEtudients', [ControllergestionEtudiant::class, 'exportEtudients'])->name('exportEtudients.hamza');

        Route::get('dashboard/gestionEnseignant', [ControllergestionEnseignant::class, 'show'])->name('gestionEnseignant');

        Route::post('dashboard/gestionEnseignant/insertEnseignant', [ControllergestionEnseignant::class, 'insertEnseignant'])->name('insertEnseignant.hamza');

        Route::get('dashboard/gestionEnseignant/exportEnseignat', [ControllergestionEnseignant::class, 'export'])->name('exportEnseignat.hamza');

        Route::post('dashboard/gestionEnseignant/suppEnseignant', [ControllergestionEnseignant::class, 'suppEnseignant'])->name('suppEnseignant.hamza');

        Route::post('dashboard/gestionEnseignant/UpdateEnseignant', [ControllergestionEnseignant::class, 'UpdateEnseignant'])->name('UpdateEnseignant.hamza');

        Route::post('dashboard/gestionEnseignant/getEnseignant', [ControllergestionEnseignant::class, 'showelement'])->name('showelement');

        Route::post('dashboard/gestionEnseignant/getEnseignants', [ControllergestionEnseignant::class, 'getEnseignants'])->name('getEnseignants');

        Route::post('gestionEnseignant/get_element', [CascadeDropDownController::class, 'get_element'])->name('element.hamza');

        Route::post('gestionEtudiant/GetAnnne', [CascadeDropDownController::class, 'GetAnnne'])->name('GetAnnne.hamza');

        Route::post('gestionEnseignant/get_sec', [CascadeDropDownController::class, 'get_sec'])->name('sectionn.hamza');

        Route::post('dashboard/gestionNote/importEnseigant', [ControllergestionEnseignant::class, 'import'])->name('importEnseigant');
    });

    Route::group(['middleware' => ['eneignant']], function () {

        Route::get('dashboard/remplirNotes', [ControllerremplirNotes::class, 'show'])->name('remplirNotes');

        Route::post('dashboard/exportNotes', [ControllerremplirNotes::class, 'export'])->name('exportNotes.hamza');

        Route::post('dashboard/importNotes', [ControllerremplirNotes::class, 'importNotes'])->name('importNotes.hamza');

        Route::post('gestionEnseignant/getSection_by_element', [CascadeDropDownController::class, 'getSection_by_element'])->name('getSection_by_element');

        Route::post('gestionEnseignant/fetchNotes', [ControllerremplirNotes::class, 'fetch'])->name('fetchNotes.hamza');

        Route::post('gestionEnseignant/updateNotes', [ControllerremplirNotes::class, 'updateNotes'])->name('updateNotes.hamza');

        Route::post('gestionEnseignant/modifierStatut', [ControllerremplirNotes::class, 'modifierStatut'])->name('modifierStatut.hamza');


        Route::post('dashboard/puissance1', [getDirection::class, 'fill_progress1'])->name('puissance1');


        Route::group(['middleware' => ['coordinateur']], function () {

            Route::get('dashboard/affectation', [AffectationController::class, 'show'])->name('affectation');

            Route::post('dashboard/get_en_by_dep', [AffectationController::class, 'getenbydep'])->name('GetEnseignant_by_Dep.hamza');

            Route::post('dashboard/GetAffectationInfo', [AffectationController::class, 'GetAffectationInfo'])->name('GetAffectationInfo.hamza');

            Route::post('dashboard/GetAffectation', [AffectationController::class, 'GetAffectation'])->name('GetAffectation.hamza');

            Route::post('dashboard/AjouterAffectation', [AffectationController::class, 'AjouterAffectation'])->name('AjouterAffectation.hamza');

            Route::post('dashboard/SuppAffectation', [AffectationController::class, 'SuppAffectation'])->name('SuppAffectation.hamza');

            Route::post('dashboard/importAffectation', [AffectationController::class, 'import'])->name('importAffectation');

            Route::get('dashboard/exportAffectation', [AffectationController::class, 'export'])->name('exportAffectation');

            Route::post('gestionEnseignant/getSectionn', [CascadeDropDownController::class, 'getSection1'])->name('getSection1.hamza');
        });
    });

    Route::get('dashboard', [getDirection::class, 'show'])->name('dashboard');

    Route::get('dashboard/generePV', [ControllerGenerePV::class, 'show'])->name('generePV');

    Route::get('dashboard/profile', [ControllerProfile::class, 'show'])->name('profile');

    Route::post('gestionEnseignant/GetFiliere', [CascadeDropDownController::class, 'GetFiliere'])->name('GetSubCatAgainstMainCatEdit');

    Route::post('dashboard/puissance', [getDirection::class, 'fill_progress'])->name('puissance');

    Route::post('gestionEnseignant/get_Modules', [CascadeDropDownController::class, 'get_Modules'])->name('get_Modules.hamzaa');

    Route::post('gestionEnseignant/get_secc', [CascadeDropDownController::class, 'get_secc'])->name('get_secc.hamzaa');

    Route::post('gestionEnseignant/getSection', [CascadeDropDownController::class, 'getSection'])->name('getSection');

    Route::post('dashboard/generePV/pv_semestre', [ControllerGenerePV::class, 'PV_Semestre'])->name('PV_Semestre');

    Route::post('dashboard/generePV/pv_filiere', [ControllerGenerePV::class, 'PV_Filiere'])->name('PV_Filiere');

    Route::post('dashboard/generePV/get_Semestre', [ControllerGenerePV::class, 'get_Semestre'])->name('get_Semestre.hamzaa');

    Route::post('dashboard/generePV/PV_module', [ControllerGenerePV::class, 'PV_module'])->name('PV_module.hamzaa');

    Route::post('dashboard/profile/change-password', [ControllerProfile::class, 'changePass'])->name('change.password1');
});


Route::get('logout', [LogoutController::class, 'logout'])->name('logout');
Route::get('register', function () {
    return view('policy');
})->name('register');
Route::post('register/info', [ControllerRegister::class, 'registe'])->name('register.info');
