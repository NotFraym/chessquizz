<?php include 'session_start.php';?>
<!DOCTYPE html>
<html>
<?php
include 'include/head.php';
include 'include/header.php';
include 'config.php';

// Établir une connexion à la base de données

$mysqli = new mysqli($shost, $user, $pass, $dbname);

// Vérifier la connexion à la base de données

if ($mysqli->connect_error) {
    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
}

// Vérifier si le formulaire a été soumis

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Récupérer les données du formulaire

    $user = $_POST["nom"]; 
    $mdp = $_POST["mdp"];
    $c_mdp = $_POST["c_mdp"];

    // Vérifier que le nom d'utilisateur n'existe pas déjà

    $stmt = $mysqli->prepare("SELECT id FROM utilisateur WHERE user = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "Ce nom d'utilisateur est déjà pris. Veuillez en choisir un autre.";
    } else {
        
        // Vérifier la politique de sécurité des mots de passe

        if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&+=!])(?=.{12,})/", $mdp)) {
            echo "Le mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre, un caractère spécial et avoir une longueur d'au moins 12 caractères.";
        } else {

            // Vérifier si les mots de passe correspondent

            if ($mdp !== $c_mdp) {
                echo "Les mots de passe ne correspondent pas.";
            } else {

                // Hacher le mot de passe avec SHA-256

                $mdp_hash = hash("sha256", $mdp);

                // Définir le statut par défaut

                $statut = "membre";

                // Insérer l'utilisateur dans la base de données
                
                $stmt = $mysqli->prepare("INSERT INTO utilisateur (user, mdp, statut) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $user, $mdp_hash, $statut);

                if ($stmt->execute()) {
                    echo "Inscription réussie ! Vous pouvez maintenant vous connecter !";
                } else {
                    echo "Erreur lors de l'inscription : " . $stmt->error;
                }
            }
        }
    }

    include 'include/footer.php';
}

// Fermer la connexion à la base de données

$stmt->close();
$mysqli->close();
?>
</html>

