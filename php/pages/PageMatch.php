<?php
require('../bd/ConnexionBD.php');

$stmt = $linkpdo->query('SELECT Date_Match, Heure, Lieu_Rencontre, Nom_Equipe_Adverse, Resultat_Equipe, Resultat_Equipe_Adverse, Id_Match FROM Match_');
$matchs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/Match.css" rel="stylesheet">
    <title>Gestion des Matchs</title>
</head>
<body>

<header>Mes Matchs</header>

    <div class="menu-container">
        <button class="menu-button">☰</button>
        <div class="menu-content">
            <a href="PageJoueurs.php">Mes Joueurs</a>
            <a href="PageMatch.php">Mes Matchs</a>
            <a href="PageStatistiques.php">Statistiques</a>
            <a href="PageAccueil.php">Accueil</a>
        </div>
    </div>

<a href="AjouterMatch.php" class="btn btn-ajouter">+ Ajouter un match</a>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Heure</th>
            <th>Lieu</th>
            <th>Equipe Adverse</th>
            <th>Score Equipe</th>
            <th>Score Adverse</th>
            <th>Fiche</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($matchs as $match): 
        ?>
        <tr>
            <td><?= htmlspecialchars(date('d/m/Y', strtotime($match['Date_Match'])) ?? 'Inconnu'); ?></td>
            <td><?= htmlspecialchars(date('H:i', strtotime($match['Heure'])) ?? 'Inconnu'); ?></td>
            <td><?= htmlspecialchars($match['Lieu_Rencontre'] ?? 'Inconnu'); ?></td>
            <td><?= htmlspecialchars($match['Nom_Equipe_Adverse'] ?? 'Inconnu'); ?></td>
            <td><?= htmlspecialchars($match['Resultat_Equipe'] ?? 'Inconnu'); ?></td>
            <td><?= htmlspecialchars($match['Resultat_Equipe_Adverse'] ?? 'Inconnu'); ?></td>
            <td>
                <a href="FicheMatch.php?id=<?= $match['Id_Match']; ?>" class="btn">Voir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a class="return" href="PageAccueil.php">Retour à l'accueil</a>

</body>
</html>