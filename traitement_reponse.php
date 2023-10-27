<?php include "session_start.php"; ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <?php include 'include/head.php'; ?>
</head>
<body class="dark-theme">
    <?php include 'include/header.php'; ?>

    <?php
    $reponse_correcte = false; // Variable pour indiquer si la réponse est correcte ou non
    $bonne_reponse = ""; // Variable pour stocker la bonne réponse

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $question_id = isset($_POST['question_id']) ? $_POST['question_id'] : null;
        $reponse_utilisateur = isset($_POST['reponse']) ? $_POST['reponse'] : null;

        // Récupérez la catégorie depuis le formulaire
        $categorie_id = isset($_POST['categorie_id']) ? $_POST['categorie_id'] : null;

        if ($question_id && $reponse_utilisateur) {
            include 'config.php';
            $conn = new mysqli($shost, $user, $pass, $dbname);

            if ($conn->connect_error) {
                die("Erreur de connexion à la base de données : " . $conn->connect_error);
            }

            $query = "SELECT bonne_reponse FROM questions WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $question_id);
            $stmt->execute();
            $stmt->bind_result($bonne_reponse);

            if ($stmt->fetch()) {
                if ($reponse_utilisateur == $bonne_reponse) {
                    // La réponse est correcte
                    $reponse_correcte = true;
                }
            }

            $stmt->close();
            $conn->close();
        }
    }
    ?>

    <?php if ($reponse_correcte) : ?>
        <p>Votre réponse est correcte !</p>
    <?php else : ?>
        <p>Votre réponse est incorrecte. La réponse correcte est : <?php echo $bonne_reponse; ?></p>
    <?php endif; ?>

    <br>

    <!-- Bouton pour passer à la question suivante -->
    <form action="quizz.php" method="get">
        <input type="hidden" name="categorie" value="<?php echo $categorie_id; ?>">
        <input type="hidden" name="difficulte" value="facile"> <!-- Remplacer "facile" par la difficulté souhaitée -->
        <button type="submit">Question suivante</button>
    </form>

    <?php include 'include/footer.php'; ?>
</body>
</html>
