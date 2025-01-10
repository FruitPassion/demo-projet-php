<?php
// Inclure la connexion à la base de données depuis le dossier "BD"
require('../bd/ConnexionBD.php');

// Gestion du formulaire d'ajout de joueur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Date = $_POST['Date'];
    $Heure = $_POST['Heure'];
    $Lieu_rencontre = $_POST['Lieu_rencontre'];
    $Nom_Equipe_Adverse = $_POST['Nom_Equipe_Adverse'];
    $Resultat_equipe = $_POST['Resultat_equipe']
    $Resultat_equipe_adverse = $_POST['Resultat_equipe_adverse']
    $Resultat_Match = $Resultat_equipe.' '. $Resultat_equipe_adverse;
    $Date_Match = $Date.' '.$Heure;


    // Insertion dans la base de données
    $stmt = $linkpdo->prepare('INSERT INTO Match_ (Date_Match, Lieu_Rencontre, Nom_Equipe_Adverse, Resultat_Match) 
                           VALUES (?, ?, ?, ?)');

    $stmt = $linkpdo->query('SELECT Date_Match, Lieu_Rencontre, Nom_Equipe_Adverse, Resultat_Match FROM Match_');
    $stmt->execute([$Date_Match, $Lieu_rencontre, $Nom_Equipe_Adverse, $Resultat_Match]);

    // Redirection vers la page principale après l'ajout
    header('Location: PageMatch.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/AjouterMatch.css" rel="stylesheet">
    <title>Ajouter un joueur</title>
</head>
<body>

<h1>Ajouter un nouveau match</h1>
<a class="rtab" href="PageMatch.php">Retour au tableau</a>

<form method="POST" enctype="multipart/form-data">
    <label for="Date">Date :</label>
    <input type="date" id="Date" name="Date" required>

    <label for="Heure">Heure :</label>
    <input type="time" id="Heure" name="Heure" required>

    <label for="Lieu_rencontre">Lieu de rencontre :</label>
    <select id="Lieu_rencontre" name="Lieu_rencontre">
        <option value="Actif">domicile</option>
        <option value="Blessé">extérieur</option>
    </select>

    <label for="Nom_Equipe_Adverse">Nom de l'équipe adverse :</label>
    <input type="text" id="Nom_Equipe_Adverse" name="Nom_Equipe_Adverse" required>

    <label for="Resultat_equipe">Résultat équipe:</label>
    <input type="number" id="poids" name="poids" step="0.1" required> 
    <label for="Resultat_equipe_adverse">Résultat adversaire:</label>
    <input type="number" id="poids" name="poids" step="0.1" required>

    <button type="submit">Ajouter le match</button>
</form>
</body>
</html>
