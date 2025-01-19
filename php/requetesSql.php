<?php

//Requetes concernant les pages Joueur
    //Selection information d'un joueur
        $select_joueur = "SELECT Numero_licence, Nom, Prenom, Statut, photo FROM Joueur";
    //Selection information d'un joueur spécifique
        $select_joueur_spec = "SELECT * FROM Joueur WHERE Numero_licence = ?";
    //Selection du texte et de la date d'un commentaire
        $select_commentaire = "SELECT texte, date_commentaire FROM Commentaire WHERE numero_licence = ? ORDER BY date_commentaire DESC";
    //Verification si le joueur est dans un match
        $verif_match ="SELECT DateMatch FROM Participer NATURAL JOIN Match WHERE Numero_licence = ?";
    //Inserer un joueur dans la bd
        $insert_joueur = "INSERT INTO Joueur (nom, prenom, numero_licence, date_naissance, poids, taille, statut, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    //Inserer commentaire dans la bd
        $insert_commentaire = "INSERT INTO Commentaire (texte, numero_licence, date_commentaire) VALUES (?, ?, NOW())";
    //Update information joueur
        $update_joueur = "UPDATE Joueur SET Nom = ?, Prenom = ?, Statut = ?, date_naissance = ?, poids = ?, taille = ? WHERE Numero_licence = ?";
    //Update photo joueur
        $update_photo ="UPDATE Joueur SET Photo = ? WHERE Numero_licence = ?";
    //Suppression commentaire 
        $delete_commentaire = "DELETE FROM Commentaire WHERE numero_licence = ?";
    //Suppresion joueur
        $delete_joueur = "DELETE FROM Joueur WHERE Numero_licence = ?";


//Requetes concernant les pages Match
    //Selection information match ordre decroissant
        $select_match_desc ="SELECT Date_Match, Heure, Lieu_Rencontre, Nom_Equipe_Adverse, Resultat_Equipe, Resultat_Equipe_Adverse, Id_Match FROM Match_ ORDER BY Date_Match DESC";
    //Selection match
        $select_match ="SELECT Date_Match, Heure, Lieu_Rencontre, Nom_Equipe_Adverse, Resultat_Equipe, Resultat_Equipe_Adverse, Id_Match FROM Match_";
    //Selection joueur actif
        $select_joueur_actif="SELECT Numero_licence, Nom, Prenom, Taille, Poids FROM Joueur WHERE Statut = 'Actif'";
    //Selection joueur inscrit pour le match
        $select_joueur_inscrit="SELECT COUNT(*) FROM Participer WHERE Numero_licence = ? AND Id_Match = ?";
    //Selection toutes info joueur
        $select_infos_joueur ="SELECT * FROM Joueur WHERE Numero_licence = ?";
    //Selection evaluation
        $select_evaluation ="SELECT Evaluation FROM Participer WHERE Numero_licence = ? AND Id_Match = ?";
    //Selection match specifique
        $select_match_spec="SELECT * FROM Match_ WHERE Id_Match = ?";
    //Selection joueurs a un match associé
        $select_joueurs_match="SELECT p.Numero_Licence, j.Nom, j.Prenom, p.Titulaire, p.Poste, p.Evaluation FROM Participer p
                               INNER JOIN Joueur j ON p.Numero_Licence = j.Numero_Licence WHERE p.Id_Match = ? AND p.Poste IS NOT NULL";
    //Insertion dans participer
        $insert_participer="INSERT INTO Participer (Numero_licence, Id_Match, Poste, Titulaire) VALUES (?, ?, ?, ?)";
    //Insertion dans match
        $insert_match ="INSERT INTO Match_ (Date_Match, Heure, Lieu_Rencontre, Nom_Equipe_Adverse) VALUES (?, ?, ?, ?)";
    //Update evaluation
        $update_evaluation="UPDATE Participer SET Evaluation = ? WHERE Numero_licence = ? AND Id_Match = ?";
    //Update match
        $update_match="UPDATE Match_ SET Date_Match = ?, Heure = ?, Lieu_Rencontre = ?, Nom_Equipe_Adverse = ?, Resultat_Equipe = ?, 
                        Resultat_Equipe_Adverse = ? WHERE Id_Match = ?";
    //Suppresion match de participer
        $delete_match_participer="DELETE FROM Participer WHERE Id_Match = ?";
    //Suppresion match
        $delete_match="DELETE FROM Match_ WHERE Id_Match = ?";

    
