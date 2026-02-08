<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () { return view('welcome'); })->name('home');
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::prefix('etudiant')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\EtudiantController::class, 'dashboard'])->name('etudiant.dashboard');
    Route::get('/notes', [\App\Http\Controllers\EtudiantController::class, 'notes'])->name('etudiant.notes');
    Route::get('/notes/download-pdf', [\App\Http\Controllers\Enseignant\NoteController::class, 'downloadPdf'])->name('etudiant.notes.download-pdf');
    Route::get('/pv/download', [\App\Http\Controllers\Departement\PvController::class, 'downloadExistingPv'])->name('etudiant.pv.download');
    Route::get('/revendications', [\App\Http\Controllers\EtudiantController::class, 'revendications'])->name('etudiant.revendications');
    Route::post('/revendications', [\App\Http\Controllers\EtudiantController::class, 'storeRevendication'])->name('etudiant.revendications.store');
})->name('etudiant');

Route::prefix('enseignant')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Enseignant\DashboardController::class, 'index'])->name('enseignant.dashboard');
    Route::get('/saisie', [\App\Http\Controllers\Enseignant\NoteController::class, 'index'])->name('enseignant.saisie');
    Route::get('/saisie/download-pdf', [\App\Http\Controllers\Enseignant\NoteController::class, 'downloadPdf'])->name('enseignant.saisie.download-pdf');
    Route::get('/revendications', [\App\Http\Controllers\Enseignant\RevendicationController::class, 'index'])->name('enseignant.revendications');
    Route::put('/revendications/{id}', [\App\Http\Controllers\Enseignant\RevendicationController::class, 'update'])->name('enseignant.revendications.update');
    Route::get('/evaluation', [\App\Http\Controllers\Enseignant\EvaluationController::class, 'index'])->name('enseignant.evaluation');
    Route::post('/evaluation', [\App\Http\Controllers\Enseignant\EvaluationController::class, 'store'])->name('enseignant.evaluation.store');
    Route::post('/notes', [\App\Http\Controllers\Enseignant\NoteController::class, 'store'])->name('enseignant.notes.store');
});


Route::prefix('departement')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Departement\DashboardController::class, 'index'])->name('departement.dashboard');

    // Enseignants
    Route::get('/enseignants', [\App\Http\Controllers\Departement\EnseignantController::class, 'index'])->name('departement.enseignants');
    Route::post('/enseignants', [\App\Http\Controllers\Departement\EnseignantController::class, 'store'])->name('departement.enseignants.store');
    Route::put('/enseignants/{id}', [\App\Http\Controllers\Departement\EnseignantController::class, 'update'])->name('departement.enseignants.update');
    Route::delete('/enseignants/{id}', [\App\Http\Controllers\Departement\EnseignantController::class, 'destroy'])->name('departement.enseignants.destroy');

    // Filières
    Route::get('/filieres', [\App\Http\Controllers\Departement\FiliereController::class, 'index'])->name('departement.filieres');
    Route::post('/filieres', [\App\Http\Controllers\Departement\FiliereController::class, 'store'])->name('departement.filieres.store');
    Route::put('/filieres/{id}', [\App\Http\Controllers\Departement\FiliereController::class, 'update'])->name('departement.filieres.update');
    Route::delete('/filieres/{id}', [\App\Http\Controllers\Departement\FiliereController::class, 'destroy'])->name('departement.filieres.destroy');
    Route::get('/filieres/{id}/classes', [\App\Http\Controllers\Departement\FiliereController::class, 'getClasses'])->name('departement.filieres.classes.by-filiere');

    // Classes
    Route::post('/filieres/classes', [\App\Http\Controllers\Departement\FiliereController::class, 'storeClasse'])->name('departement.filieres.classes.store');
    Route::put('/filieres/classes/{id}', [\App\Http\Controllers\Departement\FiliereController::class, 'updateClasse'])->name('departement.filieres.classes.update');
    Route::delete('/filieres/classes/{id}', [\App\Http\Controllers\Departement\FiliereController::class, 'destroyClasse'])->name('departement.filieres.classes.destroy');

    // Étudiants
    Route::get('/etudiants/download', [\App\Http\Controllers\Departement\EtudiantController::class, 'downloadPdf'])->name('departement.etudiants.download');
    Route::get('/etudiants/by-classe', [\App\Http\Controllers\Departement\EtudiantController::class, 'getByClasse'])->name('departement.etudiants.by-classe');
    Route::get('/etudiants/search', [\App\Http\Controllers\Departement\EtudiantController::class, 'search'])->name('departement.etudiants.search');
    Route::get('/etudiants/{id}', [\App\Http\Controllers\Departement\EtudiantController::class, 'show'])->name('departement.etudiants.show');
    Route::get('/etudiants', [\App\Http\Controllers\Departement\EtudiantController::class, 'index'])->name('departement.etudiants');
    Route::post('/etudiants', [\App\Http\Controllers\Departement\EtudiantController::class, 'store'])->name('departement.etudiants.store');
    Route::put('/etudiants/{id}', [\App\Http\Controllers\Departement\EtudiantController::class, 'updateEtudiant'])->name('departement.etudiants.update');
    Route::delete('/etudiants/{id}', [\App\Http\Controllers\Departement\EtudiantController::class, 'destroyEtudiant'])->name('departement.etudiants.destroy');

    // PV
    Route::get('/publication-pv', [\App\Http\Controllers\Departement\PvController::class, 'index'])->name('departement.pv');
    Route::post('/publication-pv', [\App\Http\Controllers\Departement\PvController::class, 'generate'])->name('departement.pv.generate');
    Route::get('/pv/classes', [\App\Http\Controllers\Departement\PvController::class, 'getClasses'])->name('departement.pv.classes');
    Route::get('/pv/historique', [\App\Http\Controllers\Departement\PvController::class, 'history'])->name('departement.pv.history');

    // UEs
    Route::get('/ues', [\App\Http\Controllers\Departement\PvController::class, 'ueIndex'])->name('departement.ues');
    Route::post('/ues', [\App\Http\Controllers\Departement\PvController::class, 'storeUe'])->name('departement.ues.store');
    Route::post('/groupes-ue', [\App\Http\Controllers\Departement\PvController::class, 'storeGroupe'])->name('departement.groupes.store');
});
