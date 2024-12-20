<?php
// Inclure la connexion à la base de données depuis le dossier "BD"
require_once __DIR__ . '../bd/connexion.php';

// Vérifier si l'ID est fourni
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Préparer la requête pour récupérer les données du joueur
    $stmt = $pdo->prepare('SELECT * FROM joueurs WHERE id = ?');
    $stmt->execute([$id]);
    $joueur = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si aucun joueur n'est trouvé
    if (!$joueur) {
        die('Joueur introuvable.');
    }
} else {
    die('ID non fourni.');
}

// Gestion de la mise à jour des données via le formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et valider les données envoyées
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $statut = $_POST['statut'];
    $commentaire = trim($_POST['commentaire']);

    // Vérifier que les champs obligatoires sont remplis et que le statut est valide
    if (empty($nom) || empty($prenom) || !in_array($statut, ['Actif', 'Blessé', 'Suspendu', 'Absent'])) {
        die('Erreur : Champs invalides ou incomplets.');
    }

    // Mise à jour dans la base de données
    $stmt = $pdo->prepare('UPDATE joueurs SET nom = ?, prenom = ?, statut = ?, commentaire = ? WHERE id = ?');
    $stmt->execute([$nom, $prenom, $statut, $commentaire, $id]);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fiche joueur</title>
</head>
<body>

<h1>Fiche de <?= htmlspecialchars($joueur['prenom']) . ' ' . htmlspecialchars($joueur['nom']); ?></h1>

<form method="POST">
    <label>Nom : <input type="text" name="nom" value="<?= htmlspecialchars($joueur['nom']); ?>"></label><br>
    <label>Prénom : <input type="text" name="prenom" value="<?= htmlspecialchars($joueur['prenom']); ?>"></label><br>
    <label>Statut :
        <select name="statut">
            <option value="Actif" <?= $joueur['statut'] === 'Actif' ? 'selected' : ''; ?>>Actif</option>
            <option value="Blessé" <?= $joueur['statut'] === 'Blessé' ? 'selected' : ''; ?>>Blessé</option>
            <option value="Suspendu" <?= $joueur['statut'] === 'Suspendu' ? 'selected' : ''; ?>>Suspendu</option>
            <option value="Absent" <?= $joueur['statut'] === 'Absent' ? 'selected' : ''; ?>>Absent</option>
        </select>
    </label><br>
    <label>Commentaire : <textarea name="commentaire"><?= htmlspecialchars($joueur['commentaire']); ?></textarea></label><br>
    <button type="submit">Enregistrer</button>
</form>

<!-- Formulaire pour supprimer le joueur -->
<form method="POST" action="supprimer_joueur.php">
    <input type="hidden" name="id" value="<?= $id; ?>">
    <button type="submit">Supprimer le joueur</button>
</form>

<!-- Lien pour retourner à la liste des joueurs -->
<a href="index.php">Retour</a>

</body>
</html>
