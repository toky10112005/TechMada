<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DepartementSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nom'         => 'Ressources Humaines',
                'description' => 'Gestion des ressources humaines',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nom'         => 'Informatique',
                'description' => 'Département informatique et développement',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nom'         => 'Ventes',
                'description' => 'Département commercial et ventes',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'nom'         => 'Marketing',
                'description' => 'Département marketing et communication',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('departement')->insertBatch($data);
    }
}
