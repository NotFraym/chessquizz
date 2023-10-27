<?php include "session_start.php"; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php include "include/head.php"; ?>
</head>
<body class="dark-theme">
    <?php include 'include/header.php'; ?>
    <h2>Bienvenue sur ChessQuizz !</h2>
    <h3>Sur ChessQuizz, venez tester vos connaissances du monde des échecs !</h3>
    <?php
    if (isset($_SESSION['user_nom'])) {
        echo '<h2>Choisissez une catégorie de jeu :</h2>';
        
        include 'config.php';
        $conn = new mysqli($shost, $user, $pass, $dbname);
        
        if ($conn->connect_error) {
            die("Erreur de connexion à la base de données : " . $conn->connect_error);
        }
        
        $query = "SELECT id_categorie, nom_categorie, nom_fichier FROM categorie";
        $result = $conn->query($query);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id_categorie = $row['id_categorie'];
                $nom_categorie = $row['nom_categorie'];
                $image_categorie = $row['nom_fichier'];
                echo '<a class="categorie" href="quizz.php?categorie=' . $id_categorie . '">';
                echo '<img src="' . $image_categorie . '" alt="' . $nom_categorie . '" width="200" height="200">';
                echo '<h4>' . $nom_categorie . '</h4>';
                echo '</a>';
            }
            echo '<br><br>';
        } else {
            echo '<p>Aucune catégorie disponible.</p>';
        }
        
        $conn->close();
    } else {
        echo '<h3>Afin de commencer, veuillez vous connecter ou créer un compte !</h3>';
    }
    ?>
    <?php include 'include/footer.php'; ?>
</body>
</html>
