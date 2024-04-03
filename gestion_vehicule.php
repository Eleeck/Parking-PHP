<?php
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

    if (isset($_GET['id_user']) ) {
        $_SESSION['id_user'] = $_GET['id_user'];
     
    } else {
        // Gérer l'absence de nom ou de prénom dans $_GET
        echo "Données manquantes";
    }
    
    $Id= $_SESSION['id_user'];

?>

<!DOCTYPE html>
<html>
    <meta charset="UTF-8" />
    <title>Utilisateur</title>
    <link rel="stylesheet" href="interface.css">
</html>
<body>
    <div class="header" style="background-color: #f2f2f2;">
        <h2 style="font-size: 150%;"> Parking Secure +</h2>
        <br>
        <h4 style="font-size: medium; font-style: oblique;">Garder une trace de vos véhicules</h4>
    </div>
    <button class ="deco" onclick="window.location.href='deconnection.php'">Déconnexion</button>
    <a href="page_utilisateur.php"><button class ="button3">Retour</button></a>
    <center>
        <h3 style="font-size: medium;">Vos Véhicules</h3>
        <br>
        <table>
            <?php
                // Requête SQL pour sélectionner les données sur les véhicules de l'utilisateur
                $sql = "SELECT Place, Modele, Annee FROM Vehicule WHERE Proprietaire = '$Id' ";

                $result = $conn->query($sql);

                // Vérifier s'il y a des données
                if ($result->num_rows > 0) {
                    // Afficher les données dans un tableau
                    echo "<table><tr><th>ID Place</th><th>Modèle</th><th>Année</th></tr>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td>".$row["Place"]."</td><td>".$row["Modele"]."</td><td>".$row["Annee"]."</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "Aucun résultat trouvé.";
                }
                $conn->close();
            ?>
        </table>
        <button class="button3" onclick="afficherFormulaire()"> Supprimer</button>

        <div id="myModal" class="modal">
            <span class="close">&times;</span>
            <center>
                <div class="modal-content">
                    <h2>Sélection du Véhicule</h2>
                    <form id="SuppressForm" method = "post" action="suppression_V.php">

                        <label for="place">Id Place:</label>
                        <input type="number" id="place" name="place"><br><br>
                        
                        <label for="model">Modèle :</label>
                        <input type="text" id="model" name="model" required><br><br>

                        <label for="date"> Année :</label>
                        <input type="number" id ="date" name="date" required><br>
                        
                        <input type="submit" value="Supprimer">
                    </form>
                </div>
            </center>
        </div>
    </center>


    <script>
         // JavaScript pour afficher le modal et envoyer les données du formulaire
        function afficherFormulaire() {
            var modal = document.getElementById('myModal');
            modal.style.display = 'block';
        }

        // Événement de clic sur le bouton de fermeture du modal
        var closeBtn = document.querySelector('.close');
        closeBtn.addEventListener('click', function() {
            var modal = document.getElementById('myModal');
            modal.style.display = 'none';
        });


        // Fonction pour fermer le modal
        function closeModal() {
            var modal = document.getElementById('myModal');
            modal.style.display = 'none';
        }

        // function retour() {
        //     // Redirection vers la page précédente
        //     window.history.back();
        // }
    </script>
</body>