<?php include "session_start.php"; ?>

<!DOCTYPE html>
<html>

<?php 
include 'include/head.php';
include 'include/header.php';

if (isset($_SESSION['user_statut']) && $_SESSION['user_statut'] == 'admin') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom_categorie = $_POST['nom_categorie'];
        $bonne_reponse = $_POST['bonne_reponse'];
        $difficulte = $_POST['difficulte'];

        include 'config.php';
        $conn = new mysqli($shost, $user, $pass, $dbname);

        if ($conn->connect_error) {
            die("Erreur de connexion à la base de données : " . $conn->connect_error);
        }

        // Vérification si la réponse existe déjà
        $check_query = "SELECT id FROM questions WHERE bonne_reponse = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("s", $bonne_reponse);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            echo "Cette réponse existe déjà dans la base de données. Veuillez enregistrer une réponse unique.";
        } else {
            if (isset($_FILES['image_question']) && $_FILES['image_question']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'image/question/';
                $extension = pathinfo($_FILES['image_question']['name'], PATHINFO_EXTENSION);

                // Vérification de l'extension de l'image et de la taille
                $image_info = getimagesize($_FILES['image_question']['tmp_name']);
                if ($image_info === false || $image_info[0] !== 500 || $image_info[1] !== 500) {
                    echo "L'image doit avoir une dimension de 500x500 pixels.";
                } else {
                    if ($extension !== 'jpg' && $extension !== 'png') {
                        echo "Seuls les fichiers JPG et PNG sont acceptés.";
                    } else {
                        // Générer un nom de fichier unique basé sur la bonne réponse hachée
                        $hashed_bonne_reponse = hash('sha256', $bonne_reponse);
                        $image_name = $upload_dir . $hashed_bonne_reponse . '.' . $extension;

                        // Déplacer le fichier vers le répertoire des images
                        if (move_uploaded_file($_FILES['image_question']['tmp_name'], $image_name)) {
                            // Insérer la question dans la base de données
                            $insert_query = "INSERT INTO questions (nom_categorie, bonne_reponse, img_question, difficulte) VALUES (?, ?, ?, ?)";
                            $stmt = $conn->prepare($insert_query);
                            $stmt->bind_param("isss", $nom_categorie, $bonne_reponse, $image_name, $difficulte);

                            if ($stmt->execute()) {
                                echo "La question a été ajoutée avec succès.";
                            } else {
                                echo "Une erreur est survenue lors de l'ajout de la question : " . $stmt->error;
                            }
                            $stmt->close();
                        } else {
                            echo "Une erreur est survenue lors du téléchargement de l'image.";
                        }
                    }
                }
            } else {
                echo "Aucun fichier image n'a été téléchargé.";
            }
        }

        $check_stmt->close();
        $conn->close();
    }
} else {
    header("Location: index.php");
    exit();
}
include 'include/footer.php';
?>
