<?php include "session_start.php"; ?>

<!DOCTYPE html>
<html>


<?php 
include 'config.php';
include 'include/head.php';
include 'include/header.php';

if (isset($_SESSION['user_statut']) && $_SESSION['user_statut'] == 'admin') {
    if (isset($_GET['id'])) {
        $id_utilisateur_a_supprimer = $_GET['id'];

        $conn = new mysqli($shost, $user, $pass, $dbname);

        if ($conn->connect_error) {
            die("Erreur de connexion à la base de données : " . $conn->connect_error);
        }

        // Vérifiez d'abord si l'utilisateur avec l'ID spécifié existe et n'est pas un administrateur
        $check_query = "SELECT id, statut FROM utilisateur WHERE id = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("i", $id_utilisateur_a_supprimer);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $check_stmt->bind_result($id, $statut);
            $check_stmt->fetch();

            if ($statut != 'admin') {
                // L'utilisateur avec cet ID existe et n'est pas un administrateur, procédez à la suppression
                $query = "DELETE FROM utilisateur WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i", $id_utilisateur_a_supprimer);

                if ($stmt->execute()) {
                    // La suppression a réussi
                    echo "Utilisateur supprimé avec succès.";
                } else {
                    // Gestion de l'erreur de suppression
                    echo "Une erreur est survenue lors de la suppression de l'utilisateur.";
                }

                $stmt->close();
            } else {
                echo "Vous ne pouvez pas supprimer un administrateur.";
            }
        } else {
            echo "ID de l'utilisateur à supprimer non trouvé dans la base de données.";
        }

        $check_stmt->close();
        $conn->close();
    } else {
        echo "ID de l'utilisateur à supprimer non spécifié.";
    }
} else {
    header("Location: index.php");
    exit();
}
include 'include/footer.php';
?>
</html>
