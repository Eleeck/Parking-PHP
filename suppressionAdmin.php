<?php
// Assurez-vous que l'ID de l'utilisateur est reçu correctement
if (isset($_POST['id_utilisateur'])) {
    $idUtilisateur = $_POST['id_utilisateur'];
    
    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "wxcvbn?";
    $dbname = "Parking";

    // Création de la connexion
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("La connexion a échoué : " . $conn->connect_error);
    }

    // Suppression des places associées
    $stmt = $conn->prepare("DELETE FROM place WHERE Occupant = ?");
    $stmt->bind_param("i", $idUtilisateur);
    $stmt->execute();

    // Suppression des véhicules associés
    $stmt = $conn->prepare("DELETE FROM vehicule WHERE Proprietaire = ?");
    $stmt->bind_param("i", $idUtilisateur);
    $stmt->execute();

    // Suppression de l'utilisateur
    $stmt = $conn->prepare("DELETE FROM utilisateur WHERE id_utilisateur = ?");
    $stmt->bind_param("i", $idUtilisateur);
    $stmt->execute();

    // Vérification du nombre de lignes affectées pour s'assurer que la suppression a réussi
    if ($stmt->affected_rows > 0) {
        // Envoyer une réponse
        http_response_code(200);
        echo "Utilisateur supprimé avec succès!";
    } else {
        // Envoyer une réponse d'erreur
        http_response_code(500);
        echo "Erreur lors de la suppression de l'utilisateur.";
    }

    // Fermer la connexion
    $conn->close();
} else {
    // Envoyer une réponse d'erreur
    http_response_code(400);
    echo "ID de l'utilisateur non fourni.";
}
?>
