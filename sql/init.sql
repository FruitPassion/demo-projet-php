CREATE DATABASE IF NOT EXISTS ProjetPHP;

USE ProjetPHP;

CREATE TABLE IF NOT EXISTS Joueur(
   Numero_licence INT ,
   Nom VARCHAR(30) ,
   Prenom VARCHAR(30) ,
   Date_naissance DATE,
   Taille DECIMAL(4,1)  ,
   Poids DECIMAL(4,1)  ,
   Statut ENUM('Actif', 'Blessé', 'Suspendu', 'Absent') DEFAULT 'Actif',
   Photo VARCHAR(51),
   PRIMARY KEY(Numero_licence)
);

CREATE TABLE IF NOT EXISTS Match_(
   Id_Match INT AUTO_INCREMENT,
   Date_Match DATETIME,
   Heure DATETIME,
   Nom_Equipe_Adverse VARCHAR(30) ,
   Lieu_Rencontre ENUM('Domicile', 'Extérieur') DEFAULT 'Domicile',
   Resultat_Equipe INT,
   Resultat_Equipe_Adverse INT,
   PRIMARY KEY(Id_Match)
);

CREATE TABLE IF NOT EXISTS Commentaire(
   Id_Commentaire INT AUTO_INCREMENT,
   Numero_licence INT NOT NULL,
   PRIMARY KEY(Id_Commentaire),
   Texte VARCHAR(300) ,
   Date_commentaire DATETIME,
   FOREIGN KEY(Numero_licence) REFERENCES Joueur(Numero_licence)
);

CREATE TABLE IF NOT EXISTS Connexion(
   Id_Connexion INT AUTO_INCREMENT,
   Identifiant INT ,
   Mot_de_passe VARCHAR(50) ,
   PRIMARY KEY(Id_Connexion)
);

CREATE TABLE IF NOT EXISTS Participer(
   Numero_licence INT,
   Id_Match INT,
   Id_Equipe INT,
   Titulaire BOOLEAN,
   Evaluation DECIMAL(3,2),
   Poste VARCHAR(15) ,
   PRIMARY KEY(Numero_licence, Id_Match),
   FOREIGN KEY(Numero_licence) REFERENCES Joueur(Numero_licence),
   FOREIGN KEY(Id_Match) REFERENCES Match_(Id_Match)
);


