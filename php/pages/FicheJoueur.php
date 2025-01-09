<?php
require('../bd/ConnexionBD.php');

// Vérifier si l'ID est fourni
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Préparer la requête pour récupérer les données du joueur
    $stmt = $linkpdo->prepare('SELECT * FROM Joueur WHERE Numero_licence = ?');
    $stmt->execute([$id]);
    $joueur = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si aucun joueur n'est trouvé
    if (!$joueur) {
        die('Joueur introuvable.');
    }

    // Suppression du joueur si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer'])) {
        // Préparer la requête pour supprimer le joueur
        $stmt = $linkpdo->prepare('DELETE FROM Joueur WHERE Numero_licence = ?');
        $stmt->execute([$id]);

        // Redirection vers la page des joueurs après suppression
        header('Location: PageJoueurs.php');
        exit; // S'assurer que le script s'arrête après la redirection
    }

    // Mise à jour des informations du joueur si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['supprimer'])) {
        // Récupérer les données envoyées
        $nom = trim($_POST['Nom']);
        $prenom = trim($_POST['Prenom']);
        $statut = $_POST['Statut'];
        $commentaire = trim($_POST['Commentaire']);

        // Vérifier que les champs obligatoires sont remplis et que le statut est valide
        if (empty($nom) || empty($prenom) || !in_array($statut, ['Actif', 'Blessé', 'Suspendu', 'Absent'])) {
            die('Erreur : Champs invalides ou incomplets.');
        }

        // Mise à jour dans la base de données
        $stmt = $linkpdo->prepare('UPDATE Joueur SET Nom = ?, Prenom = ?, Statut = ? WHERE Numero_licence = ?');
        $stmt->execute([$nom, $prenom, $statut, $id]);

        // Redirection vers la page des joueurs après mise à jour
        header('Location: PageJoueurs.php');
        exit; // S'assurer que le script s'arrête après la redirection
    }
} else {
    die('ID non fourni.');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/FicheJoueur.css" rel="stylesheet">
    <title>Fiche joueur</title>
</head>
<body>

<!-- Lien pour retourner à la liste des joueurs -->
<a class="return" href="PageJoueurs.php">Retour</a>

<h1>Fiche de <?= htmlspecialchars($joueur['Prenom']) . ' ' . htmlspecialchars($joueur['Nom']); ?></h1>

<!-- Affichage de la photo du joueur -->
<div class="photo-container">
    <img src="<?= htmlspecialchars($joueur['Photo'] ?? 'placeholder.png'); ?>" alt="Photo du joueur" class="photo">
</div>

<form method="POST">
    <label>Nom : <input type="text" name="Nom" value="<?= htmlspecialchars($joueur['Nom']); ?>"></label><br>
    <label>Prénom : <input type="text" name="Prenom" value="<?= htmlspecialchars($joueur['Prenom']); ?>"></label><br>
    <label>Statut : <br>
        <select name="Statut">
            <option value="Actif" <?= $joueur['Statut'] === 'Actif' ? 'selected' : ''; ?>>Actif</option>
            <option value="Blessé" <?= $joueur['Statut'] === 'Blessé' ? 'selected' : ''; ?>>Blessé</option>
            <option value="Suspendu" <?= $joueur['Statut'] === 'Suspendu' ? 'selected' : ''; ?>>Suspendu</option>
            <option value="Absent" <?= $joueur['Statut'] === 'Absent' ? 'selected' : ''; ?>>Absent</option>
        </select>
    </label><br>
    <button type="save">Enregistrer</button>
</form>

<!-- Formulaire pour supprimer le joueur directement -->
<form method="POST">
    <input type="hidden" name="supprimer" value="1">
    <button class="enregistrer" type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce joueur ?');">Supprimer le joueur</button>
</form>
</body>
</html>
