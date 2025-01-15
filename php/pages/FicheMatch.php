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

    // Récupérer les joueurs participant au match
    $stmt = $linkpdo->prepare('SELECT p.Numero_Licence, j.Nom, j.Prenom, p.Titulaire 
                               FROM Participer p 
                               INNER JOIN Joueur j ON p.Numero_Licence = j.Numero_Licence
                               WHERE p.Id_Match = ?');
    $stmt->execute([$id]);
    $joueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Gestion du formulaire
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Cas de suppression
        if (isset($_POST['supprimer']) && $_POST['supprimer'] == '1') {
            // Supprimer le match
            $stmt = $linkpdo->prepare('DELETE FROM Match_ WHERE Id_Match = ?');
            $stmt->execute([$id]);

            // Supprimer les relations avec les joueurs
            $stmt = $linkpdo->prepare('DELETE FROM Participer WHERE Id_Match = ?');
            $stmt->execute([$id]);

            header('Location: PageMatch.php');
            exit;
        }

        // Cas de mise à jour
        if (isset($_POST['Date_Match'], $_POST['Heure'], $_POST['Lieu_rencontre'], 
                  $_POST['Nom_Equipe_Adverse'], $_POST['Resultat_Equipe'], $_POST['Resultat_Equipe_Adverse'])) {
            
            $Date = $_POST['Date_Match'];
            $Heure = $_POST['Heure'];
            $Lieu_rencontre = $_POST['Lieu_rencontre'];
            $Nom_Equipe_Adverse = $_POST['Nom_Equipe_Adverse'];
            $Resultat_Equipe = $_POST['Resultat_Equipe'];
            $Resultat_Equipe_Adverse = $_POST['Resultat_Equipe_Adverse'];

            // Liste des joueurs sélectionnés
            $joueurs_titulaires = $_POST['joueurs_titulaires'] ?? [];
            $joueurs_remplacants = $_POST['joueurs_remplacants'] ?? [];

            // Vérifier les données
            if (empty($Date) || empty($Heure) || !in_array($Lieu_rencontre, ['Domicile', 'Extérieur'])) {
                die('Erreur : Champs invalides ou incomplets.');
            }

            // Mettre à jour les informations du match
            $stmt = $linkpdo->prepare('UPDATE Match_ 
                                       SET Date_Match = ?, Heure = ?, Lieu_Rencontre = ?, 
                                           Nom_Equipe_Adverse = ?, Resultat_Equipe = ?, 
                                           Resultat_Equipe_Adverse = ? 
                                       WHERE Id_Match = ?');
            $stmt->execute([$Date, $Heure, $Lieu_rencontre, $Nom_Equipe_Adverse, $Resultat_Equipe, $Resultat_Equipe_Adverse, $id]);

            // Mettre à jour les joueurs
            $stmt = $linkpdo->prepare('UPDATE Participer 
                                       SET Titulaire = ? 
                                       WHERE Id_Match = ? AND Numero_Licence = ?');

            // Passer tous les joueurs en "remplaçant" par défaut
            foreach ($joueurs as $joueur) {
                $stmt->execute([0, $id, $joueur['Numero_Licence']]);
            }

            // Mettre à jour les joueurs titulaires
            foreach ($joueurs_titulaires as $titulaire) {
                $stmt->execute([1, $id, $titulaire]);
            }

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
<h1>Fiche du match <?= htmlspecialchars($match['Date_Match']) . ' ' . htmlspecialchars($match['Heure']); ?></h1>

<div><a class="return" href="PageMatch.php">Retour</a></div>

<form method="POST">
    <label for="Date_Match">Date :</label>
    <input type="date" id="Date_Match" name="Date_Match" value="<?= htmlspecialchars($match['Date_Match']); ?>" required>

    <label for="Heure">Heure :</label>
    <input type="time" id="Heure" name="Heure" value="<?= htmlspecialchars($match['Heure']); ?>" required>

    <label for="Lieu_rencontre">Lieu de rencontre :</label>
    <select id="Lieu_rencontre" name="Lieu_rencontre">
        <option value="Domicile" <?= $match['Lieu_Rencontre'] === 'Domicile' ? 'selected' : ''; ?>>Domicile</option>
        <option value="Extérieur" <?= $match['Lieu_Rencontre'] === 'Extérieur' ? 'selected' : ''; ?>>Extérieur</option>
    </select>

    <label for="Nom_Equipe_Adverse">Nom de l'équipe adverse :</label>
    <input type="text" id="Nom_Equipe_Adverse" name="Nom_Equipe_Adverse" value="<?= htmlspecialchars($match['Nom_Equipe_Adverse']); ?>" required>

    <label for="Resultat_Equipe">Résultat équipe :</label>
    <input type="number" id="Resultat_Equipe" name="Resultat_Equipe" value="<?= htmlspecialchars($match['Resultat_Equipe']); ?>" required>

    <label for="Resultat_Equipe_Adverse">Résultat adversaire :</label>
    <input type="number" id="Resultat_Equipe_Adverse" name="Resultat_Equipe_Adverse" value="<?= htmlspecialchars($match['Resultat_Equipe_Adverse']); ?>" required>

    <h2>Joueurs titulaires :</h2>
    <select name="joueurs_titulaires[]" multiple>
        <?php foreach ($joueurs as $joueur): ?>
            <option value="<?= $joueur['Numero_Licence']; ?>" <?= $joueur['Titulaire'] ? 'selected' : ''; ?>>
                <?= htmlspecialchars($joueur['Prenom'] . ' ' . $joueur['Nom']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <h2>Joueurs remplaçants :</h2>
    <select name="joueurs_remplacants[]" multiple>
        <?php foreach ($joueurs as $joueur): ?>
            <option value="<?= $joueur['Numero_Licence']; ?>" <?= !$joueur['Titulaire'] ? 'selected' : ''; ?>>
                <?= htmlspecialchars($joueur['Prenom'] . ' ' . $joueur['Nom']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Enregistrer</button>
</form>

<form method="POST">
    <input type="hidden" name="supprimer" value="1">
    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce match ?');">Supprimer</button>
</form>
</body>
</html>
