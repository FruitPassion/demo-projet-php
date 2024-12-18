<?php
session_start();

//Compte acceptés
$login1 = 'Girafe';
$mdp1 = 'Savane'; 
$errorMessage = "";

//Verif Login et mdp ont quelque chose dedans
if (isset($_POST['login']) && isset($_POST['mdp'])) {
    // Verif si id ET mdp sont corrects
    if ($_POST['login'] == $login1 && $_POST['mdp'] == $mdp1) {
        $_SESSION['login'] = $_POST['login'];
        // Début de la phase de "connexion"
        $_SESSION['connecter'] = true;

        header('Location: PageAccueil.php');      //Lien quand utilisateur est connecté & déconnecté
        exit; 
    } else { //Si id ou MDP incorrect
        $errorMessage = "Identifiant ou mot de passe incorrect";
        $_SESSION['connecter'] = false;
    }
}

// Si appuit sur deconnection ET suppression des cookies
if (isset($_POST['deco'])) {
    $_SESSION['connecter'] = false;
    session_destroy();
    header('Location: PageConnexion.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/Connexion.css" rel="stylesheet">
    <link rel="icon" href="../img/vif-dor.png">
    <title>Connexion</title>
</head>
<body>
    <div class="co1">    
        <h1>Connexion</h1>
 
            <form method="post" action="">
                <label for="login">Login :</label>
                <input type="text" id="login" name="login" required><br>
                <label for="mdp">Mot de passe :</label>
                <input type="password" id="mdp" name="mdp" required><br><br>

            <!-- Affichage du message d'erreur si présent -->
            <?php if (!empty($errorMessage)): ?>
                <p style="color: red;"><?php echo $errorMessage; ?></p>
            <?php endif; ?>

                <button type="submit">Envoyer</button> 
            </form>
        
    </div>
</body>
</html>
