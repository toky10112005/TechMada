<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FitspaceSeeder extends Seeder
{
    public function run(): void
    {
        $this->db->table('users')->insert([
            'nom' => 'Admin',
            'email' => 'admin@example.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    } 
}