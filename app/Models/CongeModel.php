<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\Query\Builder;

class CongeModel extends Model
{
    protected $table = 'conges';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'employe_id', 'type_conge_id', 'date_debut', 'date_fin', 'nb_jours', 'motif', 'statut', 'commentaire_rh', 'date_demande', 'traite_par'
    ];

    public function createConge(array $data): int
    {
        $this->insert($data);
        return (int) $this->getInsertID();
    }

    public function getByEmployee(int $employeId): array
    {
        return $this->where('employe_id', $employeId)
            ->orderBy('date_demande', 'DESC')
            ->findAll();
    }

    public function getById(int $id): ?array
    {
        $row = $this->find($id);
        return $row ?: null;
    }

    public function updateById(int $id, array $data): bool
    {
        return (bool) $this->update($id, $data);
    }

    public function deleteById(int $id): bool
    {
        return (bool) $this->delete($id);
    }

    public function countByStatus(int $employeId, string $statut): int
    {
        return (int) $this->where(['employe_id' => $employeId, 'statut' => $statut])->countAllResults(false);
    }

    public function getSummaryByEmployee(int $employeId): array
    {
        $db = $this->db;
        $builder = $db->table($this->table);

        $counts = $builder
            ->select('statut, COUNT(*) as cnt')
            ->where('employe_id', $employeId)
            ->groupBy('statut')
            ->get()
            ->getResultArray();

        $summary = ['en_attente' => 0, 'accepte' => 0, 'refuse' => 0];
        foreach ($counts as $r) {
            $summary[$r['statut']] = (int) $r['cnt'];
        }

        // soldes: compute total remaining days across types for current year
        $year = (int) date('Y');
        $soldes = $db->table('soldes')
            ->select('type_conge_id, jours_attribues, jours_pris')
            ->where(['employe_id' => $employeId, 'annee' => $year])
            ->get()
            ->getResultArray();

        $remainingTotal = 0;
        $detail = [];
        foreach ($soldes as $s) {
            $remaining = ((int) $s['jours_attribues']) - ((int) $s['jours_pris']);
            $remainingTotal += $remaining;
            $detail[] = [
                'type_conge_id' => (int) $s['type_conge_id'],
                'remaining' => $remaining,
                'attribues' => (int) $s['jours_attribues'],
                'pris' => (int) $s['jours_pris'],
            ];
        }

        return [
            'counts' => $summary,
            'remaining_total' => $remainingTotal,
            'soldes_detail' => $detail,
        ];
    }
}
