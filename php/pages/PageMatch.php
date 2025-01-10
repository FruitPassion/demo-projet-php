<?php
require('../bd/ConnexionBD.php');

$stmt = $linkpdo->query('SELECT Date_Match, Heure, Lieu_Rencontre, Nom_Equipe_Adverse, Resultat_Equipe, Resultat_Equipe_Adverse FROM Match_');
$matchs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/Match.css" rel="stylesheet">
    <title>Gestion des Joueurs</title>
</head>
<body>

<header>Mes Matchs</header>

<a href="AjouterMatch.php" class="btn btn-ajouter">+ Ajouter un match</a>

<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>Heure</th>
            <th>Lieu</th>
            <th>Equipe Adverse</th>
            <th>Score</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($matchs as $match): 
        $Date_Match = $date->format('d/m/Y'); 
        $Heure = $time->format('H:i');
        ?>
        <tr>
            <td><?= htmlspecialchars($joueur['Date_Match'] ?? 'Inconnu'); ?></td>
            <td><?= htmlspecialchars($joueur['Heure'] ?? 'Inconnu'); ?></td>
            <td><?= htmlspecialchars($joueur['Lieu_Rencontre'] ?? 'Inconnu'); ?></td>
            <td><?= htmlspecialchars($joueur['Nom_Equipe_Adverse'] ?? 'Inconnu'); ?></td>
            <td><?= htmlspecialchars($joueur['Nom_Equipe_Adverse'] ?? 'Inconnu'); ?></td>
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