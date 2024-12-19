# Projet_PHP
Bienvenue dans la LigueDorée  <img src="https://media.tenor.com/6WP5bQ455YwAAAAM/passe-quadribol-quidditch.gif" height=50 >

Ici est entreposé notre projet en PHP fait à l'occasion du cours R3.01. :)

# Explication Docker

## Installation et mise en place

Cliquer [ici](https://tech-talk.info/forum/post/f3b760a9-3221-4c14-a8a6-ec7444fd8c81/) pour accéder au tutoriel.

## Lancement et ouverture du projet

Ouvrir l'application Docker Desktop et la laisser tourner en arrière-plan pendant l'utilisation du docker.

Une fois sur VSCode, ouvrir un terminal et entrer cette commande :   
``` docker-compose up -d --build```   
On peut maintenant travailler sur le projet.

L'option ```-d``` sert à lancer le chargement en arrière plan.  
L'option ```--build``` sert à construire le projet. Cette option est nécessaire pour le premier lancement uniquement.  
Si le fichier *./apache/Dockerfile* est changé, il faudra relancer la commande avec.

Pour le fermer il suffit de marquer dans le terminal la commande suivante:  
```docker-compose down```   
On peut aussi fermer Docker Desktop.

## Explication du docker-compose

Il y a deux parties: les services et les volumes.

Chaque service correspond à un aspect de l'application.  
Nous en disposons de trois:
- web : il permet de faire tourner apache et php
- bd : il permet de créer mariadb
- phpmyadmin : il permet de créer une interface pour gérer la bd.

Les volumes sont liés à un service et servent à conserver les données de celui-ci.  
Pour en supprimer le contenu il faut ajouter une option lorsqu'on ferme le docker:  
```docker-compose down -v```

## Ce qui va changer pour notre projet

La mise en place de ces services nous permettra de ne pas avoir à lancer XAMPP et de pouvoir assurer la portabilité de notre application web.

La racine de notre projet n'est plus le dossier ```Projet_PHP``` mais le dossier ```php```.  
Le chemin d'accès de l'application web est ```localhost/pages/..```.  
Le chemin d'accès de phpmyadmin ```localhost/8080```.

---
Un grand merci à [FruitPassion](https://github.com/FruitPassion) pour son aide et ses explications ! 
