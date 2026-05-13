<?php

namespace App\Controllers;

use App\Models\CodeRechargeModel;
use App\Models\UserModel;
use App\Controllers\BaseController;
use Config\Services;


class CodeRecharge extends BaseController
{
    protected $codeRechargeModel;
    protected $session;//Ho an ny username
    protected $userModel;

     public function __construct(){
        $this->codeRechargeModel = new CodeRechargeModel();
        $this->session = Services::session();
        $this->userModel = new UserModel();
    }

     public function valideCode(){
        $code = trim((string) $this->request->getPost('code'));
        $userId = (int) $this->session->get('user_id');
        $isAjax = $this->request->isAJAX();

        if (!$userId) {
            if ($isAjax) {
                return $this->response->setStatusCode(401)->setJSON([
                    'success' => false,
                    'message' => 'Session expirée, veuillez vous reconnecter.',
                ]);
            }

            return redirect()->to('/');
        }

        $codeData = $this->codeRechargeModel->validerCode($code);
        $user = $this->userModel->find($userId);
        $solde = $user['solde_portefeuille'] ?? 0;
        $csrfName = csrf_token();
        $csrfHash = csrf_hash();

        if ($codeData !== false) {
            $nouveauSolde = (float) $solde + (float) $codeData;
            $this->userModel->update($userId, ['solde_portefeuille' => $nouveauSolde]);

            if ($isAjax) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Code valide ! Votre solde a été mis à jour.',
                    'solde' => $nouveauSolde,
                    'csrfName' => $csrfName,
                    'csrfHash' => $csrfHash,
                ]);
            }

            // return redirect()->to('/portefeuille');
            return view('portefeuille', ['success' => 'Code valide ! Votre solde a été mis à jour.', 'solde' => $nouveauSolde]);
        } else {
            if ($isAjax) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'message' => 'Code invalide ou déjà utilisé.',
                    'solde' => $solde,
                    'csrfName' => $csrfName,
                    'csrfHash' => $csrfHash,
                ]);
            }

            return view('portefeuille', ['error' => 'Code invalide ou déjà utilisé.', 'solde' => $solde]);
        }
     }
}