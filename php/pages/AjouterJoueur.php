<?php
// Inclure la connexion à la base de données depuis le dossier "BD"
require('../bd/ConnexionBD.php');
require('../requetesSql.php');

// Gestion du formulaire d'ajout de joueur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $numero_licence = $_POST['numero_licence'];
    $date_naissance = $_POST['date_naissance'];
    $poids = $_POST['poids'];
    $taille = $_POST['taille'];
    $statut = $_POST['statut'];

    // Gestion de l'upload de photo
    $photo = null;
    if (!empty($_FILES['photo']['name'])) {
        $target_dir = "../img/";
        $photo = $target_dir . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
    }

    // Insertion dans la table Joueur
    $stmt = $linkpdo->prepare($insert_joueur);
    $stmt->execute([$nom, $prenom, $numero_licence, $date_naissance, $poids, $taille, $statut, $photo]);

    // Redirection vers la page principale après l'ajout
    header('Location: PageJoueurs.php');
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/AjouterJoueur.css" rel="stylesheet">
    <title>Ajouter un joueur</title>
</head>
<body>



<h1>Ajouter un nouveau joueur</h1>

<!-- Lien pour revenir à la page principale -->
<a class="rtab" href="PageJoueurs.php">Retour au tableau</a>

<form method="POST" enctype="multipart/form-data">
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" required>

    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" required>

    <label for="numero_licence">Numéro de licence :</label>
    <input type="number" id="numero_licence" name="numero_licence" required>

    <label for="date_naissance">Date de naissance :</label>
    <input type="date" id="date_naissance" name="date_naissance" required>

    <label for="poids">Poids (kg) :</label>
    <input type="number" id="poids" name="poids" step="0.1" required>

    <label for="taille">Taille (cm) :</label>
    <input type="number" id="taille" name="taille" step="0.1" required>

    <label for="statut">Statut :</label>
    <select id="statut" name="statut">
        <option value="Actif">Actif</option>
        <option value="Blessé">Blessé</option>
        <option value="Suspendu">Suspendu</option>
        <option value="Absent">Absent</option>
    </select>

    <label for="photo">Photo :</label>
    <input type="file" id="photo" name="photo" accept="image/*">

    <button type="submit">Ajouter le joueur</button>
</form>
</body>
</html>
