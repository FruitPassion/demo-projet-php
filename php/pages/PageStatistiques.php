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
            <h3> Nombre et pourcentage des matchs </h3>
            <table>
            <thead>
                <tr>
                    <th>Gagnés</th>
                    <th>Perdus</th>
                    <th>Nuls</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($joueurs as $joueur): ?>
                <tr>
                    <td><?= htmlspecialchars($joueur['Nom'] ?? 'Inconnu'); ?></td>
                    <td><?= htmlspecialchars($joueur['Nom'] ?? 'Inconnu'); ?></td>
                    <td><?= htmlspecialchars($joueur['Nom'] ?? 'Inconnu'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>
        <div class="deux">
            <h3> Données par joueur </h3>
            <table>
            <thead>
                <tr>
                    <th>Joueur</th>
                    <th>Status</th>
                    <th>Poste préféré</th>
                    <th>Nombre Titulaire</th>
                    <th>Remplaçant</th>
                    <th>Sélection consécutives</th>
                    <th>Moyenne évaluations</th>
                    <th>Matchs gagnés</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($joueurs as $joueur): 
                ?>
                <tr>
                <td><?= htmlspecialchars($joueur['Nom'] ?? 'Inconnu'); ?></td>
                <td><?= htmlspecialchars($joueur['Statut'] ?? 'Non défini'); ?></td>
                <td><?= htmlspecialchars($joueur['Nom'] ?? 'Inconnu'); ?></td>
                <td><?= htmlspecialchars($joueur['Nom'] ?? 'Inconnu'); ?></td>
                <td><?= htmlspecialchars($joueur['Nom'] ?? 'Inconnu'); ?></td>
                <td><?= htmlspecialchars($joueur['Nom'] ?? 'Inconnu'); ?></td>
                <td><?= htmlspecialchars($joueur['Nom'] ?? 'Inconnu'); ?></td>
                <td><?= htmlspecialchars($joueur['Nom'] ?? 'Inconnu'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>
    </div>

<a class="return" href="PageAccueil.php">Retour à l'accueil</a>

</body>
</html>