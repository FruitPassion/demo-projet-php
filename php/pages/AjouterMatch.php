<?php
// Inclure la connexion à la base de données
require('../bd/ConnexionBD.php');

// Récupérer les joueurs actifs
$stmt = $linkpdo->query("SELECT Numero_licence, Nom, Prenom FROM Joueur WHERE Statut = 'Actif'");
$joueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gestion du formulaire d'ajout de match
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Date_Match'], $_POST['Heure'], $_POST['Lieu_rencontre'], $_POST['Nom_Equipe_Adverse'], 
        $_POST['Resultat_Equipe'], $_POST['Resultat_Equipe_Adverse'], $_POST['poste_poursuiveur'], 
        $_POST['poste_batteur'], $_POST['poste_gardien'], $_POST['poste_attrapeur'], $_POST['remplacants'])) {

        // Récupération des informations du formulaire
        $Date = $_POST['Date_Match'];
        $Heure = $_POST['Heure'];
        $Lieu_rencontre = $_POST['Lieu_rencontre'];
        $Nom_Equipe_Adverse = $_POST['Nom_Equipe_Adverse'];
        $Resultat_Equipe = $_POST['Resultat_Equipe'];
        $Resultat_Equipe_Adverse = $_POST['Resultat_Equipe_Adverse'];

        // Récupération des joueurs par poste
        $poursuiveurs = $_POST['poste_poursuiveur'];
        $batteurs = $_POST['poste_batteur'];
        $gardien = $_POST['poste_gardien'];
        $attrapeur = $_POST['poste_attrapeur'];
        $remplacants = $_POST['remplacants'];

        // Vérification des doublons dans les joueurs
        $joueurs_titulaires = array_merge($poursuiveurs, $batteurs, [$gardien], [$attrapeur]);
        $joueurs_selectionnes = array_merge($joueurs_titulaires, $remplacants);
        if (count($joueurs_selectionnes) !== count(array_unique($joueurs_selectionnes))) {
            echo "<script>alert('Un même joueur ne peut pas être sélectionné plusieurs fois.');</script>";
        } else {
            try {
                // Insertion dans la table Match_
                $stmt = $linkpdo->prepare('INSERT INTO Match_ (Date_Match, Heure, Lieu_Rencontre, Nom_Equipe_Adverse, Resultat_Equipe, Resultat_Equipe_Adverse) 
                                           VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->execute([$Date, $Heure, $Lieu_rencontre, $Nom_Equipe_Adverse, $Resultat_Equipe, $Resultat_Equipe_Adverse]);

                // Récupérer l'ID du match inséré
                $idMatch = $linkpdo->lastInsertId();

                // Insertion des joueurs associés à ce match avec leur poste
                $stmt = $linkpdo->prepare('INSERT INTO Participer (Numero_licence, Id_Match, Poste, Titulaire) VALUES (?, ?, ?, ?)');

                // Enregistrement des joueurs titulaires pour chaque poste
                foreach ($poursuiveurs as $poursuiveur) {
                    $stmt->execute([$poursuiveur, $idMatch, 'Poursuiveur', 1]);
                }
                foreach ($batteurs as $batteur) {
                    $stmt->execute([$batteur, $idMatch, 'Batteur', 1]);
                }
                $stmt->execute([$gardien, $idMatch, 'Gardien', 1]);
                $stmt->execute([$attrapeur, $idMatch, 'Attrapeur', 1]);

                // Enregistrement des remplaçants
                foreach ($remplacants as $remplacant) {
                    $stmt->execute([$remplacant, $idMatch, 'Remplaçant', 0]);
                }

                // Redirection après l'ajout
                header('Location: PageMatch.php');
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
    <script>
        // Validation JavaScript pour éviter les doublons
        function validerSelection() {
            const selections = [
                ...document.querySelectorAll("select[name='poste_poursuiveur[]']"),
                ...document.querySelectorAll("select[name='poste_batteur[]']"),
                document.querySelector("select[name='poste_gardien']"),
                document.querySelector("select[name='poste_attrapeur']"),
                ...document.querySelectorAll("select[name='remplacants[]']")
            ];

            const valeurs = selections.map(sel => sel.value);
            const valeursUniques = new Set(valeurs);

            if (valeurs.length !== valeursUniques.size) {
                alert('Un même joueur ne peut pas être sélectionné plusieurs fois.');
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

    <h1>Ajouter un match</h1>
    <a class="rtab" href="PageMatch.php">Retour au tableau</a>

    <form method="POST" enctype="multipart/form-data" onsubmit="return validerSelection();">
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

        <label for="Resultat_Equipe">Résultat équipe :</label>
        <input type="number" id="Resultat_Equipe" name="Resultat_Equipe" step="1" required>
        <label for="Resultat_Equipe_Adverse">Résultat adversaire :</label>
        <input type="number" id="Resultat_Equipe_Adverse" name="Resultat_Equipe_Adverse" step="1" required>

        <label>Choisir des joueurs pour chaque poste :</label>

        <div class="player-types">
            <label for="poste_poursuiveur">Poursuiveurs (3) :</label>
            <?php for ($i = 0; $i < 3; $i++): ?>
                <select name="poste_poursuiveur[]" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($joueurs as $joueur): ?>
                        <option value="<?= $joueur['Numero_licence']; ?>">
                            <?= htmlspecialchars($joueur['Prenom'] . ' ' . $joueur['Nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endfor; ?>

            <label for="poste_batteur">Batteurs (2) :</label>
            <?php for ($i = 0; $i < 2; $i++): ?>
                <select name="poste_batteur[]" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($joueurs as $joueur): ?>
                        <option value="<?= $joueur['Numero_licence']; ?>">
                            <?= htmlspecialchars($joueur['Prenom'] . ' ' . $joueur['Nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endfor; ?>

            <label for="poste_gardien">Gardien :</label>
            <select id="poste_gardien" name="poste_gardien" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($joueurs as $joueur): ?>
                    <option value="<?= $joueur['Numero_licence']; ?>">
                        <?= htmlspecialchars($joueur['Prenom'] . ' ' . $joueur['Nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="poste_attrapeur">Attrapeur :</label>
            <select id="poste_attrapeur" name="poste_attrapeur" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($joueurs as $joueur): ?>
                    <option value="<?= $joueur['Numero_licence']; ?>">
                        <?= htmlspecialchars($joueur['Prenom'] . ' ' . $joueur['Nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="remplacants">Remplaçants (4) :</label>
            <?php for ($i = 0; $i < 4; $i++): ?>
                <select name="remplacants[]" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($joueurs as $joueur): ?>
                        <option value="<?= $joueur['Numero_licence']; ?>">
                            <?= htmlspecialchars($joueur['Prenom'] . ' ' . $joueur['Nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endfor; ?>
        </div>

        <button type="submit">Ajouter le match</button>
    </form>

</body>
</html>

