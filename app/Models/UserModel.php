<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'employe';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'departement_id',
        'date_embauche',
        'actif'
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Authentifier un utilisateur par email et mot de passe
     */
    public function authenticate($email, $password)
    {
        $user = $this->where('email', $email)
                     ->where('actif', 1)
                     ->first();

        if (!$user) {
            return null;
        }

        // Vérifier le mot de passe
        if (!password_verify($password, $user['password'])) {
            return null;
        }

        return $user;
    }

    /**
     * Récupérer un utilisateur par email
     */
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)
                    ->where('actif', 1)
                    ->first();
    }

    /**
     * Récupérer un utilisateur par ID
     */
    public function getUserById($id)
    {
        return $this->find($id);
    }

    /**
     * Récupérer tous les utilisateurs actifs
     */
    public function getActiveUsers()
    {
        return $this->where('actif', 1)
                    ->findAll();
    }

    /**
     * Récupérer les utilisateurs par rôle
     */
    public function getUsersByRole($role)
    {
        return $this->where('role', $role)
                    ->where('actif', 1)
                    ->findAll();
    }

    /**
     * Vérifier si un utilisateur est un Admin
     */
    public function isAdmin($userId)
    {
        $user = $this->find($userId);
        return $user && $user['role'] === 'Admin';
    }

    /**
     * Vérifier si un utilisateur est un RH
     */
    public function isRH($userId)
    {
        $user = $this->find($userId);
        return $user && $user['role'] === 'RH';
    }

    /**
     * Vérifier si un utilisateur est un Employe
     */
    public function isEmploye($userId)
    {
        $user = $this->find($userId);
        return $user && $user['role'] === 'Employe';
    }
}
