<?php

namespace App\Controllers;
use App\Models\AdminModel;
use App\Models\RoleModel;
use App\Controllers\BaseController;
use Config\Services;


class Admin extends BaseController
{

    protected $adminModel;
    protected $roleModel;
    protected $session;//Ho an ny username

    public function __construct(){
        $this->adminModel = new AdminModel();
        $this->roleModel = new RoleModel();
        $this->session = Services::session();
    }

    public function loginPage()
    {
        return view('adminlogin');
    }

    public function insertredirect(){
        return view('/Admin/Insert');
    }

    public function put(){
        $username = $this->request->getPost('username');
        $motDePasse = $this->request->getPost('password');

        $role = $this->roleModel->getByName('admin');
        if (!$role) {
            return view('adminlogin', ['error' => 'Role admin introuvable']);
        }

        $admin = $this->adminModel->put($username, $motDePasse, (int) $role['id']);

        if ($admin !== null) {
            // Enregistrement réussi
            return view('adminlogin', ['success' => 'Admin enregistré avec succès']);
        } else {
            // Enregistrement échoué
            return view('adminlogin', ['error' => 'Erreur lors de l\'enregistrement de l\'admin']);
        }
    }

     public function loginAdmin(){
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $role = $this->roleModel->getByName('admin');
        if (!$role) {
            return view('adminlogin', ['error' => 'Role admin introuvable']);
        }

        $admin = $this->adminModel->authenticateCredentials($username, $password, (int) $role['id']);

        if ($admin !== null && !is_string($admin)) {
            // Authentification réussie
            $this->session->set([
                'username' => $admin['nom'],
                'user_id'  => $admin['id'],
                'AdminLoggedIn' => true,
                'role' => 'admin'
            ]);

            return view('/Admin/dashboard');
        } else {
            // Authentification échouée
            return view('adminlogin', ['error' => $admin]); // Affiche le message d'erreur retourné par loginAdmin
        }
    }
}