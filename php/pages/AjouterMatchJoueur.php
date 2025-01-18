<?php
// Inclure la connexion à la base de données
require('../bd/ConnexionBD.php');

// Récupérer l'ID du match
$idMatch = $_GET['idMatch'] ?? null;
if (!$idMatch) {
    die('ID du match manquant.');
}

// Récupérer les joueurs actifs
$stmt = $linkpdo->query("SELECT Numero_licence, Nom, Prenom, Taille, Poids FROM Joueur WHERE Statut = 'Actif'");
$joueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gestion du formulaire d'ajout des joueurs au match
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $postes = $_POST['poste'] ?? []; // Tableau associatif [Numero_licence => Poste]
        $selectionnes = $_POST['selectionne'] ?? []; // Joueurs sélectionnés
        $titulaires = $_POST['titulaire'] ?? []; // Joueurs titulaires parmi les sélectionnés

        // Liste des postes valides
        $postesValides = ['Poursuiveur', 'Batteur', 'Gardien', 'Attrapeur'];

        // Comptages pour validation
        $counts = [
            'Poursuiveur' => 0,
            'Batteur' => 0,
            'Gardien' => 0,
            'Attrapeur' => 0,
            'Remplaçant' => 0,
        ];

        // Vérification des joueurs sélectionnés
        foreach ($selectionnes as $numeroLicence => $valeur) {
            // Vérifier si un poste a été attribué
            if (!isset($postes[$numeroLicence]) || empty($postes[$numeroLicence])) {
                throw new Exception("Le joueur $numeroLicence est sélectionné mais n'a pas de poste.");
            }

            $poste = $postes[$numeroLicence];
            if (!in_array($poste, $postesValides)) {
                throw new Exception("Poste invalide pour le joueur $numeroLicence.");
            }

            // Titulaire ou remplaçant
            $isTitulaire = isset($titulaires[$numeroLicence]) && $titulaires[$numeroLicence] == 1;

            if ($isTitulaire) {
                $counts[$poste]++;
            } else {
                $counts['Remplaçant']++;
            }
        }

        // Vérifier les contraintes sur les postes
        if ($counts['Gardien'] !== 1) {
            throw new Exception("Il doit y avoir exactement 1 gardien titulaire.");
        }
        if ($counts['Attrapeur'] !== 1) {
            throw new Exception("Il doit y avoir exactement 1 attrapeur titulaire.");
        }
        if ($counts['Poursuiveur'] !== 3) {
            throw new Exception("Il doit y avoir exactement 3 poursuiveurs titulaires.");
        }
        if ($counts['Batteur'] !== 2) {
            throw new Exception("Il doit y avoir exactement 2 batteurs titulaires.");
        }
        if ($counts['Remplaçant'] > 4) {
            throw new Exception("Il ne peut pas y avoir plus de 4 remplaçants.");
        }

        // Préparer la requête d'insertion
        $stmt = $linkpdo->prepare('INSERT INTO Participer (Numero_licence, Id_Match, Poste, Titulaire) VALUES (?, ?, ?, ?)');

        foreach ($selectionnes as $numeroLicence => $valeur) {
            $poste = $postes[$numeroLicence];
            $isTitulaire = isset($titulaires[$numeroLicence]) && $titulaires[$numeroLicence] == 1;
            $titulaire = $isTitulaire ? 1 : 0;

            // Vérifier si le joueur est déjà inscrit pour ce match
            $checkStmt = $linkpdo->prepare('SELECT COUNT(*) FROM Participer WHERE Numero_licence = ? AND Id_Match = ?');
            $checkStmt->execute([$numeroLicence, $idMatch]);
            $exists = $checkStmt->fetchColumn();

            if ($exists) {
                throw new Exception("Le joueur avec le numéro de licence $numeroLicence est déjà associé à ce match.");
            }

            // Insertion si tout est valide
            $stmt->execute([$numeroLicence, $idMatch, $poste, $titulaire]);
        }

        // Redirection après succès
        header('Location: PageMatch.php');
        exit;

    } catch (Exception $e) {
        // Afficher un message d'erreur utilisateur
        echo '<p class="error">Erreur : ' . htmlspecialchars($e->getMessage()) . '</p>';
    } catch (PDOException $e) {
        // Gestion des erreurs de la base de données
        echo '<p class="error">Erreur de la base de données : ' . htmlspecialchars($e->getMessage()) . '</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/AjouterMatchJoueur.css" rel="stylesheet">
    <title>Ajouter des joueurs au match</title>
</head>
<body>

    <h1>Ajouter des joueurs au match</h1>
    <a class="rtab" href="AjouterMatch.php">Retour au match</a>
    <div class ="explications">
        <p> Une équipe doit être constituée de 3 poursuiveurs, 2 batteurs, un gardien et un attrapeur.</p>
        <p> Il peut y avoir au maximum 4 remplaçants. </p> 
        <P> Les joueurs qui sont inscrits au match sont cochés dans Sélectionné et les personnes sur le terrain sont cochés dans Titulaire. </p>
        <p> Les joueurs cochés seulement dans Sélectionné sont remplaçants. </p>
    </div>  
    <form method="POST">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Poids</th>
                    <th>Taille</th>
                    <th>Poste</th>
                    <th>Titulaire</th>
                    <th>Sélectionné</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($joueurs as $joueur): ?>
                    <tr>
                        <td><?= htmlspecialchars($joueur['Prenom'] . ' ' . $joueur['Nom']); ?></td>
                        <td><?= htmlspecialchars($joueur['Poids']); ?> kg</td>
                        <td><?= htmlspecialchars($joueur['Taille']); ?> m</td>
                        <td>
                            <select name="poste[<?= $joueur['Numero_licence']; ?>]">
                                <option value="">-- Sélectionnez un poste --</option>
                                <option value="Poursuiveur">Poursuiveur</option>
                                <option value="Batteur">Batteur</option>
                                <option value="Gardien">Gardien</option>
                                <option value="Attrapeur">Attrapeur</option>
                            </select>
                        </td>
                        <td>
                            <input type="checkbox" class="checkbox bouton-titulaire" name="titulaire[<?= $joueur['Numero_licence']; ?>]" value="1">
                        </td>
                        <td>
                            <input type="checkbox" class="checkbox bouton-selectionne" name="selectionne[<?= $joueur['Numero_licence']; ?>]" value="1">
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <button type="submit">Ajouter les joueurs</button>
    </form>

</body>
</html>
