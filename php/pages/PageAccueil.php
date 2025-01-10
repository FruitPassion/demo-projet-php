<?php
// Si appuit sur deconnection ET suppression des cookies
if (isset($_POST['deco'])) {
    $_SESSION['connecter'] = false;
    header('Location: PageConnexion.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/Accueil.css" rel="stylesheet">
    <title>LigueDorée</title>
</head>
<body>
    <header>LigueDorée</header>

    <!-- Bouton du menu -->
    <div class="menu-container">
        <button class="menu-button">☰</button>
        <div class="menu-content">
            <a href="PageJoueurs.php">Mes Joueurs</a>
            <a href="PageMatch.php">Mes Matchs</a>
            <a href="PageStatistiques.php">Statistiques</a>
            <a href="PageAccueil.php">Accueil</a>
        </div>
    </div>
    
    <form method="post" action="">
        <button type="submit" name="deco" class="deconnection"> Se déconnecter </button> 
    </form>
    
    <div class="co1">
        <a href="PageJoueurs.php" class="card">
            <img src="../img/team-icon.png" alt="Mes Joueurs">
            <span>Mes Joueurs</span>
        </a>
        <a href="PageMatch.php" class="card">
            <img src="../img/balai-icon.png" alt="Mes Matchs">
            <span>Mes Matchs</span>
        </a>
        <a href="PageStatistiques.php" class="card">
            <img src="../img/pourcentage-icon.png" alt="Statistiques">
            <span>Statistique</span>
        </a>
    </div>
</body>
</html>
