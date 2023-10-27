<?php include 'session_start.php';?>
<!DOCTYPE html>
<html>
<?php
include 'include/head.php';
include 'include/header.php';

if (isset($_SESSION['user_statut']) && $_SESSION['user_statut'] == 'admin') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && is_numeric($_POST['id'])) {
        $question_id = $_POST['id'];

        include 'config.php';
        $conn = new mysqli($shost, $user, $pass, $dbname);

        if ($conn->connect_error) {
            die("Erreur de connexion à la base de données : " . $conn->connect_error);
        }

        // Vérifiez d'abord si la question existe
        $check_query = "SELECT id FROM questions WHERE id = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("i", $question_id);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            // Supprime la question de la base de données
            $delete_query = "DELETE FROM questions WHERE id = ?";
            $delete_stmt = $conn->prepare($delete_query);
            $delete_stmt->bind_param("i", $question_id);

            if ($delete_stmt->execute()) {
                echo "La question a été supprimée avec succès.";
            } else {
                echo "Une erreur est survenue lors de la suppression de la question : " . $delete_stmt->error;
            }

            $delete_stmt->close();
        } else {
            echo "La question n'existe pas dans la base de données.";
        }

        $check_stmt->close();
        $conn->close();
    }
} else {
    header("Location: index.php");
    exit();
}

include "include/footer.php";

?>
