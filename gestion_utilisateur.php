<?php
include "session_start.php";
include "config.php";

if (isset($_SESSION['user_statut']) && $_SESSION['user_statut'] == 'admin') {
    $conn = new mysqli($shost, $user, $pass, $dbname);

    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données : " . $conn->connect_error);
    }

    $sql = "SELECT id, user, statut FROM utilisateur";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <?php include "include/head.php" ?>
            <script src="suppr_user_admin.js"></script>
        </head>
        <body class="dark-theme">
            <?php include 'include/header.php'; ?>

            <h2>Liste de tous les utilisateurs :</h2>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Nom d'utilisateur</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>

                <?php
           while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["user"] . "</td>";
            echo "<td>" . $row["statut"] . "</td>";
            echo "<td><button onclick='confirmerSuppression(" . $row["id"] . ")'>Supprimer</button></td>";
            echo "</tr>";
        }
                ?>
            </table>

            <?php include 'include/footer.php'; ?>
        </body>
        </html>
        <?php
    } else {
        echo "Aucun utilisateur trouvé dans la base de données.";
    }

    $conn->close();
} else {
    header("Location: index.php");
    exit();
}
?>
