<?php
require('../bd/ConnexionBD.php');
require('../requetesSql.php');

$stmt = $linkpdo->prepare($select_match_concat_date_heure);
$stmt->execute(['current_datetime' => date('Y-m-d H:i:s')]);
$matchs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $linkpdo->prepare($select_joueur);
$stmt->execute();
$joueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getDataFromQuery($linkpdo, $requete, $joueur, $paramAttendu){
    $stmt = $linkpdo->prepare($requete);
    $stmt->execute([$joueur["Numero_licence"]]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result == FALSE){
        return "-";
    } else {
        $temp_value = $result[$paramAttendu];
        if (is_numeric($temp_value)) {
            $temp_value = (int)floatval($temp_value);
        }
        return $temp_value;
    }
}

$matchs_gagnes = 0;
$matchs_perdus = 0;
$matchs_nuls = 0;

foreach ($matchs as $match) {
    if ($match['Resultat_Equipe'] > $match['Resultat_Equipe_Adverse']) {
        $matchs_gagnes++;
    } elseif ($match['Resultat_Equipe'] < $match['Resultat_Equipe_Adverse']) {
        $matchs_perdus++;
    } else {
        $matchs_nuls++;
    }
}

$nombre_total_match = count($matchs);
$pourcentage_matchs_gagnes = $nombre_total_match > 0 ? round(($matchs_gagnes / $nombre_total_match) * 100, 2) : 0;
$pourcentage_matchs_perdus = $nombre_total_match > 0 ? round(($matchs_perdus / $nombre_total_match) * 100, 2) : 0;
$pourcentage_matchs_nuls = $nombre_total_match > 0 ? round(($matchs_nuls / $nombre_total_match) * 100, 2) : 0;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/Statistiques.css" rel="stylesheet">
    <title>Statistiques</title>
</head>
<body>

<header>Mes Statistiques</header>

    <div class="menu-container">
        <button class="menu-button">☰</button>
        <div class="menu-content">
            <a href="PageJoueurs.php">Mes Joueurs</a>
            <a href="PageMatch.php">Mes Matchs</a>
            <a href="PageStatistiques.php">Statistiques</a>
            <a href="PageAccueil.php">Accueil</a>
        </div>
    </div>

    <div class="grid">
        <div class="un">
            <h3> Nombre et pourcentage des matchs </h3>
            <table>
            <thead>
                <tr>
                    <th></th>
                    <th>Nombre</th>
                    <th>Pourcentage</th>
                </tr>
            </thead>
            <tbody>
            <tr>
                    <td class="type">Gagnés</td>
                    <td><?php echo $matchs_gagnes; ?></td>
                    <td><?php echo $pourcentage_matchs_gagnes; ?>%</td>
                </tr>
                <tr>
                    <td class="type">Perdus</td>
                    <td><?php echo $matchs_perdus; ?></td>
                    <td><?php echo $pourcentage_matchs_perdus; ?>%</td>
                </tr>
                <tr>
                    <td class="type">Nuls</td>
                    <td><?php echo $matchs_nuls; ?></td>
                    <td><?php echo $pourcentage_matchs_nuls; ?>%</td>
                </tr>
            </tbody>
            </table>
        </div>
        <div class="deux">
            <h3> Données par joueur </h3>
            <div style="display:flex;">
                <table>
                    <thead>
                        <tr>
                            <th>Joueur</th>
                            <th>Statut</th>
                            <th>Poste préféré</th>
                            <th>Sélections titulaire</th>
                            <th>Sélections remplaçant</th>
                            <th>Sélections consécutives</th>
                            <th>Moyenne évaluations</th>
                            <th>Matchs gagnés</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($joueurs as $joueur) :?>
                        <tr>
                            <td><?php echo htmlspecialchars($joueur['Nom']).' '.htmlspecialchars($joueur['Prenom']); ?></td>
                            <td><?php echo htmlspecialchars($joueur['Statut']); ?></td>
                            <td><?php echo htmlspecialchars(getDataFromQuery($linkpdo, $poste_prefere, $joueur, "Poste")); ?></td>
                            <td><?php echo htmlspecialchars(getDataFromQuery($linkpdo, $nb_titulaire, $joueur, "Titulaire")); ?></td>
                            <td><?php echo htmlspecialchars(getDataFromQuery($linkpdo, $nb_remplacant, $joueur, "Remplacant")); ?></td>
                            <td><?php echo htmlspecialchars(getDataFromQuery($linkpdo, $nb_selec_consecutive, $joueur, "ConsecutiveSelections")); ?></td>
                            <td><?php echo htmlspecialchars(getDataFromQuery($linkpdo, $moyenne_eval, $joueur, "Moyenne_Evaluation")); ?></td>
                            <td><?php echo htmlspecialchars(getDataFromQuery($linkpdo, $participation_match_gagne, $joueur, "Nombre_Participations_Matchs_Gagnes")); ?></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>

<a class="return" href="PageAccueil.php">Retour à l'accueil</a>

</body>
</html>