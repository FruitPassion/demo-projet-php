<?php
require('../bd/ConnexionBD.php');

// Vérifier si l'ID est fourni
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Préparer la requête pour récupérer les données du joueur
    $stmt = $linkpdo->prepare('SELECT * FROM Match_ WHERE Id_Match = ?');
    $stmt->execute([$id]);
    $match = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si aucun joueur n'est trouvé
    if (!$match) {
        die('Match introuvable.');
    }

    // Suppression du joueur si le formulaire de suppression est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer'])) {
        $stmt = $linkpdo->prepare('DELETE FROM Match_ WHERE Id_Match = ?');
        $stmt->execute([$id]);

        header('Location: PageMatch.php');
        exit;
    }

    // Mise à jour des informations du joueur si le formulaire est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
        $Date = $_POST['Date_Match'];
        $Heure = $_POST['Heure'];
        $Lieu_rencontre = $_POST['Lieu_rencontre'];
        $Nom_Equipe_Adverse = $_POST['Nom_Equipe_Adverse'];
        $Resultat_Equipe = $_POST['Resultat_Equipe'];
        $Resultat_Equipe_Adverse = $_POST['Resultat_Equipe_Adverse'];
 
        if (empty($Date) || empty($Heure) || !in_array($Lieu_Rencontre, ['Domicile', 'Extérieur'])) {
            die('Erreur : Champs invalides ou incomplets.');
        }

        $stmt = $linkpdo->prepare('UPDATE Match_ SET Date_Match = ?, Heure = ?, Lieu_Rencontre = ?, Nom_Equipe_Adverse = ?, Resultat_Equipe = ?, Resultat_Equipe_Adverse = ? WHERE Id_Match = ?');
        $stmt->execute([$Date, $Heure, $Lieu_Rencontre, $Nom_Equipe_Adverse, $Resultat_Equipe, $Resultat_Equipe_Adverse, $id]);

        header('Location: PageMatch.php');
        exit;
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

<h1>Fiche du <?= htmlspecialchars($match['Date_Match']) . ' ' . htmlspecialchars($match['Heure']); ?></h1>

<div><a class="return" href="PageMatch.php">Retour</a></div>

<form method="POST">
<label for="Date_Match">Date :</label>
    <input type="date" id="Date_Match" name="Date_Match" required>

    <label for="Heure">Heure :</label>
    <input type="time" id="Heure" name="Heure" required>

    <label for="Lieu_rencontre">Lieu de rencontre :</label>
    <select id="Lieu_rencontre" name="Lieu_rencontre">
        <option value="Domicile">domicile</option>
        <option value="Extérieur">extérieur</option>
    </select>

    <label for="Nom_Equipe_Adverse">Nom de l'équipe adverse :</label>
    <input type="text" id="Nom_Equipe_Adverse" name="Nom_Equipe_Adverse" required>

    <label for="Resultat_Equipe">Résultat équipe:</label>
    <input type="number" id="Resultat_Equipe" name="Resultat_Equipe" step="1" required> 
    <label for="Resultat_Equipe_Adverse">Résultat adversaire:</label>
    <input type="number" id="Resultat_Equipe_Adverse" name="Resultat_Equipe_Adverse" step="1" required>

    <button type="save" name="update">Enregistrer</button>
</form>

<form method="POST">
    <input type="hidden" name="supprimer" value="1">
    <button class="enregistrer" type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce match ?');">Supprimer le match</button>
</form>
</body>
</html>
