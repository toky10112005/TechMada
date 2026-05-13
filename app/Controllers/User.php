<?php

namespace App\Controllers;
use App\Models\UserModel;
use App\Models\CategorieModel;
use App\Models\GenreModel;
use App\Models\RoleModel;
use App\Models\RegimeModel;
use App\Models\UserHealthProfileModel;
use App\Models\UserObjectifModel;
use App\Controllers\BaseController;
use Config\Services;


class User extends BaseController
{
    protected $userModel;
    protected $session;//Ho an ny username
    protected $categorieModel;
    protected $genreModel;
    protected $roleModel;
    protected $healthProfileModel;
    protected $userObjectifModel;
    protected $regimeModel;

    public function __construct(){
        $this->userModel = new UserModel();
        $this->categorieModel = new CategorieModel();
        $this->genreModel = new GenreModel();
        $this->roleModel = new RoleModel();
        $this->healthProfileModel = new UserHealthProfileModel();
        $this->userObjectifModel = new UserObjectifModel();
        $this->regimeModel = new regimeModel();
        $this->session = Services::session();
    }

    public function index(){
        return view('login');
    }

    public function login(){
        $email = $this->request->getPost('email');
        $mot_de_passe = $this->request->getPost('password');

        $user = $this->userModel->authenticateCredentials($email, $mot_de_passe);
        if ($user !== null && !is_string($user)) {
            // Authentification réussie
        $profile = $this->healthProfileModel->getLatestForUser((int) $user['id']);
        $IMC = null;
        if ($profile) {
            $IMC = $this->userModel->calculeIMC((int) $profile['taille'], (int) $profile['poids_actuel']);
        }

        $existingObjectif = $this->userObjectifModel->getByUserId((int) $user['id']);
        if ($existingObjectif) {
            $this->session->set([
                'username' => $user['nom'],
                'user_id'  => $user['id'],
                'isLoggedIn' => true,
                'IMC' => $IMC,
            ]);
            return redirect()->to(site_url('/objectif?objectif=' . $existingObjectif['id_objectif_type']));
        }

            return view('login', ['error' => 'Objectif manquant pour ce compte.']);
        } else {
            // Authentification échouée
            // return redirect()->back()->withInput()->with('error', 'Email or password incorrect.');
            return view('login', ['error' => $user]); // Affiche le message d'erreur retourné par authenticateCredentials
        }
    }

    public function redirectinscription(){
        return view('inscription');
    }

    public function page(){
        $nom = $this->request->getPost('username');
        $email = $this->request->getPost('email');
        $mot_de_passe = $this->request->getPost('password');
        $genre = $this->request->getPost('genre');

        $genreRow = $this->genreModel->getByLabel($genre);
        if (!$genreRow) {
            return view('inscription', ['errors' => ['Genre invalide.']]);
        }

        $this->session->set('tempusers', [
            'nom' => $nom,
            'email' => $email,
            'mot_de_passe' => $mot_de_passe,
            'id_genre' => (int) $genreRow['id'],
        ]);

        $objectifs = $this->categorieModel->getAll();
        return view('inscriptiondetails', ['objectifs' => $objectifs]);
    }

    public function put(){

        $tempusers = $this->session->get('tempusers');
        $taille = $this->request->getPost('taille');
        $poids = $this->request->getPost('poids');
        $poidsCible = $this->request->getPost('poids_cible');
        $objectifTypeId = $this->request->getPost('objectif_type');

        if (!$tempusers) {
            return view('inscription', ['errors' => ['Session invalide, veuillez recommencer.']]);
        }

        if ($poidsCible === null || $objectifTypeId === null) {
            $objectifs = $this->categorieModel->getAll();
            return view('inscriptiondetails', [
                'errors' => ['Objectif et poids cible requis.'],
                'objectifs' => $objectifs,
            ]);
        }

        $role = $this->roleModel->getByName('user');
        if (!$role) {
            $objectifs = $this->categorieModel->getAll();
            return view('inscriptiondetails', [
                'errors' => ['Role utilisateur introuvable.'],
                'objectifs' => $objectifs,
            ]);
        }

        $user = $this->userModel->createUser(
            $tempusers['nom'],
            $tempusers['email'],
            $tempusers['mot_de_passe'],
            (int) $tempusers['id_genre'],
            (int) $role['id']
        );

        if (!$user) {
            return view('inscription', ['errors' => $this->userModel->errors()]);
        }

        $profileSaved = $this->healthProfileModel->createProfile(
            (int) $user['id'],
            (float) $poids,
            (float) $taille
        );

        if (!$profileSaved) {
            $objectifs = $this->categorieModel->getAll();
            return view('inscriptiondetails', [
                'errors' => $this->healthProfileModel->errors(),
                'objectifs' => $objectifs,
            ]);
        }

        $objectifSaved = $this->userObjectifModel->setObjective(
            (int) $user['id'],
            (int) $objectifTypeId,
            (float) $poidsCible
        );

        if (!$objectifSaved) {
            $objectifs = $this->categorieModel->getAll();
            return view('inscriptiondetails', [
                'errors' => $this->userObjectifModel->errors(),
                'objectifs' => $objectifs,
            ]);
        }

        $this->session->set('username', $user['nom']);
        $this->session->set('user_id', $user['id']);

        $IMC = $this->userModel->calculeIMC((int) $taille, (int) $poids);
        $this->session->set('IMC', $IMC);

        return redirect()->to(site_url('/objectif?objectif=' . (int) $objectifTypeId));
        // $this->$regimeModel->getSuggestedRegimes($IMC, $objectifTypeId);
    } 

    // public function objectif(){
    //     $objectif = $this->request->getGet('objectif');

    //     $categories = $this->categorieModel->getAll();
    //     $categoryIds = array_column($categories, 'id');
    //     if (!in_array($objectif, $categoryIds, true)) {
    //         return view('accueil', ['IMC' => $this->session->get('IMC'), 'categories' => $categories]);
    //     }

    //     $imc = $this->session->get('IMC');
    //     $category = $this->categorieModel->getById($objectif);
    //     if (is_numeric($imc) && $category) {
    //         $imc = (float) $imc;
    //         if ($imc >= 25 && strpos($category['name'], 'Augmenter') !== false) {
    //             return view('accueil', ['IMC' => $imc, 'categories' => $categories]);
    //         }
    //         if ($imc < 18.5 && strpos($category['name'], 'Reduire') !== false) {
    //             return view('accueil', ['IMC' => $imc, 'categories' => $categories]);
    //         }
    //     }

    //     $this->session->set('objectif', $objectif);
    //     return view('accueil', ['IMC' => $this->session->get('IMC'), 'categories' => $categories]);
    // }


    public function redirectadmin(){
        return view('adminlogin');
    }

    public function valeurportefeuille(){
        $userId = (int) $this->session->get('user_id');
        if (!$userId) {
            return redirect()->to('/');
        }

        $user = $this->userModel->find($userId);
        if (!$user) {
            return redirect()->to('/');
        }

        return view('portefeuille', ['solde' => $user['solde_portefeuille']]);
    }

    // public function Gold(){ 
    //     $userId = (int) $this->session->get('user_id');
    //     if (!$userId) {
    //         return redirect()->to('/');
    //     }

    //     $user = $this->userModel->find($userId);
    //     if (!$user) {
    //         return redirect()->to('/');
    //     }

    //     return view('gold', ['solde' => $user['solde_portefeuille']]);
    // }

   
}