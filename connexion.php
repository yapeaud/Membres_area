<?php
// On inclut connect.php
include("connect.php");

// On vérifie que le visiteur a correctement saisi puis envoyé le formulaire
if (isset($_POST['connexion']) && $_POST['connexion'] == 'connexion') {
    if (isset($_POST['login']) && !empty($_POST['login']) && isset($_POST['pwd']) && !empty($_POST['pwd'])) {
        // On se connecte à la BDD avec MySQLi
        $connexion = new mysqli(SERVER, LOGIN, MDP, BDD);

        // Vérifie la connexion
        if ($connexion->connect_error) {
            die("La connexion à la base de données a échoué : " . $connexion->connect_error);
        }

        print "Connexion BDD réussie puis";
        echo "<br>";
    }
}
?>
<?php
// On inclut connect.php
include("connect.php");

// On vérifie que le visiteur a correctement saisi puis envoyé le formulaire
if (isset($_POST['connexion']) && $_POST['connexion'] == 'Connexion') {
    if (isset($_POST['login']) && !empty($_POST['login']) && isset($_POST['pwd']) && !empty($_POST['pwd'])) {
        // On se connecte à la BDD avec MySQLi
        $connexion = new mysqli(SERVER, LOGIN, MDP, BDD);

        // Vérifie la connexion
        if ($connexion->connect_error) {
            die("La connexion à la base de données a échoué : " . $connexion->connect_error);
        }

        print "Connexion BDD réussie puis";
        echo "<br/>";
        
        // On prépare la requête SQL en utilisant des paramètres sécurisés pour éviter les injections SQL
        $sql = 'SELECT count(*) FROM members_area WHERE id=? AND md5=?';
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param('ss', $_POST['login'], md5($_POST['pwd']));
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        // Si on obtient une réponse, alors l'utilisateur est un membre
        // On ouvre une session pour cet utilisateur et on le connecte à l'espace membre
        if ($count == 1) {
            session_start();
            $_SESSION['login'] = $_POST['login'];
            header('Location: espace-membre.php');
            exit();
        } elseif ($count == 0) {
            // Si le visiteur a saisi un mauvais login ou mot de passe, on ne trouve aucune réponse
            $erreur = 'Login ou mot de passe non reconnu !';
            echo $erreur;
            echo "<br/><a href=\"accueil.php\">Accueil</a>";
            exit();
        } else {
            // Sinon, il existe un problème dans la base de données
            $erreur = 'Plusieurs membres ont<br/>les mêmes login et mot de passe !';
            echo $erreur;
            echo "<br/><a href=\"accueil.php\">Accueil</a>";
            exit();
        }
    } else {
        $erreur = 'Erreur de saisie !<br/>Au moins un des champs est vide !';
        echo $erreur;
        echo "<br/><a href=\"accueil.php\">Accueil</a>";
        exit();
    }
}
?>
