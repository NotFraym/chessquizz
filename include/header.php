<header>
    <h1><a href="../index.php">Chess Quizz</a></h1>
    <div>
        <?php
        if (isset($_SESSION['user_nom'])) {
            $nom_utilisateur = $_SESSION['user_nom'];
            echo '<span>Bienvenue, ' . htmlspecialchars($nom_utilisateur) . '</span>';

            // Vérifiez le statut de l'utilisateur
            if (isset($_SESSION['user_statut']) && $_SESSION['user_statut'] == 'admin') {
                echo '<a href="../administration.php">Administration</a>';
            }
            // Lien vers la page "profil.php"
            echo '<a href="../profil.php">Profil</a>';

            echo '<a href="../deconnexion.php">Déconnexion</a>';
        }
        if (!isset($_SESSION['user_nom'])) {
            echo '<a href="../connexion.php">Connexion</a>';
            echo '<a href="../inscription.php">Inscription</a>';
        }
        ?>
    </div>
</header>
