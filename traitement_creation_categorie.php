<?php include "session_start.php"; ?>

<!DOCTYPE html>
<html>

<?php 
include 'include/head.php';
include 'include/header.php';

if (isset($_SESSION['user_statut']) && $_SESSION['user_statut'] == 'admin') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Vérifier si les données ont été soumises
        if (isset($_POST['nom_categorie']) && isset($_POST['intitule_categorie']) && isset($_FILES['image_categorie'])) {
            $nom_categorie = $_POST['nom_categorie'];
            $intitule_categorie = $_POST['intitule_categorie'];
            $image_categorie = $_FILES['image_categorie'];

            // Valider et traiter les données
            if (!empty($nom_categorie) && !empty($intitule_categorie) && $image_categorie['error'] === UPLOAD_ERR_OK) {
                // Vérifier si la catégorie existe déjà
                include 'config.php';
                $conn = new mysqli($shost, $user, $pass, $dbname);

                if ($conn->connect_error) {
                    die("Erreur de connexion à la base de données : " . $conn->connect_error);
                }

                $check_query = "SELECT id_categorie FROM categorie WHERE nom_categorie = ?";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bind_param("s", $nom_categorie);
                $check_stmt->execute();
                $check_stmt->store_result();

                if ($check_stmt->num_rows > 0) {
                    echo "La catégorie existe déjà.";
                } else {
                    // Vérifier l'extension du fichier
                    $extension = pathinfo($image_categorie['name'], PATHINFO_EXTENSION);
                    $extensions_acceptees = ['jpg', 'png'];

                    if (in_array($extension, $extensions_acceptees)) {
                        // Vérifier la taille de l'image
                        list($largeur, $hauteur) = getimagesize($image_categorie['tmp_name']);

                        if ($largeur == 500 && $hauteur == 500) {
                            // Renommer le fichier avec le nom de la catégorie et l'extension
                            $nouveau_nom_fichier = "image/categorie/{$nom_categorie}.{$extension}";

                            if (move_uploaded_file($image_categorie['tmp_name'], $nouveau_nom_fichier)) {
                                // Insérer les données de la catégorie dans la base de données
                                $insert_query = "INSERT INTO categorie (nom_categorie, intitule, nom_fichier) VALUES (?, ?, ?)";
                                $insert_stmt = $conn->prepare($insert_query);
                                $insert_stmt->bind_param("sss", $nom_categorie, $intitule_categorie, $nouveau_nom_fichier);

                                if ($insert_stmt->execute()) {
                                    echo "Catégorie créée avec succès.";
                                } else {
                                    echo "Une erreur est survenue lors de la création de la catégorie.";
                                }

                                $insert_stmt->close();
                            } else {
                                echo "Erreur lors du déplacement du fichier.";
                            }
                        } else {
                            echo "L'image doit avoir une taille de 500x500 pixels.";
                        }
                    } else {
                        echo "Seules les extensions JPG et PNG sont acceptées.";
                    }
                }

                $check_stmt->close();
                $conn->close();
            } else {
                echo "Veuillez remplir tous les champs du formulaire correctement.";
            }
        } else {
            echo "Données manquantes.";
        }
    } else {
        echo "Méthode de requête incorrecte.";
    }
} else {
    header("Location: index.php");
    exit();
}

include 'include/footer.php';
?>
