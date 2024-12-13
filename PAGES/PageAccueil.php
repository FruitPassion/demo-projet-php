<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LigueDorée</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background-color: antiquewhite;
        }
        header {
            background-color: antiquewhite;
            padding: 20px;
            font-size: 48px;
            font-weight: bold;
            border-bottom: 8px solid darkgoldenrod;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 40px;
            margin-top: 100px;
        }
        .card {
            width: 300px;
            height: 300px;
            border: 5px solid darkgoldenrod;
            border-radius: 15px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            text-decoration: none;
            color: black;
            transition: transform 0.2s, background-color 0.2s;
        }
        .card img {
            width: 100px;
            height: 100px;
            margin-bottom: 15px;
        }
        .card:hover {
            transform: scale(1.1);
            background-color: burlywood;
        }
        .card span {
            font-size: 32px;
            font-weight: bold;
        }
        .logout {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: red;
            color: white;
            padding: 20px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>LigueDorée</header>
    <button class="logout">⏻</button>
    <div class="container">
        <a href="joueurs.html" class="card">
            <img src="../IMG/team-icon.png" alt="Mes Joueurs">
            <span>Mes Joueurs</span>
        </a>
        <a href="matchs.html" class="card">
            <img src="../IMG/balai-icon.png" alt="Mes Matchs">
            <span>Mes Matchs</span>
        </a>
        <a href="statistique.html" class="card">
            <img src="../IMG/pourcentage-icon.png" alt="Statistique">
            <span>Statistique</span>
        </a>
    </div>
</body>
</html>
