<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CongeModel;
use App\Models\TypeCongeModel;
use App\Models\SoldeModel;

class Conge extends BaseController
{
    protected CongeModel $congeModel;

    public function __construct()
    {
        $this->congeModel = new CongeModel();
    }

    public function submit()
    {
        $session = session();
        $user = $session->get('username');

        if (! $user) {
            return redirect()->to('/login');
        }

        $employeId = (int) $user['id'];

        $data = [
            'employe_id' => $employeId,
            'type_conge_id' => (int) $this->request->getPost('type_conge_id'),
            'date_debut' => $this->request->getPost('date_debut'),
            'date_fin' => $this->request->getPost('date_fin'),
            'nb_jours' => (int) $this->request->getPost('nb_jours'),
            'motif' => $this->request->getPost('motif'),
            'statut' => 'en_attente',
            'date_demande' => date('Y-m-d'),
        ];

        // Basic validation
        if (empty($data['type_conge_id']) || empty($data['date_debut']) || empty($data['date_fin']) || $data['nb_jours'] <= 0) {
            return redirect()->back()->with('error', 'Tous les champs obligatoires doivent être remplis');
        }

        $id = $this->congeModel->createConge($data);

        if ($id > 0) {
            return redirect()->to('/dashboard/user')->with('success', 'Demande de congé soumise');
        }

        return redirect()->back()->with('error', 'Erreur lors de la création de la demande');
    }

    public function myRequests()
    {
        $session = session();
        $user = $session->get('username');

        if (! $user) {
            return redirect()->to('/login');
        }

        $employeId = (int) $user['id'];
        $conges = $this->congeModel->getByEmployee($employeId);

        return view('conges/my', ['conges' => $conges]);
    }

    public function create()
    {
        $session = session();
        $user = $session->get('username');

        if (! $user) {
            return redirect()->to('/login');
        }

        $typeModel = new TypeCongeModel();
        $types = $typeModel->getAll();

        // soldes for sidebar
        $year = (int) date('Y');
        $soldeModel = new SoldeModel();
        $soldes = $soldeModel->forEmployeeYear((int) $user['id'], $year);

        return view('employes/demande', [
            'types' => $types,
            'soldes' => $soldes,
        ]);
    }
}
