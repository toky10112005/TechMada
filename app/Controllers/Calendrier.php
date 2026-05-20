<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\CongeModel;
use DateTime;
use DatePeriod;
use DateInterval;

class Calendrier extends Controller
{
    private CongeModel $congeModel;

    public function __construct()
    {
        $this->congeModel = new CongeModel();
    }

    public function afficher($annee = null, $mois = null)
    {
        $annee = ($annee === null) ? date('Y') : (int)$annee;
        $mois  = ($mois === null)  ? date('m') : (int)$mois;

        $config = [
            'start_day'      => 'monday',
            'month_type'     => 'long',
            'day_type'       => 'short',
            'show_next_prev' => true,
            'next_prev_url'  => base_url('employes/calendrier')
        ];

        // 1. Récupérer les congés (Idéalement, filtrer par mois/année dans ton Model)
        $congesInDb = $this->congeModel->getAllWithEmployee();
        $joursEvenements = [];

        // 2. Parcourir chaque congé pour extraire TOUS les jours de la plage
        foreach ($congesInDb as $c) {
            $debut = new DateTime($c['date_debut']);
            $fin   = new DateTime($c['date_fin']);
            $fin->modify('+1 day'); // On ajoute 1 jour pour inclure la date de fin dans la boucle

            // Génère une période jour par jour entre le début et la fin du congé
            $periode = new DatePeriod($debut, new DateInterval('P1D'), $fin);

            foreach ($periode as $date) {
                // On vérifie que le jour appartient bien au mois et à l'année en cours d'affichage
                if ((int)$date->format('Y') === $annee && (int)$date->format('m') === $mois) {
                    $numJour = (int)$date->format('j'); // Récupère le jour sans le zéro initial (1 à 31)
                    
                    // Nom de l'employé (join fait dans le Model)
                    $nomComplet = trim(($c['prenom'] ?? '') . ' ' . ($c['nom'] ?? ''));
                    $nomAffiche = htmlspecialchars($nomComplet, ENT_QUOTES, 'UTF-8');

                    // On remplit le tableau. Ici, on met un lien ou du texte HTML explicatif.
                    $joursEvenements[$numJour] = '<span class="badge-conge" style="color:red; font-size:10px;">En Congé — ' . $nomAffiche . '</span>';
                }
            }
        }

        $calendar = \Config\Services::calendar($config);

        // 3. On passe le tableau formaté [Jour => Contenu] en 3ème paramètre
        $data['calendrier_html'] = $calendar->generate($annee, $mois, $joursEvenements);

        return view('employes/calendrier', $data);
    }
}