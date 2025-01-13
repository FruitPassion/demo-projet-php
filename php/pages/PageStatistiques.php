<?php
require('../bd/ConnexionBD.php');

$stmt = $linkpdo->query('SELECT Date_Match, Heure, Lieu_Rencontre, Nom_Equipe_Adverse, Resultat_Equipe, Resultat_Equipe_Adverse, Id_Match FROM Match_');
$matchs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $linkpdo->query('SELECT Numero_licence, Nom, Prenom, Statut, photo FROM Joueur');
$joueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/Statistiques.css" rel="stylesheet">
    <title>Statistiques</title>
</head>
<body>

<header>Mes Statistiques</header>

    <div class="menu-container">
        <button class="menu-button">☰</button>
        <div class="menu-content">
            <a href="PageJoueurs.php">Mes Joueurs</a>
            <a href="PageMatch.php">Mes Matchs</a>
            <a href="PageStatistiques.php">Statistiques</a>
            <a href="PageAccueil.php">Accueil</a>
        </div>
    </div>

    <div class="grid">
        <div class="un">
            <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($joueurs as $joueur): ?>
                <tr>
                    <td><?= htmlspecialchars($joueur['Nom'] ?? 'Inconnu'); ?></td>
                    <td><?= htmlspecialchars($joueur['Prenom'] ?? 'Inconnu'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>
        <div class="deux">
        <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Heure</th>
                <th>Lieu</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($matchs as $match): 
            ?>
            <tr>
                <td><?= htmlspecialchars(date('d/m/Y', strtotime($match['Date_Match'])) ?? 'Inconnu'); ?></td>
                <td><?= htmlspecialchars(date('H:i', strtotime($match['Heure'])) ?? 'Inconnu'); ?></td>
                <td><?= htmlspecialchars($match['Lieu_Rencontre'] ?? 'Inconnu'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        </table>
        </div>
        <div class="trois">
            test
        </div>
    </div>

<a class="return" href="PageAccueil.php">Retour à l'accueil</a>

</body>
</html>