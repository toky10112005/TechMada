<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['nom', 'email', 'mot_de_passe', 'id_genre', 'id_role', 'is_gold', 'solde_portefeuille'];

    protected $validationRules = [
        'nom' => 'required|min_length[2]|max_length[255]',
        'email' => 'required|valid_email|is_unique[users.email]',
        'mot_de_passe' => 'required|min_length[6]',
        'id_genre' => 'required|integer',
        'id_role' => 'required|integer',
    ];

    public function authenticateCredentials(string $email, string $motDePasse)
    {
        $user = $this->where('email', $email)->first();

        if (!$user) {
            return "email not found";
        }

        if (!password_verify($motDePasse, $user['mot_de_passe'])) {
            return "incorrect password";
        }

        return $user;
    }

    public function createUser(string $nom, string $email, string $motDePasse, int $genreId, int $roleId): ?array
    {
        $saved = $this->save([
            'nom' => $nom,
            'email' => $email,
            'mot_de_passe' => password_hash($motDePasse, PASSWORD_DEFAULT),
            'id_genre' => $genreId,
            'id_role' => $roleId,
        ]);

        if (!$saved) {
            return null;
        }

        return $this->where('email', $email)->first();
    }

    public function calculeIMC(int $taille, int $poids): float
    {
        return $poids / (($taille / 100) ** 2);
    }

    public function calculeMB(float $poids, float $tailleCm, int $age, string $genreLibelle): float
    {
        $base = (10 * $poids) + (6.25 * $tailleCm) - (5 * $age);
        if (stripos($genreLibelle, 'homme') !== false) {
            return $base + 5;
        }

        return $base - 161;
    }

    public function calculeMaintenance(float $mb): float
    {
        return $mb * 1.2;
    }

}