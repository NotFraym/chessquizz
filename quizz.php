<?php include "session_start.php"; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php include 'include/head.php'; ?>
</head>
<body class="dark-theme">
    <?php include 'include/header.php'; ?>

    <h2>Quizz</h2>

    <?php
    if (isset($_GET['categorie'])) {
        $categorie_id = $_GET['categorie'];
    } else {
        echo '<p>Sélectionnez une catégorie pour commencer le quizz.</p>';
        exit();
    }

    $difficulte = isset($_GET['difficulte']) ? $_GET['difficulte'] : 'facile';

    include 'config.php';
    $conn = new mysqli($shost, $user, $pass, $dbname);

    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données : " . $conn->connect_error);
    }

    $query = "SELECT c.intitule, q.id, q.bonne_reponse, q.img_question
              FROM questions q
              JOIN categorie c ON q.nom_categorie = c.id_categorie
              WHERE q.nom_categorie = ? AND q.difficulte = ?
              ORDER BY RAND() LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $categorie_id, $difficulte);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $intitule_categorie = $row['intitule'];
        $question_id = $row['id'];
        $img_question = $row['img_question'];
        $bonne_reponse = $row['bonne_reponse'];

        // Afficher l'intitulé de la catégorie
        echo '<h3>' . $intitule_categorie . '</h3>';

        // Afficher la question ici
        echo '<div class="question">';
        echo '<img src="' . $img_question . '" alt="Image Question" width="200" height="200">';

        // Récupérez les réponses (la réponse correcte et 3 réponses incorrectes de la même catégorie et de la même difficulté)
        $reponses = [$bonne_reponse];

        // Exécutez la requête uniquement s'il y a au moins une question incorrecte
        $query_reponses_fausses = "SELECT bonne_reponse FROM questions 
                                   WHERE nom_categorie = ? AND difficulte = ? AND id != ? ORDER BY RAND() LIMIT 3";
        $stmt_reponses_fausses = $conn->prepare($query_reponses_fausses);
        $stmt_reponses_fausses->bind_param("ssi", $categorie_id, $difficulte, $question_id);
        $stmt_reponses_fausses->execute();
        $result_reponses_fausses = $stmt_reponses_fausses->get_result();

        if ($result_reponses_fausses->num_rows > 0) {
            while ($row_reponse_fausse = $result_reponses_fausses->fetch_assoc()) {
                $reponses[] = $row_reponse_fausse['bonne_reponse'];
            }
        }

        shuffle($reponses);

        // Créez un formulaire avec les réponses
        echo '<form action="traitement_reponse.php" method="post">';
        echo '<input type="hidden" name="question_id" value="' . $question_id . '">';
        echo '<input type="hidden" name="categorie_id" value="' . $categorie_id . '">'; // Ajout de l'ID de catégorie

        foreach ($reponses as $reponse) {
            echo '<button type="submit" name="reponse" value="' . $reponse . '">' . $reponse . '</button>';
        }

        echo '</form>';
        echo '</div>';
    } else {
        // Aucune question disponible pour cette catégorie et cette difficulté
        echo '<p>Aucune question disponible pour cette catégorie et cette difficulté.</p>';
    }

    $stmt->close();
    if (isset($stmt_reponses_fausses)) {
        $stmt_reponses_fausses->close();
    }
    $conn->close();
    ?>

    <!-- Afficher le menu déroulant pour choisir une autre difficulté seulement s'il y a des questions disponibles -->
    <?php if ($result->num_rows > 0) : ?>
        <form method="get" action="quizz.php">
            <input type="hidden" name="categorie" value="<?php echo $categorie_id; ?>">
            <label for="difficulte">Difficulté :</label>
            <select name="difficulte" id="difficulte" onchange="this.form.submit()">
                <?php
                $difficultes = ['facile', 'moyen', 'difficile'];
                foreach ($difficultes as $dif) {
                    echo '<option value="' . $dif . '"';
                    if ($dif === $difficulte) {
                        echo ' selected';
                    }
                    echo '>' . ucfirst($dif) . '</option>';
                }
                ?>
            </select>
        </form>
    <?php endif; ?>

    <?php include 'include/footer.php'; ?>
</body>
</html>
