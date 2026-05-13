<?php
namespace App\Models;

use CodeIgniter\Model;

class SoldeModel extends Model
{
    protected $table = 'soldes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['employe_id', 'type_conge_id', 'annee', 'jours_attribues', 'jours_pris'];
    protected $returnType = 'array';

    public function forEmployeeYear(int $employeId, int $year)
    {
        return $this->where(['employe_id' => $employeId, 'annee' => $year])->findAll();
    }
}
