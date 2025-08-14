<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['connecter']) || $_SESSION['connecter'] !== true) {
    header('Location: PageConnexion.php');
    exit; //Assure qu'aucun code ne soit exécuté après
}
?>