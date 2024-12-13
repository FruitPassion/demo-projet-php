<?php
require( '../bd/ConnexionBD.php');

$stmt = $linkpdo->query('SELECT Numero_licence, Nom, Prenom, Statut, photo FROM Joueur');
$joueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/Joueurs.css" rel="stylesheet">
    <title>Gestion des Joueurs</title>

</head>
<body>

<h1>Mes joueurs</h1>

<a href="AjouterJoueurs.php" class="btn btn-ajouter">+ Ajouter un joueur</a>

<table>
    <thead>
        <tr>
            <th>Photo</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($joueurs as $joueur): ?>
        <tr>
            <td><img src="<?= $joueur['photo'] ?: 'placeholder.png'; ?>" class="photo"></td>
            <td><?= htmlspecialchars($joueur['nom']); ?></td>
            <td><?= htmlspecialchars($joueur['prenom']); ?></td>
            <td><?= htmlspecialchars($joueur['statut']); ?></td>
            <td>
                <a href="FicheJoueur.php id=<?= $joueur['id']; ?>" class="btn">Voir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Lien pour revenir à la page principale -->
<a href="../pages/PageAcceuil.php">Retour à l'acceuil</a>

</body>
</html>
