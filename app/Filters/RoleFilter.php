<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // $arguments contient le(s) rôle(s) autorisé(s)
        // ex: ['Admin'] ou ['Admin', 'RH']
        if (!session()->has('user_id')) {
            return redirect()->to('/login');
        }
        
        $user_role = session()->get('user_role');
        
        if ($arguments && !in_array($user_role, $arguments ?? [])) {
            return redirect()->to('/')->with('error', 'Accès refusé : droits insuffisants');
        }
        
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien à faire après
    }
}