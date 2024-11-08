<?php
session_start();

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "boutique"; 

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée: " . $conn->connect_error);
}

// Vérifiez si l'ID du produit est passé dans l'URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    

    $sql = "DELETE FROM produits WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);


    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Produit supprimé avec succès!";
    } else {
        $_SESSION['error_message'] = "Erreur lors de la suppression du produit: " . $stmt->error;
    }
    
    $stmt->close();
} else {
    $_SESSION['error_message'] = "Aucun ID de produit spécifié.";
}

$conn->close();

header("Location: CRUD.php");
exit();
