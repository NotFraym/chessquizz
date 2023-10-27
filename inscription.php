<?php include "session_start.php"; ?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php include 'include/head.php' ?>
    </head>
    <body>
        <?php include 'include/header.php'; ?>
        <form id="inscription" method="POST" action="traitement_inscription.php">
            <label for="nom">Nom d'utilisateur :</label>
            <input type="text" id="nom" name="nom" required>
            <br>
            <label for="mdp">Mot de passe :</label>
            <input type="password" id="mdp" name="mdp"  required>
            <br>
            <label for="mdp"> Vérification du mot de passe :</label>
            <input type="password" id="c_mdp" name="c_mdp" required>
            <br>
            <input type="submit" value="S'inscrire" name="ok">
        </form>
        <?php include 'include/footer.php'; ?>
    </body>
</html>
