<?php include "session_start.php"; ?>

<!DOCTYPE html>
<html>


<?php 
include 'include/head.php';
include 'include/header.php';

// Vérifiez si le formulaire de connexion a été soumis
if (isset($_POST['ok'])) {
    // Récupérez les données du formulaire
    $nom_utilisateur = $_POST['nom'];
    $mdp_entre = $_POST['mdp'];

    // Établissez la connexion à la base de données (utilisez vos propres paramètres de connexion)
    include 'config.php';
    $mysqli = new mysqli($shost, $user, $pass, $dbname);

    // Vérifiez la connexion à la base de données
    if ($mysqli->connect_error) {
        die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
    }

    // Préparez une requête pour obtenir le hash du mot de passe et le statut de l'utilisateur
    $query = "SELECT mdp, statut FROM utilisateur WHERE user = ?";
    $stmt = $mysqli->prepare($query);

    // Lier le nom d'utilisateur au paramètre de la requête
    $stmt->bind_param("s", $nom_utilisateur);

    // Exécutez la requête
    $stmt->execute();

    // Lier le résultat de la requête à des variables
    $stmt->bind_result($mdp_hash, $user_statut);

    // Récupérez le résultat de la requête
    if ($stmt->fetch()) {
        $mdp_entre_hash = hash('sha256', $mdp_entre);

        if ($mdp_entre_hash === $mdp_hash) {
            // Démarrer la session
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            // Stockez le nom d'utilisateur et le statut dans la session
            $_SESSION['user_nom'] = $nom_utilisateur;
            $_SESSION['user_statut'] = $user_statut;

            // Redirigez en fonction du statut
            
            header('Location: index.php'); // Ou toute autre page après la connexion réussie
            exit();
            
        } else {
            echo "Mot de passe incorrect. Veuillez réessayer.";
        }
    } else {
        echo "Nom d'utilisateur incorrect. Veuillez réessayer.";
    }

    // Fermez la connexion et la requête $stmt
    $stmt->close();
    $mysqli->close();
} else {
    header('Location: connexion.php');
    exit();
}

include 'include/footer.php';
?>
</html>