<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'boutique');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM produits WHERE categorie_id = 5");
if (!$result) {
    die("Erreur lors de la récupération des pc fixes: " . $conn->error);
}

// Initialize cart session and products in cart
$panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : [];
$produits_panier = [];
if (!empty($panier)) {
    $ids = implode(',', array_keys($panier));
    $result_panier = $conn->query("SELECT * FROM produits WHERE id IN ($ids)");
    
    if (!$result_panier) {
        die("Erreur lors de la récupération des produits du panier: " . $conn->error);
    }
    
    while ($row = $result_panier->fetch_assoc()) {
        $produits_panier[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>PC fixe - ZyncPC</title>
    <link rel="stylesheet" href="styles.css">
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
            <div class="topnav">
                <form method="GET" action="rechercher.php">
                    <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    <button type="submit">Rechercher</button>
                </form>
            </div>
        </div>
        <div class="top-bar">
            <a href="index.php">
            <a href="index.php"><img src="logo.png" alt="Logo de la boutique" class="logo"></a>
            </a>
            <nav>
                <a href="inscription.php" class="auth-button">Inscription</a>
                <a href="connexion.php" class="auth-button">Connexion</a>
            </nav>
            <div class="cart-wrapper">
                <a href="panier.php" class="panier-icon">
                    <img src="images/panier_.png" alt="Panier">
                    <span class="cart-count"><?= array_sum($_SESSION['panier'] ?? []) ?></span>
                </a>
            </div>
        </div>
        <div class="header-content">
            <h1>Gaming PC</h1>
        </div>
    </header>
    
    <div class="produits">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product">
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['nom']) ?>" class="image-product">
                    <h2><?= htmlspecialchars($row['nom']) ?></h2>
                    <p><?= htmlspecialchars($row['description']) ?></p>
                    <p>Prix: <?= htmlspecialchars($row['prix']) ?> €</p>
                    <a href="ajouter_panier.php?id=<?= $row['id'] ?>" class="add-cart">Ajouter au panier</a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Aucun produit trouvé dans cette catégorie.</p>
        <?php endif; ?>
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
