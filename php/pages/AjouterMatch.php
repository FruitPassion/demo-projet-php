<?php
// Inclure la connexion à la base de données depuis le dossier "BD"
require('../bd/ConnexionBD.php');

// Gestion du formulaire d'ajout de joueur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset ($_POST['Date_Match'], $_POST['Heure'], $_POST['Lieu_rencontre'],$_POST['Nom_Equipe_Adverse'], 
        $_POST['Resultat_Equipe'],$_POST['Resultat_Equipe_Adverse'])) {

        $Date = $_POST['Date_Match'];
        $Heure = $_POST['Heure'];
        $Lieu_rencontre = $_POST['Lieu_rencontre'];
        $Nom_Equipe_Adverse = $_POST['Nom_Equipe_Adverse'];
        $Resultat_Equipe = $_POST['Resultat_Equipe'];
        $Resultat_Equipe_Adverse = $_POST['Resultat_Equipe_Adverse'];
        
        try {
            // Insertion dans la base de données
            $stmt = $linkpdo->prepare('INSERT INTO Match_ (Date_Match, Heure, Lieu_Rencontre, Nom_Equipe_Adverse, Resultat_Equipe, Resultat_Equipe_Adverse) 
                                VALUES (?, ?, ?, ?, ?, ?)');

            $stmt->execute([$Date_Match, $Heure, $Lieu_rencontre, $Nom_Equipe_Adverse, $Resultat_Equipe, $Resultat_Equipe_Adverse ]);

            // Redirection vers la page principale après l'ajout
            header('Location: PageMatch.php');
            exit;
        } catch  (PDOException $e){
            echo 'Erreur lors ajout match : ' . $e->getMessage();
        }
    } else {
        echo ' ';
    }
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
    <label for="Date_Match">Date :</label>
    <input type="date" id="Date_Match" name="Date_Match" required>

    <label for="Heure">Heure :</label>
    <input type="time" id="Heure" name="Heure" required>

    <label for="Lieu_rencontre">Lieu de rencontre :</label>
    <select id="Lieu_rencontre" name="Lieu_rencontre">
        <option value="Actif">domicile</option>
        <option value="Blessé">extérieur</option>
    </select>

    <label for="Nom_Equipe_Adverse">Nom de l'équipe adverse :</label>
    <input type="text" id="Nom_Equipe_Adverse" name="Nom_Equipe_Adverse" required>

    <label for="Resultat_Equipe">Résultat équipe:</label>
    <input type="number" id="Resultat_Equipe" name="Resultat_Equipe" step="1" required> 
    <label for="Resultat_Equipe_Adverse">Résultat adversaire:</label>
    <input type="number" id="Resultat_Equipe_Adverse" name="Resultat_Equipe_Adverse" step="1" required>

    <button type="submit">Ajouter le match</button>
</form>
</body>
</html>