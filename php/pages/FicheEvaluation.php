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

    // Préparer la requête pour récupérer les données du match
    $stmt = $linkpdo->prepare("SELECT Date_Match FROM Match_ WHERE Id_Match = ?");
    $stmt->execute([$id_match]);
    $match = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si le match n'est pas trouvé
    if (!$match) {
        die('Match introuvable.');
    }

    // Comparer la date du match avec la date actuelle
    $dateMatch = new DateTime($match['Date_Match']);
    $dateActuelle = new DateTime();

    // Vérifier si le match est dans le futur
    $isFutureMatch = $dateMatch > $dateActuelle;

    // Préparer la requête pour récupérer l'évaluation du joueur dans la table Participer
    $stmt = $linkpdo->prepare($select_evaluation);
    $stmt->execute([$id, $id_match]);
    $evaluation = $stmt->fetch(PDO::FETCH_ASSOC);

    // Mise à jour de l'évaluation si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        // Récupérer l'évaluation du POST
        $nouvelle_eval = isset($_POST['Evaluation']) ? trim($_POST['Evaluation']) : null;

        // Si une évaluation est donnée et que ce n'est pas un nombre valide, afficher une erreur
        if ($nouvelle_eval !== null && !is_numeric($nouvelle_eval)) {
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
    <style>
        /* Griser le champ si le match est dans le futur */
        .disabled-input {
            background-color: #e0e0e0;
            cursor: not-allowed;
        }
    </style>
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
        <input type="number" step="1" name="Evaluation" value="<?= htmlspecialchars($evaluation['Evaluation'] ?? 0); ?>" min="0" max="5" <?= $isFutureMatch ? 'disabled class="disabled-input"' : ''; ?>>
    </label><br>
    <button type="submit" name="update">Enregistrer</button>
    <a class="fiche-joueur" href="FicheJoueur.php?id=<?= urlencode($id); ?>" >Voir la fiche du joueur</a>
</form>

</body>
</html>
