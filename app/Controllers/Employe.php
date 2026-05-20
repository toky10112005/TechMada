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
        // Si pas connecté -> login
        if (! $user) {
            return redirect()->to('/login');
        }

        // Si utilisateur standard -> afficher ses demandes (employes/index remplacée par 'mes demandes')
        if (isset($user['role']) && $user['role'] === 'user') {
            $congeModel = new \App\Models\CongeModel();
            $typeModel = new \App\Models\TypeCongeModel();

            $conges = $congeModel->getByEmployee((int) $user['id']);

            $typesRaw = $typeModel->getAll();
            $types = [];
            foreach ($typesRaw as $t) {
                $types[(int) $t['id']] = $t['libelle'] ?? '';
            }

            return view('employes/index', [
                'title' => 'Mes demandes',
                'user' => $user,
                'conges' => $conges,
                'types' => $types,
                'totalRequests' => count($conges),
            ]);
        }

        // Pour les autres rôles (admin/rh) on affiche la liste des employés
        $data = [
            'title' => 'Liste des employes',
            'employes' => $this->employesModel->getEmployesList(),
            'totalEmployes' => $this->employesModel->countAllResults(),
        ];

        return view('employes/index', $data);
    }

    public function showLogin()
    {
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
        $employeModel = new \App\Models\EmployesModel();
        $congeModel = new \App\Models\CongeModel();

        // employees
        $totalEmployees = $employeModel->where('actif', 1)->countAllResults();

        // requests
        $totalRequests = $congeModel->countAllResults();
        $pending = $congeModel->where('statut', 'en_attente')->countAllResults();

        // approved this month
        $start = date('Y-m-01');
        $end = date('Y-m-t');
        $approvedThisMonth = $congeModel->where('statut', 'approuvee')
            ->where('date_demande >=', $start)
            ->where('date_demande <=', $end)
            ->countAllResults();

        // departments
        $db = \Config\Database::connect();
        $departments = $db->table('departements')->countAllResults();

        // absents today
        $today = date('Y-m-d');
        $absentConges = $congeModel->where('statut', 'approuvee')
            ->where('date_debut <=', $today)
            ->where('date_fin >=', $today)
            ->findAll();

        $absents = [];
        foreach ($absentConges as $c) {
            $emp = $employeModel->find((int)$c['employe_id']);
            if ($emp) {
                $absents[] = ['employe' => $emp, 'conge' => $c];
            }
        }

        // recent requests
        $recentRaw = $congeModel->orderBy('date_demande', 'DESC')->findAll(10);
        $recent = [];
        foreach ($recentRaw as $r) {
            $emp = $employeModel->find((int)$r['employe_id']) ?: [];
            $recent[] = ['conge' => $r, 'employe' => $emp];
        }

        return view('Admin/dashboardAdmin', [
            'title' => 'Dashboard Admin',
            'totalEmployees' => $totalEmployees,
            'totalRequests' => $totalRequests,
            'pending' => $pending,
            'approvedThisMonth' => $approvedThisMonth,
            'departments' => $departments,
            'absents' => $absents,
            'recent' => $recent,
        ]);
    }

    public function createEmploye()
    {
        $db = \Config\Database::connect();
        $departements = $db->table('departements')->orderBy('nom')->get()->getResultArray();

        return view('Admin/createEmploye', [
            'title' => 'Créer un employé',
            'departements' => $departements,
        ]);
    }

    public function storeEmploye()
    {
        $data = [
            'nom' => trim((string) $this->request->getPost('nom')),
            'prenom' => trim((string) $this->request->getPost('prenom')),
            'email' => trim((string) $this->request->getPost('email')),
            'password' => (string) $this->request->getPost('password'),
            'role' => (string) $this->request->getPost('role'),
            'departement_id' => $this->request->getPost('departement_id') !== '' ? (int) $this->request->getPost('departement_id') : null,
            'date_embauche' => $this->request->getPost('date_embauche') ?: date('Y-m-d'),
            'actif' => $this->request->getPost('actif') !== null ? (int) $this->request->getPost('actif') : 1,
        ];

        if ($data['password'] !== '') {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        if ($data['nom'] === '' || $data['prenom'] === '' || $data['email'] === '' || $this->request->getPost('password') === '') {
            return redirect()->back()->withInput()->with('error', 'Tous les champs obligatoires doivent être remplis.');
        }

        if (! in_array($data['role'], ['admin', 'rh', 'user'], true)) {
            return redirect()->back()->withInput()->with('error', 'Rôle invalide.');
        }

        if (! $this->employesModel->insert($data)) {
            return redirect()->back()->withInput()->with('error', 'Impossible de créer l\'employé.');
        }

        return redirect()->to('/dashboard/admin')->with('success', 'Employé créé avec succès.');
    }

    public function dashboardRh()
    {
        $congeModel = new \App\Models\CongeModel();
        $employeModel = new \App\Models\EmployesModel();
        $typeModel = new \App\Models\TypeCongeModel();
        $soldeModel = new \App\Models\SoldeModel();

        // basic aggregates
        $total = $congeModel->countAllResults();
        $pending = $congeModel->where('statut', 'en_attente')->countAllResults();
        $approved = $congeModel->where('statut', 'approuvee')->countAllResults();
        $refused = $congeModel->where('statut', 'refusee')->countAllResults();

        // fetch latest requests (limit 50)
        $requestsRaw = $congeModel->orderBy('date_demande', 'DESC')->findAll(50);

        $year = (int) date('Y');
        $requests = [];
        foreach ($requestsRaw as $r) {
            $emp = $employeModel->find((int) $r['employe_id']) ?: [];
            $type = $typeModel->find((int) $r['type_conge_id']) ?: ['libelle' => '---'];
            $soldeRows = $soldeModel->forEmployeeYear((int) $r['employe_id'], $year);
            $remaining = null;
            foreach ($soldeRows as $s) {
                if ((int) $s['type_conge_id'] === (int) $r['type_conge_id']) {
                    $remaining = ((int) $s['jours_attribues']) - ((int) $s['jours_pris']);
                    break;
                }
            }

            $requests[] = [
                'conge' => $r,
                'employe' => $emp,
                'type' => $type,
                'remaining' => $remaining,
            ];
        }

        return view('dashboard/rh', [
            'title' => 'Dashboard RH',
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'refused' => $refused,
            'requests' => $requests,
        ]);
    }

    public function dashboardUser()
    {
        $session = session();
        $user = $session->get('username');

        if (! $user) {
            return redirect()->to('/login');
        }

        // load CongeModel to compute metrics
        $congeModel = new \App\Models\CongeModel();
        $summary = $congeModel->getSummaryByEmployee((int) $user['id']);

        return view('dashboard/user', [
            'title' => 'Dashboard Utilisateur',
            'user' => $user,
            'conge_summary' => $summary,
        ]);
    }

    public function profileUser()
    {
        $session = session();
        $user = $session->get('username');

        if (! $user) {
            return redirect()->to('/login');
        }

        $congeModel = new \App\Models\CongeModel();
        $summary = $congeModel->getSummaryByEmployee((int) $user['id']);

        return view('dashboard/profil', [
            'title' => 'Mon profil',
            'user' => $user,
            'conge_summary' => $summary,
        ]);
    }

    public function logout()
    {
        $session = session();
        $session->destroy();

        return view('login');
    }

    public function calendrier()
    {
        return view('employes/calendrier');
    }
}
