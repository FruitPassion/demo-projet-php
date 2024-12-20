<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Match</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        .container {
            position: relative;
            width: 800px;
            height: 500px;
            background: url('field-placeholder.png') no-repeat center center;
            background-size: contain;
        }
        .player-button {
            position: absolute;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #fff;
            border: 2px solid #00cc00;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }
        .player-button span {
            font-size: 20px;
            color: #00cc00;
        }
        .top-bar {
            position: absolute;
            top: 10px;
            width: 100%;
            display: flex;
            justify-content: space-between;
        }
        .top-bar button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .home-button {
            background-color: #ff4d4d;
            color: white;
        }
        .validate-button {
            background-color: #4caf50;
            color: white;
        }
        .substitutes {
            position: absolute;
            bottom: -80px;
            width: 100%;
            display: flex;
            justify-content: space-evenly;
        }
        .substitutes .player-button {
            background-color: #ccc;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-bar">
            <button class="home-button" onclick="location.href='PageAcceuil.php'">X</button>
            <button class="validate-button" onclick="location.href='PageJoueurs.php'">VALIDER</button>
        </div>

        <!-- Joueurs sur Terrain -->
        <div class="player-button" style="top: 50%; left: 75%;" onclick="location.href='Gardien.html'"><span>+</span></div>
        <div class="player-button" style="top: 50%; left: 62%;" onclick="location.href='Attaquant1.html'"><span>+</span></div>
        <div class="player-button" style="top: 65%; left: 55%;" onclick="location.href='Attaquant2.html'"><span>+</span></div>
        <div class="player-button" style="top: 35%; left: 55%;" onclick="location.href='Attaquant3.html'"><span>+</span></div>
        <div class="player-button" style="top: 60%; left: 40%;" onclick="location.href='Batteur1.html'"><span>+</span></div>
        <div class="player-button" style="top: 40%; left: 40%;" onclick="location.href='Batteur2.html'"><span>+</span></div>
        <div class="player-button" style="top: 50%; left: 30%;" onclick="location.href='Poursuiveur.html'"><span>+</span></div>

        <!-- Remplacant Section -->
        <div class="Remplacant">
        <div class="player-button" style="top: 95%; left: 80%;" onclick="location.href='RemplacantGardien.html'"><span>+</span></div>
        <div class="player-button" style="top: 95%; left: 70%;" onclick="location.href='RemplacantAttaquant1.html'"><span>+</span></div>
        <div class="player-button" style="top: 95%; left: 60%;" onclick="location.href='RemplacantAttaquant2.html'"><span>+</span></div>
        <div class="player-button" style="top: 95%; left: 50%;" onclick="location.href='RemplacantAttaquant3.html'"><span>+</span></div>
        <div class="player-button" style="top: 95%; left: 40%;" onclick="location.href='RemplacantBatteur1.html'"><span>+</span></div>
        <div class="player-button" style="top: 95%; left: 30%;" onclick="location.href='RemplacantBatteur2.html'"><span>+</span></div>
        <div class="player-button" style="top: 95%; left: 20%;" onclick="location.href='ReplacantPoursuiveur.html'"><span>+</span></div>
        </div>
    </div>
</body>
</html>
