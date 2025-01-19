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
   Date_Match DATE,
   Heure TIME,
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
   Identifiant VARCHAR(50)  ,
   Mot_de_passe TEXT(50) ,
   PRIMARY KEY(Id_Connexion)
);

CREATE TABLE IF NOT EXISTS Participer(
   Numero_licence INT,
   Id_Match INT,
   Titulaire BOOLEAN,
   Evaluation DECIMAL(3,2),
   Poste VARCHAR(15) ,
   PRIMARY KEY(Numero_licence, Id_Match),
   FOREIGN KEY(Numero_licence) REFERENCES Joueur(Numero_licence),
   FOREIGN KEY(Id_Match) REFERENCES Match_(Id_Match)
);

INSERT INTO Joueur 
(Numero_licence, Nom, Prenom, Date_naissance, Taille, Poids, Statut, Photo) 
VALUES 
(11111111, 'Potter', 'Harry', '1980-07-31', 170.0, 67.0, 'Actif', '../img/HarryPotter.png'),
(22222222, 'Weasley', 'Ron', '1980-03-01', 173.0, 83.0, 'Actif', '../img/RonWeasley.png'),
(33333333, 'Weasley', 'George', '1980-04-01', 183.0, 68.0, 'Actif', '../img/GeorgeWeasley.png'),
(44444444, 'Weasley', 'Fred', '1980-04-01', 184.0, 81.0, 'Actif', '../img/FredWeasley.png'),
(55555555, 'Bell', 'Katie', '1978-07-01', 165.0, 56.0, 'Actif', '../img/KatieBell.png'),
(66666666, 'Weasley', 'Ginny', '1981-08-11', 158.0, 62.0, 'Actif', '../img/GinnyWeasley.png'),
(77777777, 'Chang', 'Cho', '1979-04-05', 171.0, 67.0, 'Suspendu', '../img/ChoChang.png'),
(88888888, 'Malefoy', 'Drago', '1980-06-05', 178.0, 68.0, 'Absent', '../img/DragoMalefoy.png'),
(99999999, 'Krum', 'Viktor', '1980-07-31', 176.0, 89.0, 'Actif', '../img/VictorKrum.png'),
(12121212, 'Diggory', 'Cédric', '1977-06-14', 172.0, 72.0, 'Actif', '../img/CedricDiggory.png');

INSERT INTO Match_
(Id_Match, Date_Match, Heure, Nom_Equipe_Adverse, Lieu_Rencontre, Resultat_Equipe, Resultat_Equipe_Adverse)
VALUES
(55,'2025-01-19','11:57:00','Les aigles de Ravenclaw','Extérieur',12,85),
(66,'2025-01-18','20:00:00','Les serpents de Slytherin','Domicile',150,70),
(77,'2025-02-05','20:00:00','Les lions de Gryffondor','Domicile',0,0);


INSERT INTO Connexion (Identifiant, Mot_de_passe)
VALUES ("user", '$2y$10$e3.UHPyvAI2ihZqvBwygJ.1QRO50GuzJXlraK3/laTeLzdFE9oxfe');

INSERT INTO Participer
(Numero_licence, Id_Match, Titulaire, Evaluation, Poste)
VALUES
(11111111,55,1,1,'Attrapeur'),
(12121212,55,1,5,'Batteur'),
(12121212,66,1,4,'Batteur'),
(22222222,55,1,1,'Poursuiveur'),
(22222222,66,1,2,'Poursuiveur'),
(33333333,55,1,2,'Poursuiveur'),
(33333333,66,1,3,'Poursuiveur'),
(44444444,55,1,5,'Poursuiveur'),
(44444444,66,1,3,'Poursuiveur'),
(55555555,55,1,2,'Batteur'),
(55555555,66,1,1,'Batteur'),
(66666666,55,1,5,'Gardien'),
(66666666,66,1,3,'Gardien'),
(99999999,55,0,5,'Attrapeur'),
(99999999,66,1,2,'Attrapeur');
