<?php
// On inclut le fichier qui contient nom_de_serveur, nom_bdd, login et password d'accès à la bdd mysql
include("connect.php");

// On vérifie que le visiteur a correctement envoyé le formulaire
if (isset($_POST['inscription']) && $_POST['inscription'] == 'Inscription') {
    // On teste l'existence des variables et on vérifie qu'elles ne sont pas vides
    if (
        (isset($_POST['login']) && !empty($_POST['login'])) &&
        (isset($_POST['mdp1']) && !empty($_POST['mdp1'])) &&
        (isset($_POST['mdp2']) && !empty($_POST['mdp2']))
    ) {
        // Si les variables existent, on vérifie que les deux mots de passe sont identiques
        if ($_POST['mdp1'] != $_POST['mdp2']) {
            $erreur = 'Les 2 mots de passe sont différents.';
            echo $erreur;
            echo "<br/><a href=\"accueil.php\">Accueil</a>";
            exit();
        } else {
            // Si les deux mots de passe sont identiques, on se connecte à la bdd avec MySQLi
            $connexion = new mysqli(SERVER, LOGIN, MDP, BDD);

            // Vérifie la connexion
            if ($connexion->connect_error) {
                die("La connexion à la base de données a échoué : " . $connexion->connect_error);
            }

            print "Connexion BDD réussie puis";
            echo "<br/>";

            // On prépare la requête SQL en utilisant des paramètres sécurisés pour éviter les injections SQL
            $sqlCheckExistence = 'SELECT count(*) FROM members_area WHERE id=?';
            $stmtCheckExistence = $connexion->prepare($sqlCheckExistence);
            $stmtCheckExistence->bind_param('s', $_POST['login']);
            $stmtCheckExistence->execute();
            $stmtCheckExistence->bind_result($count);
            $stmtCheckExistence->fetch();
            $stmtCheckExistence->close();

            // Si aucun autre login identique existe, on inscrit ce nouveau login
            if ($count == 0) {
                $sqlInsert = 'INSERT INTO members_area VALUES (NULL, ?, ?)';
                $stmtInsert = $connexion->prepare($sqlInsert);
                $hashedPassword = md5($_POST['mdp1']);
                $stmtInsert->bind_param('ss', $_POST['login'], $hashedPassword);
                $stmtInsert->execute();
                $stmtInsert->close();

                $erreur = 'Inscription réussie !';
                echo $erreur;
                echo "<br/><a href=\"accueil.php\">Accueil</a>";
                exit();
            } else {
                // Sinon, on n'inscrit pas ce login
                $erreur = 'Echec de l\'inscription !<br/>Un membre possède déjà ce login !';
                echo $erreur;
                echo "<br/><a href=\"accueil.php\">Accueil</a>";
                exit();
            }
        }
    } else {
        // Si au moins un des champs est vide
        $erreur = 'Echec de l\'inscription !<br/>Au moins un des champs est vide !';
        echo $erreur;
        echo "<br/><a href=\"accueil.php\">Accueil</a>";
        exit();
    }
}
?>
