<?php
// Connexion à la base de données
$connexion = mysqli_connect('localhost', 'root', 'wxcvbn?', 'Parking');

if (!$connexion) {
    die("La connexion a échoué: " . mysqli_connect_error());
}

// Récupérer les données du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $modele = $_POST['modele'];
    $year = $_POST['year'];
    $ID = $_POST['idV'];

    // Mettre à jour l'utilisateur dans la base de données
    $requete = "UPDATE vehicule SET Modele = '$modele', Annee = '$year' WHERE Id_vehicule = '$ID'";

    if (mysqli_query($connexion, $requete)) {
        echo "Mise à jour réussie !";
        
        // Redirection vers une autre page
        header("Location: page_administrateur.php");

        //arrêter l'exécution du script
        exit(); 
    } else {
        echo "Erreur lors de la mise à jour: " . mysqli_error($connexion);
    }
}

// Fermer la connexion à la base de données
mysqli_close($connexion);

?>