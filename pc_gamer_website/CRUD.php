<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'boutique');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$produits = []; 

$query = "SELECT * FROM produits";
$result = mysqli_query($conn, $query);

if ($result) {
    $produits = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    
    echo "Erreur lors de la récupération des produits : " . mysqli_error($conn);
}


// Vérifier et récupérer le type d'utilisateur pour la session
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $sql = "SELECT usertype FROM utilisateurs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['usertype'] = $row['usertype'];
    }
    $stmt->close();
}

// Vérifiez le type d'utilisateur pour l'autorisation
if (!isset($_SESSION['usertype']) || $_SESSION['usertype'] != 'admin') {
    die("Erreur 404: Accès refusé.");
}

$success_message = ""; // Variable pour stocker le message de succès
$error_message = "";   // Variable pour stocker le message d'erreur

// Suppression d'un utilisateur
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM utilisateurs WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "L'utilisateur a été supprimé avec succès.";
    } else {
        $_SESSION['error_message'] = "Erreur lors de la suppression de l'utilisateur : " . $stmt->error; // Message d'erreur
    }
    $stmt->close();
    header("Location: CRUD.php");
    exit();
}

// Création d'un utilisateur
if (isset($_POST['create'])) {
    $username = $_POST['nom'];
    $email = $_POST['email'];
    $usertype = $_POST['usertype'];
    $password = $_POST['password'];

    // Vérifiez que le mot de passe a au moins 8 caractères
    if (strlen($password) < 8) {
        $_SESSION['error_message'] = "Le mot de passe doit contenir au moins 8 caractères.";
    } else {
        // Hachage du mot de passe
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Vérifiez si l'email existe déjà
        $check_email_sql = "SELECT * FROM utilisateurs WHERE email = ?";
        $stmt = $conn->prepare($check_email_sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $check_email_result = $stmt->get_result();

        if ($check_email_result->num_rows > 0) {
            $_SESSION['error_message'] = "Cet email est déjà utilisé. Veuillez utiliser un autre email."; // Message d'erreur
        } else {
            $stmt->close(); 
            $stmt = $conn->prepare("INSERT INTO utilisateurs (username, email, usertype, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $usertype, $hashed_password);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "L'utilisateur a été ajouté avec succès."; 
                $_SESSION['error_message'] = "Erreur lors de l'ajout de l'utilisateur : " . $stmt->error; // Message d'erreur
            }
        }
        $stmt->close();
    }
}

// Gestion des produits
if (isset($_POST['create_product'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    // Insertion du produit dans la base de données
    $stmt = $conn->prepare("INSERT INTO produits (name, price) VALUES (?, ?)");
    $stmt->bind_param("sd", $product_name, $product_price);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Le produit a été ajouté avec succès.";
    } else {
        $_SESSION['error_message'] = "Erreur lors de l'ajout du produit : " . $stmt->error;
    }
    $stmt->close();
}

// Suppression d'un produit
if (isset($_GET['delete_product'])) {
    $product_id = $_GET['delete_product'];
    $stmt = $conn->prepare("DELETE FROM produits WHERE id=?");
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Le produit a été supprimé avec succès.";
    } else {
        $_SESSION['error_message'] = "Erreur lors de la suppression du produit : " . $stmt->error;
    }
    $stmt->close();
    header("Location: CRUD.php"); 
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>CRUD</title>
    <link rel="stylesheet" href="CRUDstyles.css">
    <script>
        window.onload = function() {
            var successMessage = document.getElementById('successMessage');
            if (successMessage) {
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 4000);
            }
        };

        function validateForm() {
            var usertype = document.getElementById("usertype").value;
            var password = document.getElementById("password").value;

            if (usertype === "NULL") {
                alert("Veuillez sélectionner un type d'utilisateur valide.");
                return false;
            }

            if (password.length < 8) {
                alert("Le mot de passe doit contenir au moins 8 caractères.");
                return false;
            }

            return true;
        }

        function confirmDelete(message) {
    // Première confirmation
    if (confirm(message)) {
        // Deuxième confirmation
        return confirm("Veuillez confirmer à nouveau : voulez-vous vraiment procéder à la suppression ?");
    }
    return false; // Annuler la suppression si la première confirmation n'est pas approuvée
}


function confirmDeleteUser() {
    return confirmDelete("Êtes-vous sûr de vouloir supprimer cet utilisateur ?");
}


function confirmDeleteProduct() {
    return confirmDelete("Êtes-vous sûr de vouloir supprimer ce produit ?");
}

    </script>
</head>
<body>
    <div class="upper">
        <a href="index.php" style="display: flex; align-items: center;">
            <img src="images/home.png" alt="Home" style="width: 30px; height: 30px; margin-right: 10px;">
            <span>Retourner à l'accueil</span>
        </a>
    </div>

    <!-- Affichage du message de succès -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="success" id="successMessage"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>

    <div class="CRUD">
        <h1>Gestion des utilisateurs</h1>
        <?php 
        // Affichage des utilisateurs dans un tableau
        $resultat = $conn->query("SELECT * FROM utilisateurs");
        if ($resultat && $resultat->num_rows > 0) {
            echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Actions</th>
                </tr>";
            while ($row = $resultat->fetch_assoc()) {
                echo "<tr>
                    <td>".$row['id']."</td>
                    <td class='username'>".htmlspecialchars($row['username'])."</td>
                    <td>".$row['email']."</td>
                    <td>".$row['usertype']."</td>
                    <td>
                        <a href='update.php?id=".$row['id']."' class='btn btn-update'>Modifier</a>
                        <a href='CRUD.php?delete=".$row['id']."' class='btn btn-delete' onclick='return confirmDeleteUser()'>Supprimer</a>
                    </td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "Aucun utilisateur trouvé.";
        }
        ?>
        
        <h1>Ajouter un utilisateur</h1>
        <form action="CRUD.php" method="POST" class="user-form" onsubmit="return validateForm()">
            <label for="nom">Nom d'utilisateur:</label>
            <input type="text" id="nom" name="nom" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="error"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?><br>

            <label for="usertype">Type d'utilisateur:</label>
            <select name="usertype" id="usertype" required>
                <option value="NULL">Sélectionnez un type</option>
                <option value="admin">Administrateur</option>
                <option value="user">Utilisateur</option>
            </select><br>

            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required><br>

            <input type="submit" name="create" value="Ajouter l'utilisateur">
        </form><br><br>
                                                                        <!-- Section gestion des Produits -->



        <h1>Gestion des Produits</h1><br>

<!-- Formulaire d'ajout de produit -->

<div class="product-form">
            <form action="ajouter_produit.php" method="post">
                <label for="nom">Nom du produit:</label>
                <input type="text" id="nom" name="nom" required>

                <label for="description">Description:</label>
                <input type="text" id="description" name="description">

                <label for="prix">Prix:</label>
                <input type="number" id="prix" name="prix" step="0.01" required>

                <label for="categorie_id">Catégorie:</label>
                <select id="categorie_id" name="categorie_id" required>
                    <option value="">Sélectionner une catégorie</option>
                    <option value="4">Catégorie 4(Monitor)</option>
                    <option value="5">Catégorie 5(Gaming PC)</option>
                    <option value="6">Catégorie 6(Laptop)</option>
                    <option value="7">Catégorie 7(Mouse)</option>
                    <option value="8">Catégorie 8(Keyboard)</option>
                </select>

                <label for="image">Image:</label>
                <input type="text" id="image" name="image">

                <label for="processeur">Processeur:</label>
                <input type="text" id="processeur" name="processeur">

                <label for="carte_graphique">Carte graphique:</label>
                <input type="text" id="carte_graphique" name="carte_graphique">

                <label for="ram">RAM:</label>
                <input type="text" id="ram" name="ram">

                <label for="stockage">Stockage:</label>
                <input type="text" id="stockage" name="stockage">

                <label for="taille_ecran">Taille de l'écran:</label>
                <input type="text" id="taille_ecran" name="taille_ecran">

                <label for="resolution">Résolution:</label>
                <input type="text" id="resolution" name="resolution">

                <label for="frequence_rafraichissement">Fréquence de rafraîchissement:</label>
                <input type="number" id="frequence_rafraichissement" name="frequence_rafraichissement"><br>

                <input type="submit" value="Ajouter Produit">
            </form>
        </div>

        <!-- Tableau d'affichage des produits -->
        <table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Prix</th>
            <th>Catégorie</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produits as $produit) : ?>
            <tr>
                <td><?php echo htmlspecialchars($produit['id']); ?></td>
                <td><?php echo htmlspecialchars($produit['nom'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($produit['description'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($produit['prix'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($produit['categorie_id'] ?? ''); ?></td>
                <td>
                    <br><a href="update_products.php?id=<?php echo $produit['id']; ?>">Modifier<br></a><br>
                    <br><a href="delete_products.php?id=<?php echo $produit['id']; ?>" onclick="return confirmDeleteProduct()">Supprimer</a><br><br>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    </div>
</body>
</html>