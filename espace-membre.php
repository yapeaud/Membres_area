<?php
session_start();
if (!isset($_SESSION['login'])) {header ('Location: index.php');exit();}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace membre</title>
</head>
<body>
    <p><strong>ESPACE MEMBRE</strong></p>
    Bienvenue <?php echo htmlentities(trim($_SESSION['login'])); ?> !<br>
    <a href="deconnexion.php">Déconnexion</a>
</body>
</html>