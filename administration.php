<?php
include "session_start.php";


if (isset($_SESSION['user_statut']) && $_SESSION['user_statut'] == 'admin') {

    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <?php include "include/head.php" ?>
    </head>
    <body class="dark-theme">
        <?php include 'include/header.php'; ?>

        <h2>Page d'administration</h2>
        <a href="../gestion_utilisateur.php">Gestion des utilisateurs</a>
        <br>
        <a href="../gestion_quizz.php">Gestion des questions</a>
        <?php include 'include/footer.php'; ?>
    </body>
    </html>
    <?php
} else {
    
    header("Location: index.php");
    exit();
}
?>
