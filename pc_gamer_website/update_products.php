<?php
session_start();

$host = 'localhost';
$dbname = 'boutique';
$user = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupère l'ID du produit depuis l'URL
    $id = $_GET['id'] ?? null;

    if ($id) {
        $stmt = $conn->prepare("SELECT * FROM produits WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produit) {
            $_SESSION['error_message'] = "Erreur : Produit non trouvé.";
            header("Location: CRUD.php");
            exit;
        }
    } else {
        $_SESSION['error_message'] = "Erreur : ID de produit manquant.";
        header("Location: CRUD.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nom = $_POST['nom'];
        $description = $_POST['description'];
        $prix = $_POST['prix'];
        $categorie_id = $_POST['categorie_id'];
        $image = $_POST['image'];
        $processeur = $_POST['processeur'];
        $carte_graphique = $_POST['carte_graphique'];
        $ram = $_POST['ram'];
        $stockage = $_POST['stockage'];
        $taille_ecran = $_POST['taille_ecran'];
        $resolution = $_POST['resolution'];
        $frequence_rafraichissement = $_POST['frequence_rafraichissement'];

        try {
            $stmt = $conn->prepare("UPDATE produits SET 
                nom = :nom, 
                description = :description, 
                prix = :prix, 
                categorie_id = :categorie_id, 
                image = :image, 
                processeur = :processeur, 
                carte_graphique = :carte_graphique, 
                ram = :ram, 
                stockage = :stockage, 
                taille_ecran = :taille_ecran, 
                resolution = :resolution, 
                frequence_rafraichissement = :frequence_rafraichissement 
                WHERE id = :id");

            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':prix', $prix);
            $stmt->bindParam(':categorie_id', $categorie_id);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':processeur', $processeur);
            $stmt->bindParam(':carte_graphique', $carte_graphique);
            $stmt->bindParam(':ram', $ram);
            $stmt->bindParam(':stockage', $stockage);
            $stmt->bindParam(':taille_ecran', $taille_ecran);
            $stmt->bindParam(':resolution', $resolution);
            $stmt->bindParam(':frequence_rafraichissement', $frequence_rafraichissement);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            // Message de succès et redirection
            $_SESSION['success_message'] = "Produit mis à jour avec succès.";
            header("Location: CRUD.php");
            exit;

        } catch (PDOException $e) {
            // Message d'erreur
            $_SESSION['error_message'] = "Erreur lors de la mise à jour : " . $e->getMessage();
            header("Location: edit_product.php?id=$id");
            exit;
        }
    }

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erreur : " . $e->getMessage();
    header("Location: CRUD.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le produit</title>
    <link rel="stylesheet" href="CRUDstyles.css">
</head>
<body>
<div class="upper">
    <a href="CRUD.php" style="display: flex; align-items: center;">
        <img src="images/CRUD.png" alt="Home" style="width: 30px; height: 30px; margin-right: 10px;">
        <span>Retourner au CRUD</span>
    </a>
</div>
<div class="CRUD">
    <h1>Modifier le produit</h1>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success">
            <?php 
                echo $_SESSION['success_message'];
                unset($_SESSION['success_message']); 
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="error">
            <?php 
                echo $_SESSION['error_message'];
                unset($_SESSION['error_message']); 
            ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST" class="user-form">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($produit['id']); ?>">

        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($produit['nom']); ?>" required><br>

        <label for="description">Description:</label>
        <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($produit['description']); ?>"><br>

        <label for="prix">Prix:</label>
        <input type="number" id="prix" name="prix" step="0.01" value="<?php echo htmlspecialchars($produit['prix']); ?>" required><br>

        <label for="categorie_id">Catégorie:</label>
        <select id="categorie_id" name="categorie_id" required>
            <option value="">Sélectionner une catégorie</option>
            <option value="4" <?php echo ($produit['categorie_id'] == 4) ? 'selected' : ''; ?>>Catégorie 4 (Monitor)</option>
            <option value="5" <?php echo ($produit['categorie_id'] == 5) ? 'selected' : ''; ?>>Catégorie 5 (Gaming PC)</option>
            <option value="6" <?php echo ($produit['categorie_id'] == 6) ? 'selected' : ''; ?>>Catégorie 6 (Laptop)</option>
            <option value="7" <?php echo ($produit['categorie_id'] == 7) ? 'selected' : ''; ?>>Catégorie 7 (Mouse)</option>
            <option value="8" <?php echo ($produit['categorie_id'] == 8) ? 'selected' : ''; ?>>Catégorie 8 (Keyboard)</option>
        </select><br>

        <label for="image">Image:</label>
        <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($produit['image']); ?>"><br>

        <label for="processeur">Processeur:</label>
        <input type="text" id="processeur" name="processeur" value="<?php echo htmlspecialchars($produit['processeur']); ?>"><br>

        <label for="carte_graphique">Carte graphique:</label>
        <input type="text" id="carte_graphique" name="carte_graphique" value="<?php echo htmlspecialchars($produit['carte_graphique']); ?>"><br>

        <label for="ram">RAM:</label>
        <input type="text" id="ram" name="ram" value="<?php echo htmlspecialchars($produit['ram']); ?>"><br>

        <label for="stockage">Stockage:</label>
        <input type="text" id="stockage" name="stockage" value="<?php echo htmlspecialchars($produit['stockage']); ?>"><br>

        <label for="taille_ecran">Taille de l'écran:</label>
        <input type="text" id="taille_ecran" name="taille_ecran" value="<?php echo htmlspecialchars($produit['taille_ecran']); ?>"><br>

        <label for="resolution">Résolution:</label>
        <input type="text" id="resolution" name="resolution" value="<?php echo htmlspecialchars($produit['resolution']); ?>"><br>

        <label for="frequence_rafraichissement">Fréquence de rafraîchissement:</label>
        <input type="number" id="frequence_rafraichissement" name="frequence_rafraichissement" value="<?php echo htmlspecialchars($produit['frequence_rafraichissement']); ?>"><br>
        <br>
        <input type="submit" name="update" value="Mettre à jour" class="btn btn-update">
    </form>
</div>
</body>
</html>
