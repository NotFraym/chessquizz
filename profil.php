<?php
include "session_start.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - ChessQuizz</title>

</head>

<body>

<?php include 'include/header.php';
      include 'include/head.php'; ?>
<script src="action_user.js"></script>
<h3><p>Paramètres du compte</p></h3>

<form method="POST" action="traitement_modif_user.php">
    <h4>Changement de nom d'utilisateur</h4>
    <label for="mdp">Nom d'utilisateur actuel :</label>
        <input type="text" id="user" name="user"  required>
        <br>
        <label for="mdp1">Nouveau nom d'utilisateur :</label>
        <input type="text" id="user1" name="user1"  required>
        <br>
        <label for="mdp2">Vérification du nouveau nom d'utilisateur :</label>
        <input type="text" id="user2" name="user2"  required>
        <br>
        <input type="submit" value="Changer mon nom d'utilisateur" name="ok">

</form>
    
<form method="POST" action="traitement_modif_mdp.php">
    <h4>Changement de mot de passe</h4>
    <label for="mdp">Mot de passe actuel :</label>
        <input type="password" id="mdp" name="mdp"  required>
        <br>
        <label for="mdp1">Nouveau mot de passe :</label>
        <input type="password" id="mdp1" name="mdp1"  required>
        <br>
        <label for="mdp2">Vérification du nouveau mot de passe :</label>
        <input type="password" id="mdp2" name="mdp2"  required>
        <br>
        <input type="submit" value="Changer mon mot de passe" name="ok">

</form>
<br>
<button onclick="supprimerCompte()">Supprimer mon compte</button>


<?php include 'include/footer.php'; ?>

</body>
</html>
