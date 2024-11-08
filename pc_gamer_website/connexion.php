<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'boutique');
$logout_success = isset($_GET['logout_success']) && $_GET['logout_success'] === 'true';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$login_success = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    
    $sql = "SELECT id, usertype, password FROM utilisateurs WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id']; // Store user ID in session
            $_SESSION['usertype'] = $user['usertype']; // Store usertype in session
            $login_success = "Connexion réussie !"; // Success message
            
            header("Location: index.php");
            exit(); 
        } else {
            $error_message = "Mot de passe incorrect. Réessayer ou cliquez sur 'Mot de passe oublié' pour le réinitialiser."; // Password error message
        }
    } else {
        $error_message = "Nom d'utilisateur incorrect."; // Username error message
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .error {
            border: 1px solid red;
            color: red;
            text-align: center;
            margin-top: 20px;
        }

        .success {
            font-size: 24px;
            color: #212488;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
        }

        .new-account {
            text-align: center;
            margin-top: 30px;
        }

        .new-account a {
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

        .new-account a:hover {
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
        <h1>Connexion</h1>
        <div class="breadcrumb">
        <a href="index.php">Accueil</a> /
        <span class="current">Connexion</span>
    </div>
    
</header>  

<?php if ($login_success): ?>
    <div class="success"><?= $login_success ?></div> 
<?php endif; ?>

<form action="connexion.php" method="post">
    <!-- Nom d'utilisateur -->
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" id="username" name="username" value="<?= isset($username) ? htmlspecialchars($username) : '' ?>" required>
    
    <?php if ($error_message && strpos($error_message, 'Nom d\'utilisateur') !== false): ?>
        <div class="error"><?= $error_message ?></div> <!-- Erreur spécifique du nom d'utilisateur -->
    <?php endif; ?>

    <!-- Mot de passe -->
    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required>

    <?php if ($error_message && strpos($error_message, 'Mot de passe') !== false): ?>
        <div class="error"><?= $error_message ?></div> <!-- Erreur spécifique du mot de passe -->
    <?php endif; ?>
    <div class="forget_paswd">
    <p><a href="mot_de_passe_oublie.php">Mot de passe oublié ?</a></p>
    </div>
        <button type="submit">Se connecter</button>
    </form>
    <!-- Section "Nouveau chez ZyncPC" -->
<div class="new-account">
    <p>Nouveau chez ZyncPC ?<br><a href="inscription.php">Créez votre compte ZyncPC</a></p>
</div>
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
