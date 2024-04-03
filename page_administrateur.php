
<?php
    session_start();
    // Connexion à la base de données
    $server = 'localhost';
    $username = 'root';
    $password = 'wxcvbn?';
    $dbname = 'Parking';
    
    // Vérifier la connexion
    $connexion = mysqli_connect($server, $username, $password, $dbname);
  
    if (!$connexion) {
        die("La connexion à la base de données a échoué : " . mysqli_connect_error());
    }
  
    if (isset($_GET['nom_utilisateur']) && isset($_GET['prenom_utilisateur'])) {
        $_SESSION['nom_utilisateur'] = $_GET['nom_utilisateur'];
        $_SESSION['prenom_utilisateur'] = $_GET['prenom_utilisateur'];
        
    } else {
        // Gérer l'absence de nom ou de prénom dans $_GET
        echo "Données manquantes";
    }

    $nom = $_SESSION['nom_utilisateur'];
    $prenom = $_SESSION['prenom_utilisateur'];
  
        
    // Récupération des utilisateurs
    $sqlU = "SELECT* FROM Utilisateur";
    $result_U = $connexion->query($sqlU);
    $users = ($result_U->num_rows > 0) ? $result_U->fetch_all(MYSQLI_ASSOC) : [];

    // Récupération des véhicules
    $sqlV = "SELECT* FROM vehicule";
    $result_V = $connexion->query($sqlV);
    $vehicles = ($result_V->num_rows > 0) ? $result_V->fetch_all(MYSQLI_ASSOC) : [];

    // Récupération des places
    $sqlP = "SELECT* FROM place WHERE Occupant is not NULL";
    $result_P = $connexion->query($sqlP);
    $places = ($result_P->num_rows > 0) ? $result_P->fetch_all(MYSQLI_ASSOC) : [];


    $connexion->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Administrateur</title>
    <link rel="stylesheet" href="interface.css">
    <script src="scroll.js"></script>
