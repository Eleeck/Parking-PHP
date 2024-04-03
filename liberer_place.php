<?php
// Vérifie si l'identifiant de la place a été reçu correctement
if (isset($_POST['id_place'])) {
    // Récupère l'identifiant de la place à libérer
    $idPlace = $_POST['id_place'];
    
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

    // Requête SQL pour libérer la place
    $sql = "UPDATE Place SET Etat = 'libre' , Occupant = NULL WHERE id_place = $idPlace";

    if ($conn->query($sql) === TRUE) {
        // Libération réussie
        http_response_code(200);
        echo "Place libérée avec succès!";
    } else {
        // Erreur lors de la libération
        http_response_code(500);
        echo "Erreur lors de la libération de la place: " . $conn->error;
    }

    // Fermer la connexion à la base de données
    $conn->close();
} else {
    // Si l'identifiant de la place n'a pas été fourni
    http_response_code(400);
    echo "Identifiant de place non fourni.";
}

?>
