<?php
require('../bd/ConnexionBD.php');
require('../requetesSql.php');

// Vérifier si l'ID du joueur et l'ID du match sont fournis
if (isset($_GET['id']) && isset($_GET['id_match'])) {
    $id = $_GET['id'];
    $id_match = $_GET['id_match'];

    // Préparer la requête pour récupérer les données du joueur
    $stmt = $linkpdo->prepare($select_infos_joueur);
    $stmt->execute([$id]);
    $joueur = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si aucun joueur n'est trouvé
    if (!$joueur) {
        die('Joueur introuvable.');
    }

    // Préparer la requête pour récupérer l'évaluation du joueur dans la table Participer
    $stmt = $linkpdo->prepare($select_evaluation);
    $stmt->execute([$id, $id_match]);
    $evaluation = $stmt->fetch(PDO::FETCH_ASSOC);

    // Mise à jour de l'évaluation si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $nouvelle_eval = trim($_POST['Evaluation']);

        if ($nouvelle_eval === '' || !is_numeric($nouvelle_eval)) {
            die('Erreur : Champs invalides ou incomplets.');
        }

        $stmt = $linkpdo->prepare($update_evaluation);
        $stmt->execute([$nouvelle_eval, $id, $id_match]);

        // Redirection après succès
        header('Location: FicheMatch.php?id=' . $id_match);
        exit;
    }
} else {
    die('ID joueur ou match non fourni.');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/FicheEvaluation.css" rel="stylesheet">
    <title>Fiche Evaluation</title>
</head>
<body>

<h1>Fiche d'évaluation de <?= htmlspecialchars($joueur['Prenom']) . ' ' . htmlspecialchars($joueur['Nom']); ?></h1>

<a class="return" href="FicheMatch.php?id=<?= urlencode($id_match); ?>">Retour</a>

<div class="photo-container">
    <img src="<?= htmlspecialchars($joueur['Photo'] ?? 'placeholder.png'); ?>" alt="Photo du joueur" class="photo">
</div>

<div class="info">
    <p><strong>Nom :</strong> <?= htmlspecialchars($joueur['Nom']); ?></p>
    <p><strong>Prénom :</strong> <?= htmlspecialchars($joueur['Prenom']); ?></p>
</div>

<form method="POST">
    <label>Evaluation : <br>
        <input type="number" step="1" name="Evaluation" value="<?= htmlspecialchars($evaluation['Evaluation'] ?? 0); ?>" min="0" max="5">
    </label><br>
    <button type="submit" name="update">Enregistrer</button>
    <a class="fiche-joueur" href="FicheJoueur.php?id=<?= urlencode($id); ?>" >Voir la fiche du joueur</a>
</form>
</body>
</html>
