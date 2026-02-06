<?php

namespace App\Http\Controllers;

class EtudiantController extends Controller
{
    public function dashboard()
    {
        return view('etudiant.dashboard');
    }

    public function notes()
    {
        return view('etudiant.notes');
    }

    public function revendications()
    {
        return view('etudiant.revendications');
    }
}