<?php
include "session_start.php";
// Détruisez toutes les données de session
session_destroy();

// Redirigez vers la page de connexion ou toute autre page après la déconnexion
header('Location: index.php'); // Remplacez par la page de connexion ou la page souhaitée
exit();
?>
