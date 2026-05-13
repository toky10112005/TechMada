<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Controllers\BaseController;
use Config\Database;


class User extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return view('user/login_test');
    }

    public function login()
    {
        $email = (string) $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');

        if ($email === '' || $password === '') {
            return view('user/login_test', ['error' => 'Email et mot de passe requis.']);
        }

        $user = $this->userModel->findByEmail($email);

        if (! $user) {
            return view('user/login_test', ['error' => 'Utilisateur introuvable.']);
        }

        $isValidPassword = password_verify($password, (string) $user['password'])
            || hash_equals((string) $user['password'], $password);

        if (! $isValidPassword) {
            return view('user/login_test', ['error' => 'Mot de passe invalide.']);
        }

        return redirect()->to('/user/db-test?email=' . urlencode($email));
    }

    public function dbTest()
    {
        $db = Database::connect();
        $email = (string) $this->request->getGet('email');

        $isConnected = $db->initialize();
        $userCount = $this->userModel->countUsers();
        $sampleUser = $this->userModel->getOneSampleUser();
        $loggedUser = $email !== '' ? $this->userModel->findByEmail($email) : null;

        return view('user/sqlite_status', [
            'connected' => $isConnected,
            'database' => $db->database,
            'userCount' => $userCount,
            'sampleUser' => $sampleUser,
            'loggedUser' => $loggedUser,
        ]);
    }
}