<?php
require('../bd/ConnexionBD.php');

// Initialisation des variables
global $linkpdo;
$errorMessage = ''; // Stocker les messages d'erreur
$id = null;

// Vérifier si l'ID du match est fourni
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $errorMessage = 'Erreur : Aucun ID de match spécifié.';
}

// Récupérer les informations du match si l'ID est valide
if (empty($errorMessage) && $id) {
    $stmt = $linkpdo->prepare('SELECT * FROM Match_ WHERE Id_Match = ?');
    $stmt->execute([$id]);
    $match = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$match) {
        $errorMessage = 'Erreur : Match introuvable.';
    }
}

// Vérifications supplémentaires si aucune erreur
if (empty($errorMessage)) {
    $match['Resultat_Equipe'] = $match['Resultat_Equipe'] ?? 0;
    $match['Resultat_Equipe_Adverse'] = $match['Resultat_Equipe_Adverse'] ?? 0;

    $dateMatch = new DateTime($match['Date_Match']);
    $dateActuelle = new DateTime();
    $isDateDansLePasse = $dateMatch < $dateActuelle;
    $dateHeureFicheMatch = new DateTime($match['Date_Match'] . ' ' . $match['Heure']);
    $formatDateHeure = $dateHeureFicheMatch->format('d-m-Y / H:i');

    // Récupération des joueurs associés au match
    $stmt = $linkpdo->prepare('SELECT p.Numero_Licence, j.Nom, j.Prenom, p.Titulaire, p.Poste, p.Evaluation
                               FROM Participer p
                               INNER JOIN Joueur j ON p.Numero_Licence = j.Numero_Licence
                               WHERE p.Id_Match = ? AND p.Poste IS NOT NULL');
    $stmt->execute([$id]);
    $joueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $titulaires = [];
    $remplacants = [];

    foreach ($joueurs as $joueur) {
        if ($joueur['Titulaire'] == 1) {
            $titulaires[] = $joueur;
        } else {
            $remplacants[] = $joueur;
        }
    }
}

// Gestion des formulaires (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['supprimer'])) {
        // Suppression du match et des participations associées
        try {
            $stmt = $linkpdo->prepare('DELETE FROM Participer WHERE Id_Match = ?');
            $stmt->execute([$id]);

            $stmt = $linkpdo->prepare('DELETE FROM Match_ WHERE Id_Match = ?');
            $stmt->execute([$id]);

            header('Location: PageMatch.php');
            exit;
        } catch (Exception $e) {
            $errorMessage = 'Erreur lors de la suppression : ' . $e->getMessage();
        }
    }

    if (isset($_POST['Date_Match'], $_POST['Heure'], $_POST['Lieu_rencontre'], 
              $_POST['Nom_Equipe_Adverse'], $_POST['Resultat_Equipe'], $_POST['Resultat_Equipe_Adverse'])) {

        $Date = $_POST['Date_Match'];
        $Heure = $_POST['Heure'];
        $Lieu_rencontre = $_POST['Lieu_rencontre'];
        $Nom_Equipe_Adverse = $_POST['Nom_Equipe_Adverse'];
        $Resultat_Equipe = $_POST['Resultat_Equipe'];
        $Resultat_Equipe_Adverse = $_POST['Resultat_Equipe_Adverse'];

        // Validation des champs
        if (empty($Date) || empty($Heure) || !in_array($Lieu_rencontre, ['Domicile', 'Extérieur']) || 
            empty($Nom_Equipe_Adverse) || !is_numeric($Resultat_Equipe) || !is_numeric($Resultat_Equipe_Adverse)) {
            $errorMessage = 'Erreur : Champs invalides ou incomplets.';
        } else {
            $dateMatchUpdate = new DateTime($Date);
            if ($dateMatchUpdate < new DateTime() && !$isDateDansLePasse) {
                $errorMessage = 'Erreur : Vous ne pouvez pas enregistrer un match avec une date dans le passé.';
            } else {
                try {
                    $stmt = $linkpdo->prepare('UPDATE Match_ 
                                               SET Date_Match = ?, Heure = ?, Lieu_Rencontre = ?, 
                                                   Nom_Equipe_Adverse = ?, Resultat_Equipe = ?, 
                                                   Resultat_Equipe_Adverse = ? 
                                               WHERE Id_Match = ?');
                    $stmt->execute([$Date, $Heure, $Lieu_rencontre, $Nom_Equipe_Adverse, $Resultat_Equipe, $Resultat_Equipe_Adverse, $id]);

                    header('Location: PageMatch.php');
                    exit;
                } catch (Exception $e) {
                    $errorMessage = 'Erreur lors de la mise à jour : ' . $e->getMessage();
                }
            }
        }
    } else {
        $errorMessage = 'Erreur : Champs invalides ou incomplets.';
    }
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

<h1>Fiche du match <?= htmlspecialchars($formatDateHeure); ?>

<div><a class="return" href="PageMatch.php">Retour</a></div>

<!-- Modale d'erreur -->
<?php if (!empty($errorMessage)): ?>
    <div id="errorModal" class="modal" style="display: block;">
        <div class="modal-content">
        <a href="FicheMatch.php?id=<?= htmlspecialchars($id); ?>" class="close-btn">x</a>
            <p><?= htmlspecialchars($errorMessage) ?></p>
        </div>
    </div>
<?php endif; ?>


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
<div class="joueurs-list">
    <ul>
        <?php foreach ($titulaires as $joueur): ?>
            <li>
                <a href="FicheEvaluation.php?id=<?= $joueur['Numero_Licence']; ?>&id_match=<?= $match['Id_Match']; ?>" class="joueur-btn">
                    <?= htmlspecialchars($joueur['Prenom'] . ' ' . $joueur['Nom']) . ' - Poste : ' . htmlspecialchars($joueur['Poste']) . ' - Note : ' . (isset($joueur['Evaluation']) ? htmlspecialchars($joueur['Evaluation']) : 'Non Noté'); ?> 
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<h2>Joueurs remplaçants :</h2>
<div class="joueurs-list">
    <ul>
        <?php foreach ($remplacants as $joueur): ?>
            <li>
                <a href="FicheEvaluation.php?id=<?= $joueur['Numero_Licence']; ?>&id_match=<?= $match['Id_Match']; ?>" class="joueur-btn">
                    <?= htmlspecialchars($joueur['Prenom'] . ' ' . $joueur['Nom']) . ' - Poste : ' . htmlspecialchars($joueur['Poste']) . ' - Note : ' . (isset($joueur['Evaluation']) ? htmlspecialchars($joueur['Evaluation']) : 'Non Noté'); ?> 
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Bouton pour changer le joueur -->
    <button type="button" 
        onclick="window.location.href='ajouterMatchJoueur.php?Id_Match=<?= htmlspecialchars($id); ?>'"
        <?= $isDateDansLePasse ? 'disabled' : ''; ?>
        class="<?= $isDateDansLePasse ? 'disabled-btn' : ''; ?>">
    Changer joueur
    </button>
</div>

<!-- Bloc pour les actions d'enregistrement et de suppression -->
<div class="actions">
    <form method="POST">
        <button type="save" name="enregistrer">Enregistrer</button>
    </form>

    <form method="POST">
        <input type="hidden" name="supprimer" value="1">
        <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce match ?');">Supprimer</button>
    </form>
</div>

</body>
</html>
