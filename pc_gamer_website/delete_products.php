<?php
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
    
    // Suppression dans la base de données
    $sql = "DELETE FROM produits WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo "Produit supprimé avec succès!";
    } else {
        echo "Erreur: " . $conn->error;
    }
} else {
    echo "Aucun ID de produit spécifié.";
}

$conn->close();
?>
<a href="CRUD.php">Retourner à la liste des produits</a>
