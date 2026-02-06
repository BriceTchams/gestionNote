<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () { return view('welcome'); })->name('home');
Route::get('/login', function () { return view('auth.login'); })->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/etudiant', function () { return view('etudiant.dashboard'); })->name('etudiant');
Route::get('/enseignant', function () { return view('enseignant.dashboard'); })->name('enseignant');
Route::get('/departement', function () { return view('departement.dashboard'); })->name('departement');


Route::prefix('etudiant')->group(function () {
    Route::get('/dashboard', function () { return view('etudiant.dashboard'); })->name('etudiant.dashboard');
    Route::get('/notes', function () { return view('etudiant.notes'); })->name('etudiant.notes');
    Route::get('/revendications', function () { return view('etudiant.revendications'); })->name('etudiant.revendications');
})->name('etudiant');

Route::prefix('enseignant')->group(function () {
    Route::get('/', function () { return view('enseignant.dashboard'); })->name('enseignant');
    Route::get('/dashboard', function () { return view('enseignant.dashboard'); })->name('enseignant.dashboard');
    Route::get('/saisie', [\App\Http\Controllers\Enseignant\NoteController::class, 'index'])->name('enseignant.saisie');
    Route::get('/revendications', function () { return view('enseignant.revendications'); })->name('enseignant.revendications');
    Route::get('/evaluation', [\App\Http\Controllers\Enseignant\EvaluationController::class, 'index'])->name('enseignant.evaluation');
    Route::post('/evaluation', [\App\Http\Controllers\Enseignant\EvaluationController::class, 'store'])->name('enseignant.evaluation.store');
    Route::post('/notes', [\App\Http\Controllers\Enseignant\NoteController::class, 'store'])->name('enseignant.notes.store');
});


Route::prefix('departement')->group(function () {
    Route::get('/dashboard', function () { return view('departement.dashboard'); })->name('departement.dashboard');
    Route::get('/enseignants', [\App\Http\Controllers\Departement\EnseignantController::class, 'index'])->name('departement.enseignants');
    Route::post('/enseignants', [\App\Http\Controllers\Departement\EnseignantController::class, 'store'])->name('departement.enseignants.store');
    Route::put('/enseignants/{id}', [\App\Http\Controllers\Departement\EnseignantController::class, 'update'])->name('departement.enseignants.update');
    Route::delete('/enseignants/{id}', [\App\Http\Controllers\Departement\EnseignantController::class, 'destroy'])->name('departement.enseignants.destroy');
    Route::get('/filieres', [\App\Http\Controllers\Departement\FiliereController::class, 'index'])->name('departement.filieres');
    Route::post('/filieres', [\App\Http\Controllers\Departement\FiliereController::class, 'store'])->name('departement.filieres.store');
    Route::put('/filieres/{id}', [\App\Http\Controllers\Departement\FiliereController::class, 'update'])->name('departement.filieres.update');
    Route::delete('/filieres/{id}', [\App\Http\Controllers\Departement\FiliereController::class, 'destroy'])->name('departement.filieres.destroy');
    Route::post('/filieres/classes', [\App\Http\Controllers\Departement\FiliereController::class, 'storeClasse'])->name('departement.filieres.classes.store');
    Route::put('/filieres/classes/{id}', [\App\Http\Controllers\Departement\FiliereController::class, 'updateClasse'])->name('departement.filieres.classes.update');
    Route::delete('/filieres/classes/{id}', [\App\Http\Controllers\Departement\FiliereController::class, 'destroyClasse'])->name('departement.filieres.classes.destroy');
    Route::get('/etudiants', [\App\Http\Controllers\Departement\EtudiantController::class, 'index'])->name('departement.etudiants');
    Route::post('/etudiants', [\App\Http\Controllers\Departement\EtudiantController::class, 'store'])->name('departement.etudiants.store');
    Route::get('/etudiants/by-classe', [\App\Http\Controllers\Departement\EtudiantController::class, 'getEtudiantsByClasse'])->name('departement.etudiants.by-classe');
    Route::get('/etudiants/search', [\App\Http\Controllers\Departement\EtudiantController::class, 'search'])->name('departement.etudiants.search');
    Route::put('/etudiants/{id}', [\App\Http\Controllers\Departement\EtudiantController::class, 'updateEtudiant'])->name('departement.etudiants.update');
    Route::delete('/etudiants/{id}', [\App\Http\Controllers\Departement\EtudiantController::class, 'destroy'])->name('departement.etudiants.destroy');
    Route::get('/publication-pv', [\App\Http\Controllers\Departement\PvController::class, 'index'])->name('departement.pv');
    Route::post('/publication-pv', [\App\Http\Controllers\Departement\PvController::class, 'generate'])->name('departement.pv.generate');
    Route::get('/pv/classes', [\App\Http\Controllers\Departement\PvController::class, 'getClasses'])->name('departement.pv.classes');
    Route::get('/filieres/{id}/classes', [\App\Http\Controllers\Departement\FiliereController::class, 'getClassesByFiliere'])->name('departement.filieres.classes.by-filiere');

    Route::get('/filieres/classes', function () { return view('departement.filieres.classes'); })->name('departement.filieres.classes');
    Route::get('/classes', [\App\Http\Controllers\Departement\ClasseController::class, 'index'])->name('departement.classes');
    Route::post('/classes', [\App\Http\Controllers\Departement\ClasseController::class, 'store'])->name('departement.classes.store');
});