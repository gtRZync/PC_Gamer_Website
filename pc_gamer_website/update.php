<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'boutique');

// Vérifier les erreurs de connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtenir les informations utilisateur par ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Utiliser une requête préparée pour plus de sécurité
    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

// Mettre à jour les informations utilisateur
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $username = $_POST['nom'];
    $email = $_POST['email'];
    $usertype = $_POST['usertype'];
    $password = $_POST['password'];

    // Commencer la requête de mise à jour
    if (!empty($password)) { // Si le mot de passe est fourni
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("UPDATE utilisateurs SET username=?, email=?, usertype=?, password=? WHERE id=?");
        $stmt->bind_param("ssssi", $username, $email, $usertype, $hashed_password, $id);
    } else { // Si aucun nouveau mot de passe n'est fourni
        $stmt = $conn->prepare("UPDATE utilisateurs SET username=?, email=?, usertype=? WHERE id=?");
        $stmt->bind_param("sssi", $username, $email, $usertype, $id);
    }
    
    if ($stmt->execute()) {
        // Message de succès
        $_SESSION['success_message'] = "L'utilisateur a été mis à jour avec succès.";
        header("Location: CRUD.php");
        exit; 
    } else {
        echo "Erreur lors de la mise à jour de l'utilisateur : " . $stmt->error;
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'utilisateur</title>
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
    <h1>Modifier l'utilisateur</h1>
    <form action="update.php" method="POST" class="user-form">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id']); ?>">
        
        <label for="nom">Nom:</label>
        <input type="text" name="nom" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>
        
        <label for="password">Mot de passe (laisser vide pour ne pas modifier):</label>
        <input type="password" name="password" placeholder="Nouveau mot de passe"><br><br>

        <label for="usertype">Type d'utilisateur:</label>
        <select name="usertype" required>
            <option value="admin" <?php if($user['usertype'] === 'admin') echo 'selected'; ?>>Admin</option>
            <option value="user" <?php if($user['usertype'] === 'user') echo 'selected'; ?>>User</option>
        </select><br><br>

        <input type="submit" name="update" value="Mettre à jour" class="btn btn-update">
    </form>
</div>
</body>
</html>
