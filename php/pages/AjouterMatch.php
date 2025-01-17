<?php
// Inclure la connexion à la base de données
require('../bd/ConnexionBD.php');

// Gestion du formulaire d'ajout de match
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Date_Match'], $_POST['Heure'], $_POST['Lieu_rencontre'], $_POST['Nom_Equipe_Adverse'])) {

        // Récupération des informations du formulaire
        $Date = $_POST['Date_Match'];
        $Heure = $_POST['Heure'];
        $Lieu_rencontre = $_POST['Lieu_rencontre'];
        $Nom_Equipe_Adverse = $_POST['Nom_Equipe_Adverse'];

        // Vérifier si la date du match est dans le passé
        $currentDate = date('Y-m-d'); // Date actuelle au format YYYY-MM-DD
        if ($Date < $currentDate) {
            echo 'Erreur : La date du match ne peut pas être dans le passé.';
        } else {
            try {
                // Insertion dans la table Match_
                $stmt = $linkpdo->prepare('INSERT INTO Match_ (Date_Match, Heure, Lieu_Rencontre, Nom_Equipe_Adverse) 
                                           VALUES (?, ?, ?, ?)');
                $stmt->execute([$Date, $Heure, $Lieu_rencontre, $Nom_Equipe_Adverse]);

                // Récupérer l'ID du match inséré
                $idMatch = $linkpdo->lastInsertId();

                // Redirection vers la page d'ajout de joueurs
                header('Location: AjouterMatchJoueur.php?idMatch=' . $idMatch);
                exit;

            } catch (PDOException $e) {
                echo 'Erreur lors de l\'ajout du match : ' . $e->getMessage();
            }
        }
    } else {
        echo 'Veuillez remplir tous les champs.';
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/AjouterMatch.css" rel="stylesheet">
    <title>Ajouter un match</title>
</head>
<body>

    <h1>Ajouter un match</h1>
    <a class="rtab" href="PageMatch.php">Retour au tableau</a>

    <form method="POST">
        <label for="Date_Match">Date :</label>
        <input type="date" id="Date_Match" name="Date_Match" required>

        <label for="Heure">Heure :</label>
        <input type="time" id="Heure" name="Heure" required>

        <label for="Lieu_rencontre">Lieu de rencontre :</label>
        <select id="Lieu_rencontre" name="Lieu_rencontre">
            <option value="Domicile">Domicile</option>
            <option value="Extérieur">Extérieur</option>
        </select>

        <label for="Nom_Equipe_Adverse">Nom de l'équipe adverse :</label>
        <input type="text" id="Nom_Equipe_Adverse" name="Nom_Equipe_Adverse" required>

        <button type="submit">Ajouter les joueurs</button>
    </form>

</body>
</html>
