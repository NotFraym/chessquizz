<?php include 'session_start.php';?>
<!DOCTYPE html>
<html>
<?php
include 'include/head.php';
include 'include/header.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_nom'])) {
    header('Location: connexion.php'); // Redirigez vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

// Vérifiez si le formulaire de modification du mot de passe a été soumis
if (isset($_POST['ok'])) {
    // Récupérez les données du formulaire
    $mdp_actuel = $_POST['mdp'];
    $nouveau_mdp = $_POST['mdp1'];
    $verification_mdp = $_POST['mdp2'];

    // Établir une connexion à la base de données (utilisez vos propres paramètres de connexion)
    include 'config.php';
    $mysqli = new mysqli($shost, $user, $pass, $dbname);

    // Vérifiez la connexion à la base de données
    if ($mysqli->connect_error) {
        die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
    }

    // Récupérez le nom d'utilisateur de la session
    $nom_utilisateur = $_SESSION['user_nom'];

    // Préparez une requête pour obtenir le hash du mot de passe actuel de l'utilisateur
    $query = "SELECT mdp FROM utilisateur WHERE user = ?";
    $stmt = $mysqli->prepare($query);

    // Lier le nom d'utilisateur au paramètre de la requête
    $stmt->bind_param("s", $nom_utilisateur);

    // Exécutez la requête
    $stmt->execute();

    // Lier le résultat de la requête à une variable
    $stmt->bind_result($mdp_hash);

    // Récupérez le résultat de la requête
    $stmt->fetch();

    // Fermez la première requête SELECT
    $stmt->close();

    // Vérifiez le mot de passe actuel
    $mdp_actuel_hash = hash('sha256', $mdp_actuel);

    if ($mdp_actuel_hash === $mdp_hash) {
        // Vérifiez que les nouveaux mots de passe et la vérification correspondent
        if ($nouveau_mdp === $verification_mdp) {
            // Vérifiez que les nouveaux mots de passe ne sont pas similaires au mot de passe actuel
            $nouveau_mdp_hash = hash('sha256', $nouveau_mdp);

            if ($nouveau_mdp_hash !== $mdp_actuel_hash) {
                // Mettez à jour le mot de passe de l'utilisateur
                $update_query = "UPDATE utilisateur SET mdp = ? WHERE user = ?";
                $update_stmt = $mysqli->prepare($update_query);
                $update_stmt->bind_param("ss", $nouveau_mdp_hash, $nom_utilisateur);

                if ($update_stmt->execute()) {
                    echo "Votre mot de passe a bien été changé !";
                } else {
                    echo "Une erreur s'est produite lors de la mise à jour du mot de passe : " . $update_stmt->error;
                }
                $update_stmt->close(); // Fermez la requête UPDATE
            } else {
                echo "Le nouveau mot de passe doit être différent du mot de passe actuel. Veuillez réessayer.";
            }
        } else {
            echo "Le nouveau mot de passe et la vérification ne correspondent pas. Veuillez réessayer.";
        }
    } else {
        echo "Mot de passe actuel incorrect. Veuillez réessayer.";
    }

    // Fermez la connexion
    $mysqli->close();
}

include 'include/footer.php';
?>
