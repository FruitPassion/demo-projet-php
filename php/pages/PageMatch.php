<?php
require('../bd/ConnexionBD.php');

// Récupérer tous les matchs triés par date décroissante
$stmt = $linkpdo->query('SELECT Date_Match, Heure, Lieu_Rencontre, Nom_Equipe_Adverse, Resultat_Equipe, Resultat_Equipe_Adverse, Id_Match FROM Match_ ORDER BY Date_Match DESC');
$matchs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Séparer les matchs en deux catégories : futurs et passés
$matchsFuturs = [];
$matchsPasses = [];

$dateActuelle = new DateTime();

foreach ($matchs as $match) {
    $dateMatch = new DateTime($match['Date_Match']);
    if ($dateMatch >= $dateActuelle) {
        $matchsFuturs[] = $match;
    } else {
        $matchsPasses[] = $match;
    }
}
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

<h2>Matchs Futurs</h2>
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
        <?php if (empty($matchsFuturs)): ?>
            <tr><td colspan="7">Aucun match futur.</td></tr>
        <?php else: ?>
            <?php foreach ($matchsFuturs as $match): ?>
            <tr>
                <td><?= htmlspecialchars(date('d/m/Y', strtotime($match['Date_Match']))); ?></td>
                <td><?= htmlspecialchars(date('H:i', strtotime($match['Heure']))); ?></td>
                <td><?= htmlspecialchars($match['Lieu_Rencontre'] ?? 'Inconnu'); ?></td>
                <td><?= htmlspecialchars($match['Nom_Equipe_Adverse'] ?? 'Inconnu'); ?></td>
                <td><?= htmlspecialchars($match['Resultat_Equipe'] ?? '0'); ?></td>
                <td><?= htmlspecialchars($match['Resultat_Equipe_Adverse'] ?? '0'); ?></td>
                <td>
                    <a href="FicheMatch.php?id=<?= $match['Id_Match']; ?>" class="btn">Voir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<h2>Matchs Passés</h2>
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
        <?php if (empty($matchsPasses)): ?>
            <tr><td colspan="7">Aucun match passé.</td></tr>
        <?php else: ?>
            <?php foreach ($matchsPasses as $match): ?>
            <tr>
                <td><?= htmlspecialchars(date('d/m/Y', strtotime($match['Date_Match']))); ?></td>
                <td><?= htmlspecialchars(date('H:i', strtotime($match['Heure']))); ?></td>
                <td><?= htmlspecialchars($match['Lieu_Rencontre'] ?? 'Inconnu'); ?></td>
                <td><?= htmlspecialchars($match['Nom_Equipe_Adverse'] ?? 'Inconnu'); ?></td>
                <td><?= htmlspecialchars($match['Resultat_Equipe'] ?? '0'); ?></td>
                <td><?= htmlspecialchars($match['Resultat_Equipe_Adverse'] ?? '0'); ?></td>
                <td>
                    <a href="FicheMatch.php?id=<?= $match['Id_Match']; ?>" class="btn">Voir</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<a class="return" href="PageAccueil.php">Retour à l'accueil</a>

</body>
</html>
