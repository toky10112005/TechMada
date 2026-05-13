<?php
namespace App\Models;

use CodeIgniter\Model;

class TypeCongeModel extends Model
{
    protected $table = 'type_conges';
    protected $primaryKey = 'id';
    protected $allowedFields = ['libelle', 'description', 'jours_par_an'];
    protected $returnType = 'array';
    public function getAll()
    {
        return $this->orderBy('id')->findAll();
    }
}
