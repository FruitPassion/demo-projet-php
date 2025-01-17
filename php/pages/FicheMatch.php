<?php
require('../bd/ConnexionBD.php');

// Vérifier si l'ID du match est fourni
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupérer les informations du match
    $stmt = $linkpdo->prepare('SELECT * FROM Match_ WHERE Id_Match = ?');
    $stmt->execute([$id]);
    $match = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si le match n'existe pas
    if (!$match) {
        die('Match introuvable.');
    }

    // Initialiser les résultats à 0 si non définis
    $match['Resultat_Equipe'] = $match['Resultat_Equipe'] ?? 0;
    $match['Resultat_Equipe_Adverse'] = $match['Resultat_Equipe_Adverse'] ?? 0;

    // Vérification si la date du match est dans le passé
    $dateMatch = new DateTime($match['Date_Match']);
    $dateActuelle = new DateTime();
    $isDateDansLePasse = $dateMatch < $dateActuelle;
    $dateHeureFicheMatch = new DateTime($match['Date_Match'] . ' ' . $match['Heure']);
    $formatDateHeure = $dateHeureFicheMatch->format('d-m-Y / H:i');

    // Récupérer les joueurs réellement associés au match
    $stmt = $linkpdo->prepare('
        SELECT p.Numero_Licence, j.Nom, j.Prenom, p.Titulaire, p.Poste
        FROM Participer p
        INNER JOIN Joueur j ON p.Numero_Licence = j.Numero_Licence
        WHERE p.Id_Match = ? AND p.Poste IS NOT NULL
    ');
    $stmt->execute([$id]);
    $joueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Séparer les joueurs titulaires et remplaçants
    $titulaires = [];
    $remplacants = [];

    foreach ($joueurs as $joueur) {
        // Vérification du statut de titulaire ou remplaçant
        if ($joueur['Titulaire'] == 1) {
            $titulaires[] = $joueur;
        } else {
            $remplacants[] = $joueur;
        }
    }

    // Gestion du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Vérification des champs de formulaire
        $isValid = true;

        if (isset($_POST['Date_Match'], $_POST['Heure'], $_POST['Lieu_rencontre'], 
                  $_POST['Nom_Equipe_Adverse'], $_POST['Resultat_Equipe'], $_POST['Resultat_Equipe_Adverse'])) {
            
            $Date = $_POST['Date_Match'];
            $Heure = $_POST['Heure'];
            $Lieu_rencontre = $_POST['Lieu_rencontre'];
            $Nom_Equipe_Adverse = $_POST['Nom_Equipe_Adverse'];
            $Resultat_Equipe = $_POST['Resultat_Equipe'];
            $Resultat_Equipe_Adverse = $_POST['Resultat_Equipe_Adverse'];

            // Vérification des autres champs
            if (empty($Date) || empty($Heure) || !in_array($Lieu_rencontre, ['Domicile', 'Extérieur']) || empty($Nom_Equipe_Adverse) || !is_numeric($Resultat_Equipe) || !is_numeric($Resultat_Equipe_Adverse)) {
                $isValid = false;
            }

            if (!$isValid) {
                die('Erreur : Champs invalides ou incomplets.');
            }

            // Mettre à jour les informations du match
            $stmt = $linkpdo->prepare('UPDATE Match_ 
                                       SET Date_Match = ?, Heure = ?, Lieu_Rencontre = ?, 
                                           Nom_Equipe_Adverse = ?, Resultat_Equipe = ?, 
                                           Resultat_Equipe_Adverse = ? 
                                       WHERE Id_Match = ?');
            $stmt->execute([$Date, $Heure, $Lieu_rencontre, $Nom_Equipe_Adverse, $Resultat_Equipe, $Resultat_Equipe_Adverse, $id]);

            header('Location: PageMatch.php');
            exit;
        } else {
            die('Erreur : Champs invalides ou incomplets.');
        }
    }
} else {
    die('ID du match non spécifié.');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/FicheMatch.css" rel="stylesheet">
    <title>Fiche match</title>
</head>
<body>
<h1>Fiche du match <?= htmlspecialchars($formatDateHeure); ?></h1>


<div><a class="return" href="PageMatch.php">Retour</a></div>

<form method="POST">
    <label for="Date_Match">Date :</label>
    <input type="date" id="Date_Match" name="Date_Match" value="<?= htmlspecialchars($match['Date_Match']); ?>" class="<?= $isDateDansLePasse ? 'grise' : ''; ?>" <?= $isDateDansLePasse ? 'readonly' : ''; ?> required>
    <?php if ($isDateDansLePasse): ?>
        <input type="hidden" name="Date_Match" value="<?= htmlspecialchars($match['Date_Match']); ?>">
    <?php endif; ?>

    <label for="Heure">Heure :</label>
    <input type="time" id="Heure" name="Heure" value="<?= htmlspecialchars($match['Heure']); ?>" class="<?= $isDateDansLePasse ? 'grise' : ''; ?>" <?= $isDateDansLePasse ? 'readonly' : ''; ?> required>
    <?php if ($isDateDansLePasse): ?>
        <input type="hidden" name="Heure" value="<?= htmlspecialchars($match['Heure']); ?>">
    <?php endif; ?>

    <label for="Lieu_rencontre">Lieu de rencontre :</label>
    <select id="Lieu_rencontre" name="Lieu_rencontre" class="<?= $isDateDansLePasse ? 'grise' : ''; ?>" <?= $isDateDansLePasse ? 'disabled' : ''; ?> required>
        <option value="Domicile" <?= $match['Lieu_Rencontre'] === 'Domicile' ? 'selected' : ''; ?>>Domicile</option>
        <option value="Extérieur" <?= $match['Lieu_Rencontre'] === 'Extérieur' ? 'selected' : ''; ?>>Extérieur</option>
    </select>
    <?php if ($isDateDansLePasse): ?>
        <input type="hidden" name="Lieu_rencontre" value="<?= htmlspecialchars($match['Lieu_Rencontre']); ?>">
    <?php endif; ?>

    <label for="Nom_Equipe_Adverse">Nom de l'équipe adverse :</label>
    <input type="text" id="Nom_Equipe_Adverse" name="Nom_Equipe_Adverse" value="<?= htmlspecialchars($match['Nom_Equipe_Adverse']); ?>" class="<?= $isDateDansLePasse ? 'grise' : ''; ?>" <?= $isDateDansLePasse ? 'readonly' : ''; ?> required>
    <?php if ($isDateDansLePasse): ?>
        <input type="hidden" name="Nom_Equipe_Adverse" value="<?= htmlspecialchars($match['Nom_Equipe_Adverse']); ?>">
    <?php endif; ?>

    <label for="Resultat_Equipe">Résultat équipe :</label>
    <input type="number" id="Resultat_Equipe" name="Resultat_Equipe" value="<?= htmlspecialchars($match['Resultat_Equipe_Adverse'] ?? 0); ?>" class="<?= !$isDateDansLePasse ? 'grise' : ''; ?>" <?= !$isDateDansLePasse ? 'readonly' : ''; ?> required>
    <?php if (!$isDateDansLePasse): ?>
        <input type="hidden" name="Resultat_Equipe" value="<?= htmlspecialchars($match['Resultat_Equipe']); ?>">
    <?php endif; ?>

    <label for="Resultat_Equipe_Adverse">Résultat adversaire :</label>
    <input type="number" id="Resultat_Equipe_Adverse" name="Resultat_Equipe_Adverse" value="<?= htmlspecialchars($match['Resultat_Equipe_Adverse'] ?? 0); ?>" class="<?= !$isDateDansLePasse ? 'grise' : ''; ?>" <?= !$isDateDansLePasse ? 'readonly' : ''; ?> required>
    <?php if (!$isDateDansLePasse): ?>
        <input type="hidden" name="Resultat_Equipe_Adverse" value="<?= htmlspecialchars($match['Resultat_Equipe_Adverse']); ?>">
    <?php endif; ?>

    <h2>Joueurs titulaires :</h2>
    <ul>
        <?php foreach ($titulaires as $joueur): ?>
            <li><?= htmlspecialchars($joueur['Prenom'] . ' ' . $joueur['Nom']) . ' - Poste : ' . htmlspecialchars($joueur['Poste']); ?></li>
        <?php endforeach; ?>
    </ul>

    <h2>Joueurs remplaçants :</h2>
    <ul>
        <?php foreach ($remplacants as $joueur): ?>
            <li><?= htmlspecialchars($joueur['Prenom'] . ' ' . $joueur['Nom']) . ' - Poste : ' . htmlspecialchars($joueur['Poste']); ?></li>
        <?php endforeach; ?>
    </ul>

    <button type="save">Enregistrer</button>
</form>

<form method="POST">
    <input type="hidden" name="supprimer" value="1">
    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce match ?');">Supprimer</button>
</form>
</body>
</html>
