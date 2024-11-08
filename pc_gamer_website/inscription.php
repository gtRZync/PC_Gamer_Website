<?php
$conn = new mysqli('localhost', 'root', '', 'boutique');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$success_message = ""; // Variable pour stocker le message de succès
$error_message = "";   // Variable pour stocker le message d'erreur

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $usertype = 'user'; // Set the default usertype as "user"

    // Check if the email already exists
    $check_email_sql = "SELECT * FROM utilisateurs WHERE email = '$email'";
    $check_email_result = $conn->query($check_email_sql);

    if ($check_email_result->num_rows > 0) {
        $error_message = "Cet email est déjà utilisé. Veuillez utiliser un autre email.";
    } else {
        // Insert the user if the email is unique
        $sql = "INSERT INTO utilisateurs (username, email, password, usertype) VALUES ('$username', '$email', '$password', '$usertype')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Inscription réussie !";
        } else {
            $error_message = "Erreur : " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styles pour le message de succès */
        #success-message {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #212488;
            color: white;
            padding: 20px;
            font-size: 24px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            
        }

        .error {
            border: 1px solid red;
            color: red;
            text-align: center;
            margin-top: 20px;
        }

        .account {
            text-align: center;
            margin-top: 30px;
        }

        .account a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: gray;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }

        .account a:hover {
            text-decoration: underline;
            background-color: #212488;
        }


    </style>
        <title>Fil d'Ariane</title>
    <style>
        .breadcrumb {
            font-size: 16px;
            display: flex;
            align-items: center;
        }
        .breadcrumb a {
            color: black;
            text-decoration: none;
            margin-right: 5px;
        }
        .breadcrumb a:hover {
            text-decoration: underline;
        }
        .breadcrumb .current {
            color: gray;
            cursor: default;
        }
    </style>
</head>
<body>
<header>
        <div class="upper">
        <nav>
                <a href="index.php">Acceuil</a>
                <a href="laptop.php">PC portables</a>
                <a href="acc.php">Accessoires</a>
                <a href="ecran.php">Ecran</a>
                <a href="PC.php">PC fixe</a>
        </nav>
        </div>
        <h2>Inscription</h2>
        <div class="breadcrumb">
        <a href="index.php">Accueil</a> /
        <span class="current">Inscription</span>
    </div>
</header>



    <!-- Message de succès -->
<div id="success-message"><?php echo $success_message; ?></div>


    <form action="inscription.php" method="post">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required>
        
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        <?php if ($error_message && strpos($error_message, 'email') !== false): ?>
            <div class="error"><?= $error_message ?></div>
        <?php endif; ?>
        
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit">S'inscrire</button>
    </form>
    <div class="account">
    <p>Vous êtes détenteur d'un compte ZyncPC ? <br><a href="connexion.php">Connectez-vous à votre compte ZyncPC</a></p>
</div>
    
    <script>
        // Get PHP success and error messages
        const successMessage = "<?php echo $success_message; ?>";
        const errorMessage = "<?php echo $error_message; ?>";

        // Display and hide the success message if it exists
        if (successMessage) {
            const successDiv = document.getElementById('success-message');
            successDiv.style.display = 'block';
            setTimeout(function() {
                successDiv.style.display = 'none';
            }, 4000);
        }
    </script>

<footer>
    <div class="footer-container">
        <img src="logo.png" alt="ZyncPC Logo" class="footer-logo">
        <div class="footer-links">
            <div class="footer-column">
                <h3>Votre compte</h3>
                <ul>
                    <li><a href="#">Informations personnelles</a></li>
                    <li><a href="#">Commandes</a></li>
                    <li><a href="#">Avoirs</a></li>
                    <li><a href="#">Adresses</a></li>
                    <li><a href="#">Bons de réduction</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Nos services</h3>
                <ul>
                    <li><a href="#">Paiement sécurisé</a></li>
                    <li><a href="#">Retrait en magasin</a></li>
                    <li><a href="#">Paiement en plusieurs fois</a></li>
                    <li><a href="#">Dossiers SAV</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>A propos</h3>
                <ul>
                    <li><a href="#">Qui sommes-nous ?</a></li>
                    <li><a href="#">Magasins</a></li>
                    <li><a href="#">Aide</a></li>
                    <li><a href="#">Newsletter</a></li>
                    <li><a href="#">Nous contacter</a></li>
                    <li><a href="#">Recrutement</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Information</h3>
                <ul>
                    <li><a href="#">Mentions légales et CGU</a></li>
                    <li><a href="#">Conditions Générales de Vente</a></li>
                    <li><a href="#">Assurances Commerciales</a></li>
                    <li><a href="#">Données personnelles</a></li>
                    <li><a href="#">Gestion des cookies</a></li>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Nous trouver</h3>
                <p>Université de Guyane<br>
                    97300 Cayenne, Guyane</p>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>Copyright © 2024 - ZyncPC</p>
    </div>
</footer>


</body>
</html>
