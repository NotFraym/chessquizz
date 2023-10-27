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

// Vérifiez si le formulaire de modification du nom d'utilisateur a été soumis
if (isset($_POST['ok'])) {
    // Récupérez les données du formulaire
    $nouveau_nom_utilisateur = $_POST['user1'];
    $verification_nom_utilisateur = $_POST['user2'];

    // Vérifiez que les nouveaux noms d'utilisateur et la vérification correspondent
    if ($nouveau_nom_utilisateur === $verification_nom_utilisateur) {
        // Établir une connexion à la base de données (utilisez vos propres paramètres de connexion)
        include 'config.php';
        $mysqli = new mysqli($shost, $user, $pass, $dbname);

        // Vérifiez la connexion à la base de données
        if ($mysqli->connect_error) {
            die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
        }

        // Récupérez le nom d'utilisateur actuel de la session
        $nom_utilisateur_actuel = $_SESSION['user_nom'];

        // Vérifiez que le nouveau nom d'utilisateur est différent de l'ancien
        if ($nouveau_nom_utilisateur !== $nom_utilisateur_actuel) {
            // Vérifiez que le nouveau nom d'utilisateur n'existe pas déjà dans la base de données
            $query = "SELECT id FROM utilisateur WHERE user = ?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("s", $nouveau_nom_utilisateur);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                // Mettez à jour le nom d'utilisateur de l'utilisateur
                $update_query = "UPDATE utilisateur SET user = ? WHERE user = ?";
                $update_stmt = $mysqli->prepare($update_query);
                $update_stmt->bind_param("ss", $nouveau_nom_utilisateur, $nom_utilisateur_actuel);

                if ($update_stmt->execute()) {
                    // Mettez à jour le nom d'utilisateur dans la session
                    $_SESSION['user_nom'] = $nouveau_nom_utilisateur;
                    include 'include/header.php';
            
                    echo "Votre nom d'utilisateur a bien été changé !";
                } else {
                    echo "Une erreur s'est produite lors de la mise à jour du nom d'utilisateur : " . $update_stmt->error;
                }
                $update_stmt->close(); // Fermez la requête UPDATE
            } else {
                echo "Le nouveau nom d'utilisateur existe déjà. Veuillez en choisir un autre.";
            }
        } else {
            echo "Le nouveau nom d'utilisateur doit être différent de l'ancien. Veuillez réessayer.";
        }

        // Fermez la connexion
        $mysqli->close();
    } else {
        echo "Les nouveaux noms d'utilisateur ne correspondent pas. Veuillez réessayer.";
    }
}
include 'include/footer.php';
?>
