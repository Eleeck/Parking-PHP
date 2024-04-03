<?php
// Vérifie si la demande de suppression de véhicule a été envoyée
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_vehicule"])) {
    // Récupère l'ID du véhicule à supprimer
    $id_V = $_POST["id_vehicule"];

    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "wxcvbn?";
    $dbname = "Parking";

    // Création de la connexion
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifie si la connexion a réussi
    if ($conn->connect_error) {
        die("La connexion a échoué: " . $conn->connect_error);
    }

    // Requête SQL pour supprimer le véhicule
    $sql = "DELETE FROM Vehicule WHERE Id_vehicule = '$id_V'";

    if ($conn->query($sql) === TRUE) {
        echo "Véhicule supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression du véhicule: " . $conn->error;
    }

    // Fermer la connexion à la base de données
    $conn->close();
}
?>
