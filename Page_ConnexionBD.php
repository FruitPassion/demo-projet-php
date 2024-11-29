<?php
    require('Configuration_MySQL.php'); 

    try {
        // Connexion au serveur
        $linkpdo = new PDO("mysql:host=$server", $login, $mdp);
        $linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Suppression BD si existe
        $dropDBQuery = "DROP DATABASE IF EXISTS $db";
        $linkpdo->exec($dropDBQuery);

        // Création BD
        $createDBQuery = "CREATE DATABASE $db";
        $linkpdo->exec($createDBQuery);

        // Sélection BD
        $linkpdo->exec("USE $db");
        
        // Exécution du script 
        $BD_Script = 'Script_Creation_BD.sql';
        if (file_exists($BD_Script)) {
            $BD = file_get_contents($BD_Script);

            if ($BD === false) {
                die('Erreur : Impossible de lire le fichier SQL');
            }

            try {
                $linkpdo->exec($BD);
                echo "Le script SQL a été exécuté avec succès.<br>";
            } catch (Exception $e) {
                die('Erreur lors de l\'exécution du script SQL : ' . $e->getMessage());
            }
        } else {
            die('Erreur : Le fichier ' . $BD_Script . ' n\'existe pas');
        }
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
?>