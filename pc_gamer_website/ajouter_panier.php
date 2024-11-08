<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'boutique');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$produit_id = $_GET['id'];
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

if (isset($_SESSION['panier'][$produit_id])) {
    $_SESSION['panier'][$produit_id]++;
} else {
    $stmt = $conn->prepare("SELECT id FROM produits WHERE id = ?");
    $stmt->bind_param("i", $produit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $_SESSION['panier'][$produit_id] = 1;
    }
    $stmt->close();
}

header('Location: index.php');