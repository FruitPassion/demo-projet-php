<?php
require('../bd/ConnexionBD.php');
require('../requetesSql.php');

session_start();

//Verif Login et mdp ont quelque chose dedans
if (isset($_POST['login']) && isset($_POST['mdp'])) {
    $login = $_POST['login'];
    $mdp = $_POST['mdp'];

    $stmt = $linkpdo->prepare($select_login);
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verif si id ET mdp sont corrects
    if ($user && password_verify($mdp, $user['Mot_de_passe'])) {
        $_SESSION['login'] = $login;
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
                <input type="text" id="login" name="login" class="input-field" required><br>

                <label for="mdp">Mot de passe :</label>
                <input type="password" id="mdp" name="mdp" class="input-field" required><br><br>

                <!-- Affichage du message d'erreur si présent -->
                <?php if (!empty($errorMessage)): ?>
                    <p class="error-message"><?php echo $errorMessage; ?></p>
                <?php endif; ?>

                <button type="submit" class="submit-btn">Envoyer</button> 
            </form>
        
    </div>
</body>
</html>
