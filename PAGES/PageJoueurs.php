<?php
require_once '../BD/Page_ConnexionBD.php';

$stmt = $pdo->query('SELECT Numero_licence, Nom, Prenom, Statut, photo FROM joueurs');
$joueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Redirection vers la page principale
header('Location: index.php');
exit;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Joueurs</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        .photo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }
        .btn {
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        .btn-ajouter {
            background-color: #28a745;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<h1>Mes joueurs</h1>

<a href="ajouter_joueur.php" class="btn btn-ajouter">+ Ajouter un joueur</a>

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
                <a href="fiche_joueur.php?id=<?= $joueur['id']; ?>" class="btn">Voir</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
