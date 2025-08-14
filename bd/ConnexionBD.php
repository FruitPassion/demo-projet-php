<?php
    require('ConfigurationMySQL.php'); 

    try {
        // Connexion au serveur
        $linkpdo = new PDO("mysql:host=$server;port=$port;dbname=$db", $login, $mdp);
        $linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
?>
