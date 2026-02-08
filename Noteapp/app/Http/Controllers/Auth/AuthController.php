<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt for students, teachers and departments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $userTypes = ['etudiant', 'enseignant', 'departement'];
        $authenticatedGuard = null;

        foreach ($userTypes as $type) {
            // Correction : on enlève le ";" et on assigne le type si le attempt réussit
            if (Auth::guard($type)->attempt([
                'login' => $credentials['login'],
                'password' => $credentials['password']
            ], $request->boolean('remember'))) {

                $authenticatedGuard = $type;
                break; // On arrête la boucle dès qu'on a trouvé le bon guard
            }
        }

        if ($authenticatedGuard) {
            $request->session()->regenerate();

            // Redirection basée sur le guard qui a réussi
            return match ($authenticatedGuard) {
                'etudiant'    => redirect()->intended(route('etudiant.notes')),
                'enseignant'  => redirect()->intended(route('enseignant.saisie')),
                'departement' => redirect()->intended(route('departement.dashboard')),
                default       => redirect()->intended('/'),
            };
        }

        // Si on arrive ici, aucun guard n'a fonctionné
        return back()->withErrors([
            'login' => 'Les identifiants fournis ne correspondent pas à nos enregistrements.',
        ])->onlyInput('login');
    }

    /**
     * Logout the currently authenticated user (any guard) and destroy the session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Déconnexion sur tous les guards potentiels
        foreach (['etudiant', 'enseignant', 'departement'] as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        // Invalider la session et régénérer le token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirection vers la page de login
        return redirect()->route('home');
    }
}
