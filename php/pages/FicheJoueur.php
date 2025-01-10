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

    // Préparer la requête pour récupérer tous les commentaires du joueur
    $stmt = $linkpdo->prepare('SELECT texte, date_commentaire FROM Commentaire WHERE numero_licence = ? ORDER BY date_commentaire DESC');
    $stmt->execute([$id]);
    $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format de la date : "DD-MM-YYYY / Hour:minutes"
    $formatted_date = date("d-m-Y / H:i");
    $linkpdo->exec("SET time_zone = '+01:00'");


    // Suppression du joueur si le formulaire de suppression est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer'])) {
        $stmt = $linkpdo->prepare('DELETE FROM Commentaire WHERE numero_licence = ?');
        $stmt->execute([$id]);

        $stmt = $linkpdo->prepare('DELETE FROM Joueur WHERE Numero_licence = ?');
        $stmt->execute([$id]);

        header('Location: PageJoueurs.php');
        exit;
    }

    // Mise à jour des informations du joueur si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $nom = trim($_POST['Nom']);
        $prenom = trim($_POST['Prenom']);
        $statut = $_POST['Statut'];
        $date_naissance = $_POST['date_naissance'];
        $poids = $_POST['poids'];
        $taille = $_POST['taille'];

        if (empty($nom) || empty($prenom) || !in_array($statut, ['Actif', 'Blessé', 'Suspendu', 'Absent'])) {
            die('Erreur : Champs invalides ou incomplets.');
        }

        $stmt = $linkpdo->prepare('UPDATE Joueur SET Nom = ?, Prenom = ?, Statut = ?, date_naissance = ?, poids = ?, taille = ? WHERE Numero_licence = ?');
        $stmt->execute([$nom, $prenom, $statut, $date_naissance, $poids, $taille, $id]);

        header('Location: PageJoueurs.php');
        exit;
    }

    // Ajout d'un nouveau commentaire si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
        $nouveau_commentaire = trim($_POST['Commentaire']);
        if (!empty($nouveau_commentaire)) {
            $stmt = $linkpdo->prepare('INSERT INTO Commentaire (texte, numero_licence, date_commentaire) VALUES (?, ?, NOW())');
            $stmt->execute([$nouveau_commentaire, $id]);
        }

        header('Location: FicheJoueur.php?id=' . $id);
        exit;
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

<h1>Fiche de <?= htmlspecialchars($joueur['Prenom']) . ' ' . htmlspecialchars($joueur['Nom']); ?></h1>

<div><a class="return" href="PageJoueurs.php">Retour</a></div>

<div class="photo-container">
    <img src="<?= htmlspecialchars($joueur['Photo'] ?? 'placeholder.png'); ?>" alt="Photo du joueur" class="photo">
</div>

<form method="POST">
    <label>Nom : <br><input type="text" name="Nom" value="<?= htmlspecialchars($joueur['Nom']); ?>"></label><br>
    <label>Prénom : <br><input type="text" name="Prenom" value="<?= htmlspecialchars($joueur['Prenom']); ?>"></label><br>
    <label>Statut : <br>
        <select name="Statut">
            <option value="Actif" <?= $joueur['Statut'] === 'Actif' ? 'selected' : ''; ?>>Actif</option>
            <option value="Blessé" <?= $joueur['Statut'] === 'Blessé' ? 'selected' : ''; ?>>Blessé</option>
            <option value="Suspendu" <?= $joueur['Statut'] === 'Suspendu' ? 'selected' : ''; ?>>Suspendu</option>
            <option value="Absent" <?= $joueur['Statut'] === 'Absent' ? 'selected' : ''; ?>>Absent</option>
        </select>
    </label><br>
    <label>Date de Naissance : <br><input type="date" name="date_naissance" value="<?= htmlspecialchars($joueur['Date_naissance']); ?>"></label><br>
    <label>Poids : <br><input type="number" step="0.1" name="poids" value="<?= htmlspecialchars($joueur['Poids']); ?>"></label><br>
    <label>Taille : <br><input type="number" step="0.1" name="taille" value="<?= htmlspecialchars($joueur['Taille']); ?>"></label><br>
    <button type="save" name="update">Enregistrer</button>
</form>

<form method="POST">
    <label>Nouveau Commentaire :<br><textarea name="Commentaire"></textarea></label><br>
    <button type="save" name="add_comment">Ajouter</button>
</form>

<div class="commentaires">
    <h2>Commentaires</h2>
    <ul>
        <?php foreach ($commentaires as $commentaire): ?>
            <div class="commentaire">
                <strong class="date-commentaire"><?= htmlspecialchars(date("d-m-Y / H:i", strtotime($commentaire['date_commentaire']))); ?></strong>
                <p><?= htmlspecialchars($commentaire['texte']); ?></p>
            </div>
        <?php endforeach; ?>
    </ul>
</div>


<form method="POST">
    <input type="hidden" name="supprimer" value="1">
    <button class="enregistrer" type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce joueur ?');">Supprimer le joueur</button>
</form>
</body>
</html>
