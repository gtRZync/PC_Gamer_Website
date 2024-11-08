<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'boutique');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define keyword mappings related to the products with category IDs
$keywordMappings = [
    "clavier" => [5], // Category ID for Keyboard
    "souris" => [1], // Category ID for Mouse
    "moniteur" => [2], // Category ID for Monitor
    "PC gamer" => [6, 5], // Category IDs for Gaming PCs
    "cpu" => [5] // Category ID for CPUs
    //je devais ajouter les autres mais ça ne fonctionne pas jsp pas pourquoi
];

// Initialize search results
$searchResults = [];
$suggestions = [];
$selectedCategoryIds = []; 

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $searchQuery = strtolower(trim($_GET['search'] ?? '')); 

    // Check if categories are selected
    if (isset($_GET['category']) && is_array($_GET['category'])) {
        $selectedCategoryIds = array_map('intval', $_GET['category']); 
    }

    // Fetch all products to search through
    $allProductsResult = $conn->query("SELECT * FROM produits");
    $allProducts = [];

    if ($allProductsResult->num_rows > 0) {
        while ($row = $allProductsResult->fetch_assoc()) {
            $allProducts[] = $row; // Store all products for searching
        }
    }

foreach ($allProducts as $product) {
    // Ensure the product fields are not null before using strtolower
    if (strpos(strtolower($product['nom'] ?? ''), $searchQuery) !== false ||
        strpos(strtolower($product['description'] ?? ''), $searchQuery) !== false ||
        strpos(strtolower($product['processeur'] ?? ''), $searchQuery) !== false ||
        strpos(strtolower($product['ram'] ?? ''), $searchQuery) !== false ||
        strpos(strtolower($product['frequence_rafraichissement'] ?? ''), $searchQuery) !== false ||
        strpos(strtolower($product['resolution'] ?? ''), $searchQuery) !== false ||
        strpos(strtolower($product['taille_ecran'] ?? ''), $searchQuery) !== false ||
        strpos(strtolower($product['stockage'] ?? ''), $searchQuery) !== false ||
        strpos(strtolower($product['carte_graphique'] ?? ''), $searchQuery) !== false) {

        // Check if the search query matches any keywords
        if (array_key_exists($searchQuery, $keywordMappings)) {
            // Check if product belongs to any of the selected categories
            $productCategoryIds = $keywordMappings[$searchQuery];

            // Use a SQL query to get category IDs for this product
            $categoryIdsResult = $conn->query("SELECT category_id FROM produit_categories WHERE produit_id = {$product['id']}");
            $productCategories = [];
            while ($row = $categoryIdsResult->fetch_assoc()) {
                $productCategories[] = $row['category_id'];
            }

            if (empty($selectedCategoryIds) || array_intersect($selectedCategoryIds, $productCategories)) {
                $searchResults[] = $product; // Add matching product to results
            }
        } else {
            // If keyword not found in mappings, just add the product if no category filter is applied
            if (empty($selectedCategoryIds)) {
                $searchResults[] = $product; // Add matching product to results
            }
        }
    }
}

// Check if no products were found in the search results
if (empty($searchResults) && !empty($selectedCategoryIds)) {
    // Fetch products that belong to the selected categories
    $selectedCategoryIdsString = implode(',', $selectedCategoryIds);
    $categoryProductsResult = $conn->query("SELECT * FROM produits p
        JOIN produit_categories pc ON p.id = pc.produit_id
        WHERE pc.category_id IN ({$selectedCategoryIdsString})
        GROUP BY p.id");

    if ($categoryProductsResult->num_rows > 0) {
        while ($row = $categoryProductsResult->fetch_assoc()) {
            $searchResults[] = $row; // Add products from selected categories to results
        }
    }
}

}

// Fetch categories for filtering
$categoriesResult = $conn->query("SELECT id,nom FROM produits");
$categories = [];
if ($categoriesResult->num_rows > 0) {
    while ($row = $categoriesResult->fetch_assoc()) {
        $categories[] = $row; // Store all categories for filtering
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats de recherche - ZyncPC</title>
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
                    <input type="text" name="search" placeholder="Rechercher..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" id="searchInput" oninput="showSuggestions(this.value)">
                    <button type="submit">Rechercher</button>
                </form>
                <div id="suggestions" style="display:none;">
                    <?php if (!empty($suggestions)): ?>
                        <ul>
                            <?php foreach ($suggestions as $suggestion): ?>
                                <li onclick="setSuggestion('<?= htmlspecialchars($suggestion) ?>')"><?= htmlspecialchars($suggestion) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="top-bar">
            <a href="index.php"><img src="logo.png" alt="Logo de la boutique" class="logo"></a>
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
            <h1>Résultats de recherche</h1>
        </div>
    </header>

    <div class="produits">
        <?php if (!empty($searchResults)): ?>
            <h2>Résultats de recherche pour: <?= htmlspecialchars($_GET['search']) ?></h2>
            <?php foreach ($searchResults as $row): ?>
                <div class="produit">
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['nom']) ?>" class="image-produit">
                    <h2><?= htmlspecialchars($row['nom']) ?></h2>
                    <p><?= htmlspecialchars($row['description']) ?></p>
                    <p>Prix: <?= htmlspecialchars($row['prix']) ?> €</p>
                    <a href="ajouter_au_panier.php?id=<?= htmlspecialchars($row['id']) ?>" class="add-to-cart">Ajouter au panier</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <h2>Aucun produit trouvé pour: <?= htmlspecialchars($_GET['search']) ?></h2>
        <?php endif; ?>
    </div>
</body>
</html>
