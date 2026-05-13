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
        $data = [
            'title' => 'Liste des employes',
            'employes' => $this->employesModel->getEmployesList(),
            'totalEmployes' => $this->employesModel->countAllResults(),
        ];

        return view('employes/index', $data);
    }
}
