<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Afficher la page de connexion
     */
    public function login()
    {
        // Si l'utilisateur est déjà connecté, rediriger vers le dashboard
        if (session()->has('user_id')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login');
    }

    /**
     * Traiter la connexion
     */
    public function authenticate()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Valider les données
        if (empty($email) || empty($password)) {
            return redirect()->back()
                            ->with('error', 'Email et mot de passe requis.')
                            ->withInput();
        }

        // Authentifier l'utilisateur
        $user = $this->userModel->authenticate($email, $password);

        if ($user) {
            // Connexion réussie - Stocker les données en session
            session()->set([
                'user_id'   => $user['id'],
                'user_name' => $user['prenom'] . ' ' . $user['nom'],
                'user_email'=> $user['email'],
                'user_role' => $user['role'],
                'user_dept' => $user['departement_id'],
                'isLoggedIn'=> true,
            ]);

            return redirect()->to('/dashboard');
        } else {
            // Connexion échouée
            return redirect()->back()
                            ->with('error', 'Identifiants incorrects. Veuillez réessayer.')
                            ->withInput();
        }
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Vous avez été déconnecté.');
    }

    /**
     * Dashboard principal (protégé par AuthFilter)
     */
    public function dashboard()
    {
        $user = [
            'id'   => session()->get('user_id'),
            'name' => session()->get('user_name'),
            'role' => session()->get('user_role'),
        ];

        return view('auth/dashboard', ['user' => $user]);
    }
}
