<?php
$conn = new mysqli('localhost', 'root', '', 'boutique');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $token = bin2hex(random_bytes(50)); // Génère un token unique
    $sql = "UPDATE utilisateurs SET reset_token='$token' WHERE email='$email'";

    if ($conn->query($sql) === TRUE) {
        // Envoie un email avec le lien de réinitialisation
        $reset_link = "http://localhost/reset_password.php?token=$token";
        mail($email, "Réinitialisation de mot de passe", "Cliquez sur ce lien pour réinitialiser votre mot de passe : $reset_link");
        echo "Un email de réinitialisation a été envoyé.";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="styles.css">
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
        <h2>Mot de passe oublié</h2>
        <div class="breadcrumb">
        <a href="index.php">Accueil</a> /
        <a href="connexion.php">Connexion</a> /
        <span class="current">Mot de Passe Oublié</span>
    </div>
    
</header> 
    <form action="mot_de_passe_oublie.php" method="post">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>
        <button type="submit">Envoyer</button>
    </form>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
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

</body>
</html>
