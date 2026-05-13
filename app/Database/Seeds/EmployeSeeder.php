<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EmployeSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nom'             => 'Admin',
                'prenom'          => 'TechMada',
                'email'           => 'admin@techmada.mg',
                'password'        => password_hash('admin123', PASSWORD_BCRYPT),
                'role'            => 'Admin',
                'departement_id'  => 1,
                'date_embauche'   => date('Y-m-d'),
                'actif'           => 1,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'nom'             => 'Rakotonarielo',
                'prenom'          => 'Jean',
                'email'           => 'rh@techmada.mg',
                'password'        => password_hash('rh123', PASSWORD_BCRYPT),
                'role'            => 'RH',
                'departement_id'  => 1,
                'date_embauche'   => date('Y-m-d'),
                'actif'           => 1,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'nom'             => 'Rakoto',
                'prenom'          => 'Marie',
                'email'           => 'employe@techmada.mg',
                'password'        => password_hash('emp123', PASSWORD_BCRYPT),
                'role'            => 'Employe',
                'departement_id'  => 2,
                'date_embauche'   => date('Y-m-d'),
                'actif'           => 1,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'nom'             => 'Razafindrahona',
                'prenom'          => 'Paul',
                'email'           => 'paul.razafindrahona@techmada.mg',
                'password'        => password_hash('paul123', PASSWORD_BCRYPT),
                'role'            => 'Employe',
                'departement_id'  => 3,
                'date_embauche'   => date('Y-m-d'),
                'actif'           => 1,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
            [
                'nom'             => 'Randrianarison',
                'prenom'          => 'Sophie',
                'email'           => 'sophie.randrianarison@techmada.mg',
                'password'        => password_hash('sophie123', PASSWORD_BCRYPT),
                'role'            => 'Employe',
                'departement_id'  => 2,
                'date_embauche'   => date('Y-m-d'),
                'actif'           => 1,
                'created_at'      => date('Y-m-d H:i:s'),
                'updated_at'      => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('employe')->insertBatch($data);
    }
}
