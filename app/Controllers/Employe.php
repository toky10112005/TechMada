<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EmployesModel;

class Employe extends BaseController
{
    protected EmployesModel $employesModel;

    public function __construct()
    {
        $this->employesModel = new EmployesModel();
    }

    public function index()
    {
        $session = session();
        $user = $session->get('username');

        // Si utilisateur est connecté, affiche la liste des employés
        // if ($user) {
        //     $data = [
        //         'title' => 'Liste des employes',
        //         'employes' => $this->employesModel->getEmployesList(),
        //         'totalEmployes' => $this->employesModel->countAllResults(),
        //     ];

        //     return view('employes/index', $data);
        // }

        // Sinon affiche le login
        return view('login');
    }

    public function login()
    {
        $email = (string) $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');

        if ($email === '' || $password === '') {
            return view('login', ['error' => 'Email et mot de passe requis.']);
        }

        $employe = $this->employesModel->authenticate($email, $password);

        if (! $employe) {
            return view('login', ['error' => 'Email ou mot de passe incorrect.']);
        }

        $session = session();
        $session->set('username', $employe);

        $role = (string) $employe['role'];

        return match ($role) {
            'admin' => redirect()->to('/dashboard/admin'),
            'rh' => redirect()->to('/dashboard/rh'),
            'user' => redirect()->to('/dashboard/user'),
            default => redirect()->to('/'),
        };
    }

    public function dashboardAdmin()
    {
        return view('dashboard/admin', [
            'title' => 'Dashboard Admin',
        ]);
    }

    public function dashboardRh()
    {
        return view('dashboard/rh', [
            'title' => 'Dashboard RH',
        ]);
    }

    public function dashboardUser()
    {
        return view('dashboard/user', [
            'title' => 'Dashboard Utilisateur',
        ]);
    }

    public function logout()
    {
        $session = session();
        $session->destroy();

        return redirect()->to('/login');
    }
}