//Requetes concernant la pages Statistiques
    //Selection match avec date et heure concatener
        $select_match_concat_date_heure="SELECT Date_Match, Heure, Lieu_Rencontre, Nom_Equipe_Adverse, Resultat_Equipe, Resultat_Equipe_Adverse, Id_Match FROM Match_ WHERE CONCAT(Date_Match,' ',Heure) < :current_datetime";
    //Selection des données par joueur
        $poste_prefere ="SELECT p.Poste 
                        FROM Participer p JOIN Joueur j ON p.Numero_licence = j.Numero_licence WHERE p.Id_Match = (
                        SELECT MAX(Id_Match) FROM Participer WHERE Numero_licence = p.Numero_licence )
                        AND j.Numero_licence = ?";
    //Selection du nombre de titularisation d'un joueur               
        $nb_titulaire ="SELECT COUNT(p.Titulaire) AS Titulaire
                        FROM Participer p
                        JOIN Joueur j ON p.Numero_licence = j.Numero_licence
                        WHERE p.Titulaire = 1
                        AND j.Numero_licence = ?
                        GROUP BY j.Numero_licence";
        //Selection du nombre de remplacement d'un joueur               
        $nb_remplacant ="SELECT COUNT(p.Titulaire) AS Remplacant
                        FROM Participer p
                        JOIN Joueur j ON p.Numero_licence = j.Numero_licence
                        WHERE p.Titulaire = 0
                        AND j.Numero_licence = ?
                        GROUP BY j.Numero_licence";
        //Selection du nombre de selection consécutive               
        $nb_selec_consecutive ="WITH OrderedMatches AS (SELECT p.Numero_licence, m.Id_Match, m.Date_Match,
                                ROW_NUMBER() OVER (PARTITION BY p.Numero_licence ORDER BY m.Date_Match) AS rn
                                FROM Participer p JOIN Match_ m ON p.Id_Match = m.Id_Match WHERE p.Numero_licence = ? ),
                                ConsecutiveGroups AS (SELECT  Numero_licence, Id_Match, Date_Match, rn, DATE_SUB(Date_Match, INTERVAL rn DAY) AS grp
                                FROM OrderedMatches)
                                SELECT Numero_licence, COUNT(*) AS ConsecutiveSelections FROM ConsecutiveGroups
                                GROUP BY Numero_licence, grp ORDER BY ConsecutiveSelections DESC LIMIT 1";
        //Selection moyenne evaluation d'un joueur              
        $moyenne_eval ="SELECT p.Numero_licence, AVG(p.Evaluation) AS Moyenne_Evaluation
                        FROM Participer p
                        JOIN Joueur j ON p.Numero_licence = j.Numero_licence
                        JOIN Match_ m ON p.Id_Match = m.Id_Match
                        WHERE p.Numero_licence = ?  
                        AND m.Date_Match < CURDATE()
                        GROUP BY p.Numero_licence";
        //Selection du nombre de participation a un match gagné d'un joueur               
        $participation_match_gagne="SELECT 
                                    p.Numero_licence, COUNT(*) AS Nombre_Participations_Matchs_Gagnes
                                    FROM Participer p
                                    JOIN Joueur j ON p.Numero_licence = j.Numero_licence
                                    JOIN Match_ m ON p.Id_Match = m.Id_Match
                                    WHERE p.Numero_licence = ?
                                    AND m.Resultat_Equipe > m.Resultat_Equipe_Adverse
                                    GROUP BY p.Numero_licence";

?>