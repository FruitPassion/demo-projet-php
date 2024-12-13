
CREATE TABLE Joueur(
   Numero_licence INT ,
   Nom VARCHAR(30) ,
   Prenom VARCHAR(30) ,
   Date_naissance DATE,
   Taille DECIMAL(4,1)  ,
   Poids DECIMAL(4,1)  ,
   Statut VARCHAR(15) ,
   PRIMARY KEY(Numero_licence)
);

CREATE TABLE Match_(
   Id_Match INT AUTO_INCREMENT,
   Date_Match DATETIME,
   Nom_Equipe_Adverse VARCHAR(30) ,
   Lieu_Rencontre VARCHAR(30) ,
   Resultat_Match VARCHAR(10) ,
   PRIMARY KEY(Id_Match)
);

CREATE TABLE Commentaire(
   Id_Commentaire INT AUTO_INCREMENT,
   Numero_licence INT NOT NULL,
   PRIMARY KEY(Id_Commentaire),
   FOREIGN KEY(Numero_licence) REFERENCES Joueur(Numero_licence)
);

CREATE TABLE Connexion(
   Id_Connexion INT AUTO_INCREMENT,
   Identifiant INT ,
   Mot_de_passe VARCHAR(50) ,
   PRIMARY KEY(Id_Connexion)
);

CREATE TABLE Participer(
   Numero_licence INT,
   Id_Match INT,
   Id_Equipe INT,
   Titulaire BOOLEAN,
   Evaluation DECIMAL(3,2)  ,
   Poste VARCHAR(15) ,
   PRIMARY KEY(Numero_licence, Id_Match),
   FOREIGN KEY(Numero_licence) REFERENCES Joueur(Numero_licence),
   FOREIGN KEY(Id_Match) REFERENCES Match_(Id_Match)
);
