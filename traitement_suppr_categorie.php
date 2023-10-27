<?php include 'session_start.php';?>
<!DOCTYPE html>
<html>
<?php
include 'include/head.php';
include 'include/header.php';

if (isset($_SESSION['user_statut']) && $_SESSION['user_statut'] == 'admin') {
    if (isset($_POST['id'])) {
        $id_categorie_a_supprimer = $_POST['id'];

        // Établir la connexion à la base de données
        include 'config.php';
        $conn = new mysqli($shost, $user, $pass, $dbname);

        if ($conn->connect_error) {
            die("Erreur de connexion à la base de données : " . $conn->connect_error);
        }

        // Vérifier d'abord si la catégorie avec l'ID spécifié existe
        $check_query = "SELECT id_categorie, nom_fichier FROM categorie WHERE id_categorie = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("i", $id_categorie_a_supprimer);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            // La catégorie avec cet ID existe, procédez à la suppression
            $check_stmt->bind_result($id_categorie, $nom_fichier);
            $check_stmt->fetch();

            // Supprimer le fichier image associé à la catégorie
            if (file_exists($nom_fichier)) {
                unlink($nom_fichier);
            }

            // Supprimer la catégorie de la base de données
            $query = "DELETE FROM categorie WHERE id_categorie = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id_categorie_a_supprimer);

            if ($stmt->execute()) {
                echo "Catégorie supprimée avec succès.";
            } else {
                echo "Une erreur est survenue lors de la suppression de la catégorie.";
            }

            $stmt->close();
        } else {
            echo "ID de la catégorie à supprimer non trouvé dans la base de données.";
        }

        $check_stmt->close();
        $conn->close();
    } else {
        echo "ID de la catégorie à supprimer non spécifié.";
    }
} else {
    header("Location: index.php");
    exit();
}
include "include/footer.php";
?>
