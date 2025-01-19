<?php
require('../bd/ConnexionBD.php');
require('../requetesSql.php');

// Vérifier si l'ID est fourni
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Préparer la requête pour récupérer les données du joueur
    $stmt = $linkpdo->prepare($select_joueur_spec);
    $stmt->execute([$id]);
    $joueur = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si aucun joueur n'est trouvé
    if (!$joueur) {
        die('Joueur introuvable.');
    }

    // Préparer la requête pour récupérer tous les commentaires du joueur
    $stmt = $linkpdo->prepare($select_commentaire);
    $stmt->execute([$id]);
    $commentaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

    // Suppression du joueur si le formulaire de suppression est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer'])) {
    // Préparer la requête pour vérifier si le joueur est dans un match futur
    $stmt = $linkpdo->prepare("SELECT Date_Match FROM Participer NATURAL JOIN Match_ WHERE Numero_licence = ? AND Date_Match > CURDATE()");
    $stmt->execute([$id]);
    $match_dates = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Vérifier s'il existe des matchs futurs
    $future_match = false;

    foreach ($match_dates as $date) {
        if (new DateTime($date) > new DateTime()) {
            $future_match = true;
            break; // On peut sortir dès qu'on trouve un match futur
        }
    }

    if ($future_match) {
        $message = "Impossible de supprimer ce joueur : il est associé à un match à venir. Veuillez ajuster la sélection de ce match ou le supprimer avant de retirer ce joueur.";
    } else {
        // Suppression des commentaires liés
        $stmt = $linkpdo->prepare($delete_commentaire);
        $stmt->execute([$id]);

        // Suppression du joueur
        $stmt = $linkpdo->prepare($delete_joueur);
        $stmt->execute([$id]);

        header('Location: PageJoueurs.php');
        exit;
    }




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

        $stmt = $linkpdo->prepare($update_joueur);
        $stmt->execute([$nom, $prenom, $statut, $date_naissance, $poids, $taille, $id]);

        header('Location: PageJoueurs.php');
        exit;
    }

    // Ajout d'un nouveau commentaire si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comment'])) {
        $nouveau_commentaire = trim($_POST['Commentaire']);
        if (!empty($nouveau_commentaire)) {
            $stmt = $linkpdo->prepare($insert_commentaire);
            $stmt->execute([$nouveau_commentaire, $id]);
        }

        header('Location: FicheJoueur.php?id=' . $id);
        exit;
    }

    // Mise à jour de la photo si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $photo = $_FILES['photo'];
        $photoPath = '../img/' . basename($photo['name']);
        
        if (move_uploaded_file($photo['tmp_name'], $photoPath)) {
            $stmt = $linkpdo->prepare($update_photo);
            $stmt->execute([$photoPath, $id]);

            header('Location: FicheJoueur.php?id=' . $id);
            exit;
        } else {
            die('Erreur lors du téléchargement de la photo.');
        }
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

<?php if (isset($message)): ?>
    <div id="modal" class="modal" style="display: block;">
        <div class="modal-content">
            <span class="close"><a href="FicheJoueur.php?id=<?= $id; ?>">×</a></span>
            <p><?= htmlspecialchars($message); ?></p>
        </div>
    </div>
<?php endif; ?>

<h1>Fiche de <?= htmlspecialchars($joueur['Prenom']) . ' ' . htmlspecialchars($joueur['Nom']); ?></h1>

<div><a class="return" href="PageJoueurs.php">Retour</a></div>

<div class="photo-container">
    <img src="<?= htmlspecialchars($joueur['Photo'] ?? 'placeholder.png'); ?>" alt="Photo du joueur" class="photo">
</div>

<form method="POST" enctype="multipart/form-data">
    <label>Changer la photo :</label><br>
    <input type="file" name="photo"><br><br>
    <button type="save">Mettre à jour la photo</button>
</form>

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
