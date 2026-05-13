<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['nom', 'email', 'password', 'role'];

    protected $validationRules = [
        'nom' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]',
        'role' => 'required|in_list[user,admin,rh]',
    ];

    public function findByEmail(string $email): ?array
    {
        $user = $this->where('email', $email)->first();

        return $user ?: null;
    }

    public function countUsers(): int
    {
        return (int) $this->countAllResults(false);
    }

    public function getOneSampleUser(): ?array
    {
        $user = $this->select('id, nom, email, role')->orderBy('id', 'ASC')->first();

        return $user ?: null;
    }

}