<?php
// Inclure la connexion à la base de données depuis le dossier "BD"
require('../bd/ConnexionBD.php');

$stmt = $linkpdo->query("SELECT Numero_licence, Nom, Prenom, Statut, photo FROM Joueur WHERE Statut ='Actif'");
$joueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

            $stmt->execute([$Date, $Heure, $Lieu_rencontre, $Nom_Equipe_Adverse, $Resultat_Equipe, $Resultat_Equipe_Adverse ]);

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
    <title>Ajouter un match</title>
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
        <option value="Domicile">domicile</option>
        <option value="Extérieur">extérieur</option>
    </select>

    <label for="Nom_Equipe_Adverse">Nom de l'équipe adverse :</label>
    <input type="text" id="Nom_Equipe_Adverse" name="Nom_Equipe_Adverse" required>

    <label for="Resultat_Equipe">Résultat équipe:</label>
    <input type="number" id="Resultat_Equipe" name="Resultat_Equipe" step="1" required> 
    <label for="Resultat_Equipe_Adverse">Résultat adversaire:</label>
    <input type="number" id="Resultat_Equipe_Adverse" name="Resultat_Equipe_Adverse" step="1" required>

    <label> Joueurs disponibles : </label>
    <table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($joueurs as $joueur): ?>
        <tr>
            <td><?= htmlspecialchars($joueur['Nom'] ?? 'Inconnu'); ?></td>
            <td><?= htmlspecialchars($joueur['Prenom'] ?? 'Inconnu'); ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>

    <button type="submit">Ajouter le match</button>

</form>
</body>
</html>