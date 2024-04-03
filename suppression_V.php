<?php
    session_start();
    // Connexion à la base de données
    $server = 'localhost';
    $username = 'root';
    $password = 'wxcvbn?';
    $dbname = 'Parking';

    $connexion = mysqli_connect($server, $username, $password, $dbname);
 
    // Vérifier la connexion
    if ($connexion->connect_error) {
        die("La connexion a échoué : " . $connexion->connect_error);
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $place = $_POST['place'];
        $vehicule = $_POST['model'];
        $annee = $_POST['date'];
        
        // Récupérer l'identifiant du véhicule
        $select = "SELECT Id_vehicule FROM Vehicule WHERE Modele = '$vehicule' and Annee = '$annee' and Place = '$place' ";
        $resultat = mysqli_query($connexion, $select);
        
        if ($resultat) {
            $row = mysqli_fetch_assoc($resultat);
            $id_v = $row['Id_vehicule'];

            // Supprimer le véhicule de l'utilisateur
            $requete = " DELETE FROM Vehicule  WHERE Id_vehicule = $id_v ";

            if (mysqli_query($connexion, $requete)) {
                echo "Mise à jour réussie !";
                // Rediriger la page après la mise à jour réussie
                header("Location: page_utilisateur.php");
                //arrêter l'exécution du script
                exit(); 

            } else {
                echo "Erreur lors de la mise à jour: " . mysqli_error($connexion);
            }
        } else {
            echo "Erreur lors de la récupération de l'identifiant de l'utilisateur: " . mysqli_error($connexion);
        }

     }
?>