</head>
<body>
    <div class="header" id="admin">
        <h2 style="font-size: 150%;"> Parking Secure +</h2>
        <h4 style="font-size: medium; font-style: oblique;">Garder une trace de vos véhicules</h4>
    </div>
    <br>
    <button class ="deco" onclick="window.location.href='deconnection.php'">Déconnexion</button>


    <div style=" margin-top: 20px; display: flex; justify-content: space-evenly; ">
        <div style=" padding : 10px; margin-right: 10px; border: 3px solid black ; background : linear-gradient(25deg , #FFFFFF, #5e056f );">
            <h2>Tableau des Utilisateurs</h2>
            <br>
            <?php if (!empty($users)): ?>
                <table>
                    <!-- En-têtes de colonnes -->
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Actions</th>
                        <!-- ... Les autres colonnes ... -->
                    </tr>
                    <!-- Affichage des utilisateurs -->
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= $user["id_utilisateur"] ?></td>
                            <td><?= $user["Nom"] ?></td>
                            <td><?= $user["Prenom"] ?></td>
                            <td><?= $user["Mail"] ?></td>
                            <td><?= $user["Num_tel"] ?></td>
                            <td> <button onclick="openForm(<?= $user['id_utilisateur'] ?>)">Modifier</button> <br> <button onclick="supprimerUtilisateur(<?= $user['id_utilisateur'] ?>)">Supprimer </button></td>


                            <!-- ... Les autres colonnes ... -->
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>Aucune donnée trouvée dans la base de données pour les utilisateurs.</p>
            <?php endif; ?>
        </div>

        <div style=" padding : 10px; margin-right: 20px; border: 3px solid black ;  background : linear-gradient(185deg , #FFFFFF, #5e056f );">
            <h2>Tableau des Véhicules</h2>
            <br>
            <?php if (!empty($vehicles)): ?>
                <table>
                    <!-- En-têtes de colonnes -->
                    <tr>
                        <th>ID</th>
                        <th>Modèle</th>
                        <th>ID Propriétaire</th>
                        <th>Date Ajout</th>
                        <th></th>
                        
                    </tr>
                    <!-- Affichage des véhicules -->
                    <?php foreach ($vehicles as $vehicle): ?>
                        <tr>
                            <td><?= $vehicle["Id_vehicule"] ?></td>
                            <td><?= $vehicle["Modele"] ?></td>
                            <td><?= $vehicle["Proprietaire"] ?></td>
                            <td><?= $vehicle["Date_ajout"] ?></td>
                            <td><button onclick="openPop(<?= $vehicle['Id_vehicule']?>)">Modifier</button></td>
                            
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>Aucune donnée trouvée dans la base de données pour les véhicules.</p>
            <?php endif; ?>
        </div>

        <div style="padding : 10px; margin-right: 20px; border: 3px solid black ; background : linear-gradient(75deg , #FFFFFF, #5e056f );">
            <h2>Tableau des Places utilisées</h2>
            <br>
            <?php if (!empty($places)): ?>
                <table id ="Big">
                    <!-- En-têtes de colonnes -->
                    <tr>
                        <th>ID</th>
                        <th>Numéro</th>
                        <th>Etage</th>
                        <th>ID Occupant</th>
                        <!-- ... Les autres colonnes ... -->
                    </tr>
                    <!-- Affichage des véhicules -->
                    <?php foreach ($places as $place): ?>
                        <tr>
                            <td><?= $place["Id_place"] ?></td>
                            <td><?= $place["Numero"] ?></td>
                            <td><?= $place["Etage"] ?></td>
                            <td><?= $place["Occupant"] ?></td>
                            <!-- ... Les autres colonnes ... -->
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>Aucune donnée trouvée dans la base de données pour les places.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Le formulaire de modification -->
        <div class="modification_popup">
            <div class="form-popup" id="popupForm">
                <form method = "post" action="action_page.php" class="form-container">
                    <h2>Modifier les informations de l'utilisateur</h2>
                    <br>
                    <input type="hidden" id="id" name="id" />
                    <label for="telephone"><strong>Téléphone</strong></label>
                    <input type="text" id="telephone" name="telephone"/>
                    <label for="mail"><strong>Email</strong></label>
                    <input type="email" id="mail" name="mail"/>
                    
                    <button type="submit" class="btn">Modifier</button>
                    <button type="button" class="btn cancel" onclick="closeForm()">Fermer</button>
                </form>
            </div>
        </div>

         <!-- Le formulaire de modification -->
         <div class="modification_popup">
            <div class="form-popup" id="popupF">
                <form method = "post" action="modif_vehicule.php" class="form-container">
                    <h2>Modifier les informations du véhicule</h2>
                    <br>
                    <input type="hidden" id="idV" name="idV" />
                    <label for="Modele"><strong>Modèle</strong></label>
                    <input type="text" id="modele" name="modele"/>
                    <label for="year"><strong>Année</strong></label>
                    <input type="number" id="year" name="year"/>
                    <button type="submit" class="btn">Modifier</button>
                    <button type="button" class="btn cancel" onclick="closePop()">Fermer</button>
                </form>
            </div>
        </div>
    <style>
        /* Positionnez la forme Popup */
        .modification_popup {
            position: relative;
            text-align: center;
            width: 100%;
        }
        /* Masquez la forme Popup */
        .form-popup {
            display: none;
            position: fixed;
            left: 45%;
            top: 5%;
            transform: translate(-45%, 5%);
            border: 2px solid #666;
            z-index: 9;
        }
        /* Styles pour le conteneur de la forme */
        .form-container {
            max-width: 330px ;
            height: fit-content;
            padding: 20px;
            background-color: #fff;
        }
        /* Largeur complète pour les champs de saisie */
        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="number"] {
            width: 80%;
            padding: 15px;
            margin: 5px 0 22px 0;
            border: 3px solid black;
            background: #eee;
        }
        /* Quand les entrées sont concentrées, faites quelque chose */
        .form-container input[type="text"]:focus,
        .form-container input[type="email"]:focus,
        .form-container input[type="number"]{
            background-color: #ddd;
            outline: none;
        }
        /* Stylez le bouton de connexion */
        .form-container .btn {
            background-color: #8ebf42;
            color: #fff;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            width: 100%;
            margin-bottom: 10px;
            opacity: 0.8;
        }
        /* Stylez le bouton pour annuler */
        .form-container .cancel {
            background-color: #cc0000;
        }
        /* Planez les effets pour les boutons */
        .form-container .btn:hover,
        .open-button:hover {
            opacity: 1;
        }
    </style>

    <script>
        function openForm(userID) {
            document.getElementById("popupForm").style.display = "block";
            document.getElementById("id").value = userID;
        }

        function openPop(VehiculeID){

            document.getElementById("popupF").style.display = "block";
            document.getElementById("idV").value = VehiculeID;

        }


        function closeForm() {
            document.getElementById("popupForm").style.display = "none";
        }

        
        function closePop() {
            document.getElementById("popupF").style.display = "none";
        }

        function supprimerUtilisateur(idUtilisateur) {
            if (confirm("Voulez-vous vraiment supprimer cet utilisateur?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "suppressionAdmin.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // La suppression a réussi
                            alert("Utilisateur supprimé avec succès!");
                            // Rafraîchir la page ou effectuer d'autres actions nécessaires
                            window.location.reload(); // Rafraîchit la page actuelle
                        } else {
                            // La suppression a échoué
                            alert("Erreur lors de la suppression de l'utilisateur.");
                        }
                    }
                };
                xhr.send("id_utilisateur=" + encodeURIComponent(idUtilisateur));
            }
        }

    </script>

</body>
</html>
