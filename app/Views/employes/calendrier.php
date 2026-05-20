<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Calendrier CodeIgniter 4</title>
    <style>
        /* Style pour rendre le tableau généré par CI4 joli */
        table.calendar {
            width: 100%;
            max-width: 500px;
            margin: 20px auto;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        /* En-tête (Mois / Année et boutons de navigation) */
        table.calendar th.heading {
            background-color: #007bff;
            color: white;
            padding: 15px;
            font-size: 1.2rem;
        }
        table.calendar th.heading a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        /* Jours de la semaine (Lun, Mar...) */
        table.calendar tr:nth-child(2) th {
            background-color: #f8f9fa;
            color: #495057;
            padding: 10px;
            border-bottom: 2px solid #dee2e6;
        }

        /* Cases des jours */
        table.calendar td {
            width: 14%;
            height: 50px;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #dee2e6;
            background-color: #fff;
        }

        /* Cases vides */
        table.calendar td:empty {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>

    <div style="text-align: center; margin-top: 50px;">
        <h1>Mon Calendrier Dynamique</h1>
        
        <!-- Affichage direct du calendrier généré par CodeIgniter -->
        <?= $calendrier_html ?>
    </div>

</body>
</html>