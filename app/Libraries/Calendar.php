<?php

namespace App\Libraries;

/**
 * Classe Calendar pour générer un calendrier HTML
 */
class Calendar
{
    protected $config = [];

    public function __construct($config = [])
    {
        $this->config = array_merge([
            'start_day'      => 'sunday',
            'month_type'     => 'long',
            'day_type'       => 'short',
            'show_next_prev' => true,
            'next_prev_url'  => '',
        ], $config);
    }

    /**
     * Génère un calendrier HTML
     *
     * @param int $annee L'année
     * @param int $mois  Le mois (1-12)
     * @return string HTML du calendrier
     */
    public function generate($annee, $mois, array $data = [])
    {
        // Validation des paramètres
        $annee = (int)$annee;
        $mois = (int)$mois;

        if ($mois < 1 || $mois > 12) {
            $mois = date('m');
        }

        // Premier et dernier jour du mois
        $premier_jour = mktime(0, 0, 0, $mois, 1, $annee);
        $dernier_jour = date('t', $premier_jour);
        $jour_semaine_debut = date('w', $premier_jour);

        // Ajustement du jour de départ si 'monday'
        if ($this->config['start_day'] === 'monday') {
            $jour_semaine_debut = ($jour_semaine_debut === 0) ? 6 : $jour_semaine_debut - 1;
        }

        // Noms des jours et mois
        $jours_court = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $jours_long = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $mois_long = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        $jours = ($this->config['day_type'] === 'short') ? $jours_court : $jours_long;
        $nom_mois = $this->config['month_type'] === 'long' ? $mois_long[$mois] : str_pad($mois, 2, '0', STR_PAD_LEFT);

        // Construction HTML
        $html = '<table class="calendar" border="1" cellpadding="10" cellspacing="0" style="width: 100%; border-collapse: collapse;">' . "\n";
        
        // En-têtes du mois
        $html .= '<tr><td colspan="7" style="text-align: center; font-weight: bold; padding: 15px;">';
        
        if ($this->config['show_next_prev']) {
            $mois_prev = ($mois === 1) ? 12 : $mois - 1;
            $annee_prev = ($mois === 1) ? $annee - 1 : $annee;
            $mois_suiv = ($mois === 12) ? 1 : $mois + 1;
            $annee_suiv = ($mois === 12) ? $annee + 1 : $annee;

            $url_base = $this->config['next_prev_url'];
            $html .= '<a href="' . $url_base . '/' . $annee_prev . '/' . $mois_prev . '">&laquo; Prev</a> | ';
        }
        
        $html .= $nom_mois . ' ' . $annee;
        
        if ($this->config['show_next_prev']) {
            $url_base = $this->config['next_prev_url'];
            $html .= ' | <a href="' . $url_base . '/' . $annee_suiv . '/' . $mois_suiv . '">Next &raquo;</a>';
        }
        
        $html .= '</td></tr>' . "\n";

        // En-têtes des jours
        $html .= '<tr style="background-color: #f2f2f2; font-weight: bold;">' . "\n";
        if ($this->config['start_day'] === 'monday') {
            foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $jour) {
                $html .= '<td style="text-align: center; padding: 10px;">' . $jour . '</td>' . "\n";
            }
        } else {
            foreach ($jours as $jour) {
                $html .= '<td style="text-align: center; padding: 10px;">' . (($this->config['day_type'] === 'short') ? substr($jour, 0, 3) : $jour) . '</td>' . "\n";
            }
        }
        $html .= '</tr>' . "\n";

        // Jours du mois
        $html .= '<tr>' . "\n";

        // Espaces vides avant le premier jour
        for ($i = 0; $i < $jour_semaine_debut; $i++) {
            $html .= '<td style="background-color: #f9f9f9;"></td>' . "\n";
        }

        // Jours du mois
        $jour = 1;
        $colonne = $jour_semaine_debut;

        while ($jour <= $dernier_jour) {
            if ($colonne > 0 && ($colonne % 7) === 0 && $jour < $dernier_jour) {
                $html .= '</tr><tr>' . "\n";
            }

            // Si un événement est défini pour ce jour, on ajoute le contenu et une classe
            $cellContent = '';
            $cellStyle = '';
            $cellClass = '';

            if (isset($data[$jour])) {
                // Si l'utilisateur a fourni du HTML, on l'affiche en dessous du numéro
                $cellContent = '<div class="event-content">' . $data[$jour] . '</div>';
                $cellClass = ' has-event';
                $cellStyle = 'background-color: #fff0f0;';
            }

            $html .= '<td class="day' . $cellClass . '" style="text-align: center; padding: 10px; height: 80px; vertical-align: top; ' . $cellStyle . '">';
            $html .= '<div class="day-number" style="font-weight: bold; margin-bottom:6px;">' . $jour . '</div>';
            $html .= $cellContent;
            $html .= '</td>' . "\n";

            $jour++;
            $colonne++;
        }

        // Espaces vides après le dernier jour
        while (($colonne % 7) !== 0) {
            $html .= '<td style="background-color: #f9f9f9;"></td>' . "\n";
            $colonne++;
        }

        $html .= '</tr>' . "\n";
        $html .= '</table>' . "\n";

        return $html;
    }
}
