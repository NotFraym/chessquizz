<!DOCTYPE html>
<html lang="fr">
<head>
    <?php 
    include "include/head.php";
    include "session_start.php"; 
    ?>
</head>
<body class="dark-theme">
    <?php include 'include/header.php'; ?>

    <h1>Gestion du Quizz</h1>

    <h2>Paramètres des catégories</h2>

    <!-- Formulaire de création de catégorie -->
    <h3>Création d'une catégorie :</h3>
    <form action="traitement_creation_categorie.php" method="post" enctype="multipart/form-data">
        <label for="nom_categorie">Nom de la catégorie :</label>
        <input type="text" id="nom_categorie" name="nom_categorie" required>
        <label for="intitule_categorie">Intitulé de la catégorie :</label>
        <input type="text" id="intitule_categorie" name="intitule_categorie" required>
        <label for="image_categorie">Image de la catégorie : (500px/500px uniquement)</label>
        <input type="file" id="image_categorie" name="image_categorie" accept="image/*" required>
        <button type="submit">Créer la catégorie</button>
    </form>

    <!-- Liste des catégories -->
    <h3>Liste des catégories :</h3>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom de la catégorie</th>
            <th>Intitulé de la catégorie</th>
            <th>Image de la catégorie</th>
            <th>Actions</th>
        </tr>
        <?php
        include 'config.php';
        $conn = new mysqli($shost, $user, $pass, $dbname);

        if ($conn->connect_error) {
            die("Erreur de connexion à la base de données : " . $conn->connect_error);
        }

        $query = "SELECT id_categorie, nom_categorie, intitule, nom_fichier FROM categorie";
        $result = $conn->query($query);

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id_categorie"] . "</td>";
            echo "<td>" . $row["nom_categorie"] . "</td>";
            echo "<td>" . $row["intitule"] . "</td>";
            // Afficher l'image à 100x100 pixels
            echo "<td><img src='" . $row["nom_fichier"] . "' alt='" . $row["nom_categorie"] . "' width='100' height='100'></td>";
            echo "<td>";
            echo "<form action='traitement_suppr_categorie.php' method='post'>";
            echo "<input type='hidden' name='id' value='" . $row["id_categorie"] . "'>";
            echo "<button type='submit'>Supprimer</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <h2>Paramètres des questions</h2>
    <h3>Ajout d'une question :</h3>

    <!-- Formulaire d'ajout de question -->
    <form action="traitement_ajout_question.php" method="post" enctype="multipart/form-data">
        <label for="nom_categorie">Catégorie :</label>
        <select name="nom_categorie" id="nom_categorie">
            <?php
            include 'config.php';
            $conn = new mysqli($shost, $user, $pass, $dbname);

            if ($conn->connect_error) {
                die("Erreur de connexion à la base de données : " . $conn->connect_error);
            }

            $query = "SELECT id_categorie, nom_categorie FROM categorie";
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row["id_categorie"] . "'>" . $row["nom_categorie"] . "</option>";
            }

            $conn->close();
            ?>
        </select>

        <label for="bonne_reponse">Bonne réponse :</label>
        <input type="text" id="bonne_reponse" name="bonne_reponse" required>

        <label for="image_question">Image de la question : (500px/500px uniquement)</label>
        <input type="file" id="image_question" name="image_question" accept="image/*" required>

        <label for="difficulte">Difficulté :</label>
        <select name="difficulte" id="difficulte">
            <option value="facile">Facile</option>
            <option value="moyen">Moyen</option>
            <option value="difficile">Difficile</option>
        </select>

        <button type="submit">Ajouter la question</button>
    </form>

    <h3>Liste des questions</h3>

    <!-- Formulaire de tri par catégorie -->
    <form action="gestion_quizz.php" method="post">
        <label for="tri_categorie">Trier par catégorie :</label>
        <select name="tri_categorie" id="tri_categorie">
            <option value="0">Toutes les catégories</option>
            <?php
            include 'config.php';
            $conn = new mysqli($shost, $user, $pass, $dbname);

            if ($conn->connect_error) {
                die("Erreur de connexion à la base de données : " . $conn->connect_error);
            }

            $query = "SELECT id_categorie, nom_categorie FROM categorie";
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row["id_categorie"] . "'>" . $row["nom_categorie"] . "</option>";
            }

            $conn->close();
            ?>
        </select>
        <button type="submit">Trier</button>
    </form>

    <!-- Liste des questions -->
    <table>
        <tr>
            <th>ID</th>
            <th>Catégorie</th>
            <th>Bonne Réponse</th>
            <th>Difficulté</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php
        include 'config.php';
        $conn = new mysqli($shost, $user, $pass, $dbname);

        if ($conn->connect_error) {
            die("Erreur de connexion à la base de données : " . $conn->connect_error);
        }

        // Filtrer par catégorie
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tri_categorie']) && is_numeric($_POST['tri_categorie'])) {
            $tri_categorie = $_POST['tri_categorie'];
            $query = "SELECT q.id, c.nom_categorie, q.bonne_reponse, q.difficulte, q.img_question FROM questions q
                      LEFT JOIN categorie c ON q.nom_categorie = c.id_categorie
                      WHERE c.id_categorie = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $tri_categorie);
        } else {
            $query = "SELECT q.id, c.nom_categorie, q.bonne_reponse, q.difficulte, q.img_question FROM questions q
                      LEFT JOIN categorie c ON q.nom_categorie = c.id_categorie";
            $stmt = $conn->prepare($query);
        }

        $stmt->execute();
        $stmt->bind_result($id, $nom_categorie, $bonne_reponse, $difficulte, $img_question);

        while ($stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . $id . "</td>";
            echo "<td>" . $nom_categorie . "</td>";
            echo "<td>" . $bonne_reponse . "</td>";
            echo "<td>" . $difficulte . "</td>";
            echo "<td><img src='" . $img_question . "' alt='Image Question' width='100' height='100'></td>";
            echo "<td>";
            echo "<form action='traitement_suppr_question.php' method='post'>";
            echo "<input type='hidden' name='id' value='" . $id . "'>";
            echo "<button type='submit'>Supprimer</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }

        $stmt->close();
        $conn->close();
        ?>

        <br>
    </table>

    <br><br>

    <?php include 'include/footer.php'; ?>
</body>
</html>
