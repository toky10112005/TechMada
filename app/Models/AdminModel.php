<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['nom', 'email', 'mot_de_passe', 'id_genre', 'id_role'];

    protected $validationRules = [
        'nom' => 'required|min_length[2]|max_length[255]',
        'email' => 'required|valid_email',
        'mot_de_passe' => 'required|min_length[6]',
        'id_role' => 'required|integer',
    ];

    public function put(string $username, string $motDePasse, int $roleId): ?array
    {
        $saved = $this->save([
            'nom' => $username,
            'email' => $username,
            'mot_de_passe' => password_hash($motDePasse, PASSWORD_DEFAULT),
            'id_role' => $roleId,
        ]);

        if (!$saved) {
            return null;
        }

        return $this->where('email', $username)->first();

    }

    public function authenticateCredentials(string $username, string $motDePasse, int $roleId)
    {
        $admin = $this->where('email', $username)
            ->where('id_role', $roleId)
            ->first();

        if (!$admin) {
            return "username not found";
        }

        if (!password_verify($motDePasse, $admin['mot_de_passe'])) {
            return "incorrect password";
        }

        return $admin;
    }
}