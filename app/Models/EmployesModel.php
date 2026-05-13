<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployesModel extends Model
{
    protected $table = 'employes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'departement_id',
        'date_embauche',
        'actif',
    ];

    protected $validationRules = [
        'nom' => 'required|min_length[2]|max_length[100]',
        'prenom' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|is_unique[employes.email]',
        'password' => 'required|min_length[6]',
        'role' => 'required|in_list[admin,user,rh]',
    ];

    public function getEmployesList(): array
    {
        return $this->select('employes.id, employes.nom, employes.prenom, employes.email, employes.role, employes.date_embauche, employes.actif, departements.nom AS departement_nom')
            ->join('departements', 'departements.id = employes.departement_id', 'left')
            ->orderBy('employes.id', 'ASC')
            ->findAll();
    }
}
