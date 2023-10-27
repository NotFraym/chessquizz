<?php include "session_start.php"; ?>

<!DOCTYPE html>
<html>


<?php 
include 'config.php';
include 'include/head.php';
include 'include/header.php';

// Vérifiez si l'utilisateur est connecté
if (isset($_SESSION['user_nom'])) {
    // Établissez la connexion à la base de données (utilisez vos propres paramètres de connexion)
    include 'config.php';
    $mysqli = new mysqli($shost, $user, $pass, $dbname);

    // Vérifiez la connexion à la base de données
    if ($mysqli->connect_error) {
        die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
    }

    // Récupérez le nom d'utilisateur de la session
    $nom_utilisateur = $_SESSION['user_nom'];

    // Préparez une requête pour supprimer la ligne de la table utilisateur où le nom d'utilisateur correspond
    $query = "DELETE FROM utilisateur WHERE user = ?";
    $stmt = $mysqli->prepare($query);

    // Lier le nom d'utilisateur au paramètre de la requête
    $stmt->bind_param("s", $nom_utilisateur);

    // Exécutez la requête
    if ($stmt->execute()) {
        // La ligne a été supprimée avec succès
        // Détruisez la session pour déconnecter l'utilisateur
        session_destroy();

        
        
        echo "Votre compte a bien été supprimé.";
    } else {
        // Gestion de l'erreur de suppression

        
        echo "Une erreur est survenue lors de la suppression de votre compte";
    }

    // Fermez la connexion et la requête $stmt
    $stmt->close();
    $mysqli->close();
} else {
    header('Location: connexion.php'); // Redirigez vers la page de connexion si l'utilisateur n'est pas connecté
    exit();
}

include 'include/footer.php';
?>
</html>
