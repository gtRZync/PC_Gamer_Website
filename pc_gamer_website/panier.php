<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'boutique');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the cart from session
$panier = isset($_SESSION['panier']) ? $_SESSION['panier'] : [];
$produits = [];

// Fetch product details if the cart is not empty
if (!empty($panier)) {
    $ids = implode(',', array_keys($panier));
   // Update SQL query to fetch additional fields (description included)
$sql = "SELECT id, nom, description, prix, carte_graphique, ram, processeur, stockage, taille_ecran, resolution, frequence_rafraichissement, image FROM produits WHERE id IN ($ids)";

    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $produits[] = $row;
    }
}

// Initialize promo code variable and discount
$promo_code = '';
$discount = 0;

// Check if promo code is applied
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['promo_code'])) {
    $promo_code = $_POST['promo_code'];
    
    // Validate the promo code
    if ($promo_code === 'PROMO10' || $promo_code === 'promo10') {
        $discount = 10; // 10% discount
    } else {
        $error_message = "Code promo invalide.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $total = 0;
    foreach ($produits as $produit) {
        $total += $produit['prix'] * $panier[$produit['id']];
    }
    $final_total = $total + 5 - ($discount / 100) * $total; // Calculate final total

    // Store checkout data in session
    $_SESSION['checkout_data'] = [
        'produits' => $produits,
        'total' => $final_total,
        'email' => $_POST['email']
    ];
    
    // Redirect to payment page
    header('Location: payer.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>

/* Style de fond et configuration générale */
body {
    background: linear-gradient(135deg, rgba(74,144,226,0.4), rgba(74,144,226,0.1));
    font-family: Arial, sans-serif;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.card, .summary {
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.8);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}


h1, h5, .cart-item h6, .cart-item p, .total-price h5 {
    color: #4A90E2; 
    font-weight: bold;
}

.cart-item {
    border-bottom: 1px solid rgba(74, 144, 226, 0.3);
    padding-bottom: 15px;
    margin-bottom: 15px;
}

.cart-item img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 12px;
}


.quantity-control input {
    max-width: 50px;
    text-align: center;
    background: rgba(255, 255, 255, 0.6);
    border: 1px solid rgba(74, 144, 226, 0.3);
    color: #333;
    border-radius: 5px;
}

.quantity-control button {
    border: none;
    background: none;
    font-size: 1.2rem;
    color: #4A90E2;
    cursor: pointer;
}

.quantity-control button:hover {
    color: #357ABD;
}

/* Boutons */
.btn-outline-light, .summary .btn-dark {
    display: inline-block;
    background-color: #4A90E2;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s ease;
    cursor: pointer;
}

.btn-outline-light:hover, .summary .btn-dark:hover {
    background-color: #357ABD;
}

.rm-cart {
    display: inline-block;
    background-color: #E24A4A;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-weight: bold;
    transition: background-color 0.3s ease;
    cursor: pointer;
}

.rm-cart:hover {
    background-color: #d32f2f;
    
}

/* Champs de formulaire */
input[type="text"],
input[type="email"],
input[type="password"],
textarea {
    padding: 10px;
    width: 100%;
    margin-top: 8px;
    font-size: 16px;
    color: #333; /* Texte en couleur principale */
    background: rgba(255, 255, 255, 0.8); 
    border: 1px solid rgba(74, 144, 226, 0.3);
    border-radius: 8px;
}

/* Placeholder pour champ de texte */
input::placeholder,
textarea::placeholder {
    color: rgba(0, 0, 0, 0.5);
}

input:focus,
textarea:focus {
    outline: none;
    border-color: #4A90E2;
    background: rgba(255, 255, 255, 0.9);
}

/* Style pour le bouton "Supprimer du panier" avec survol en rouge */
.cart-item a[href*="retirer_panier.php"] {
    color:#E24A4A;
    padding: 6px 12px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.cart-item a[href*="retirer_panier.php"]:hover {
    color: #d32f2f; 
}

</style>
</head>
<body>

<section class="h-100 h-custom">
    <div class="container">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-10">
                <div class="card p-4">
                    <div class="row">
                        <div class="col-lg-8">
                        <h2 class="mb-4 fw-bold text-white">Votre Panier</h2>
                            <!-- Display cart items -->
                            <?php if (empty($produits)): ?>
                                <p class="text-muted">Votre panier est actuellement vide.</p>
                            <?php else: ?>
                                <?php foreach ($produits as $produit): ?>
                                    <div class="row cart-item align-items-center">
                                        <div class="col-md-2">
                                            <img src="<?= $produit['image'] ?>" class="img-fluid" alt="<?= $produit['nom'] ?>">
                                        </div>
                                        <div class="col-md-5">
                                            <h6 class="mb-1"><?= $produit['nom'] ?></h6>
                                            <p class="small mb-0"><?= !empty($produit['processeur']) ? $produit['processeur'] : ''; ?><?= !empty($produit['processeur']) && !empty($produit['ram']) ? ', ' : ''; ?><?= !empty($produit['ram']) ? $produit['ram'] . ' RAM' : ''; ?><?= !empty($produit['carte_graphique']) ? ', ' . $produit['carte_graphique'] : ''; ?></p>
                                            <?php if (!empty($produit['description'])): ?>
                                                <p class="small text-muted"><?= $produit['description'] ?></p>
                                            <?php endif; ?>
                                            <?php if (!empty($produit['stockage'])): ?>
                                                <p class="small text-muted">Stockage: <?= $produit['stockage'] ?></p>
                                            <?php endif; ?>
                                            <?php if (!empty($produit['taille_ecran'])): ?>
                                                <p class="small text-muted">Taille d'écran: <?= $produit['taille_ecran'] ?></p>
                                            <?php endif; ?>
                                            <?php if (!empty($produit['resolution'])): ?>
                                                <p class="small text-muted">Résolution: <?= $produit['resolution'] ?></p>
                                            <?php endif; ?>
                                            <?php if (!empty($produit['frequence_rafraichissement'])): ?>
                                                <p class="small text-muted">Fréquence de rafraîchissement: <?= $produit['frequence_rafraichissement'] . " Hz" ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-3 d-flex quantity-control">
                                            <button onclick="this.parentNode.querySelector('input[type=number]').stepDown()">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" min="1" value="<?= $panier[$produit['id']] ?>" readonly class="form-control form-control-sm">
                                            <button onclick="this.parentNode.querySelector('input[type=number]').stepUp()">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <h6 class="mb-0">€ <?= number_format($produit['prix'], 2) ?></h6>
                                            <a href="retirer_panier.php?id=<?= $produit['id'] ?>" class="text-muted"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            <?php endif; ?>

                            <div class="pt-4 d-flex justify-content-between">
                                    <a href="index.php" class="text-white"><i class="fas fa-arrow-left me-2"></i>Retour à la boutique</a>
                                    <a href="vider_panier.php" class="rm-cart"><i class="fas fa-trash-alt me-2"></i>Vider le panier</a>
                                </div>

                        <!-- Order summary -->
                        <div class="col-lg-4 summary">
                            <h5 class="text-uppercase text-white">Résumé</h5>
                            <hr class="my-4">

                            <div class="d-flex justify-content-between">
                                <h6 class="text-muted">Articles</h6>
                                <h6 class="text-white"><?= count($panier) ?></h6>
                            </div>

                            <div class="d-flex justify-content-between">
                                <h6 class="text-muted">Sous-total</h6>
                                <?php
                                $total = 0;
                                foreach ($produits as $produit) {
                                    $total += $produit['prix'] * $panier[$produit['id']];
                                }
                                ?>
                                <h6 class="text-white">€ <?= number_format($total, 2) ?></h6>
                            </div>

                            <div class="d-flex justify-content-between">
                                <h6 class="text-muted">Livraison</h6>
                                <h6 class="text-white">€ 5.00</h6>
                            </div>

                            <hr class="my-4">

                            <!-- Formulaire de code promo -->
                            <div class="mb-3">
                                <form method="POST" action="">
                                    <div class="input-group">
                                        <input type="text" name="promo_code" class="form-control" placeholder="Entrez votre code promo">
                                        <button class="btn btn-outline-light" type="submit">Appliquer</button>
                                    </div>
                                </form>
                                <?php if (isset($error_message)): ?>
                                    <div class="text-danger mt-2"><?= $error_message ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex justify-content-between mb-4 total-price">
                                <h5 class="text-uppercase">Total</h5>
                                <?php 
                                // Calculer le total après application du code promo
                                $discount_amount = ($discount / 100) * $total;
                                $final_total = $total + 5 - $discount_amount; // Sous-total + livraison - remise
                                ?>
                                <h5>€ <?= number_format($final_total, 2) ?></h5>
                            </div>

                            <div class="text-center mb-4">
                                <!-- Checkout Form -->
                                <form method="POST" action="/pc_gamer_website/payer.php">
                                    <div class="form-group">
                                        <label for="email" class="text-white">Email</label>
                                        <input type="email" name="email" class="form-control" id="email" placeholder="Votre email" required>
                                    </div>
                                    <button type="submit" name="checkout" class="btn btn-dark btn-lg" style="width: 100%;">Passer à la caisse</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
