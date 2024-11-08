<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'boutique');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si les champs requis sont remplis
if (isset($_POST['nom'], $_POST['prix'], $_POST['categorie_id'])) {
    $nom = $_POST['nom'];
    $description = $_POST['description'] ?? '';
    $prix = $_POST['prix'];
    $categorie_id = $_POST['categorie_id'];
    $image = $_POST['image'] ?? '';
    $processeur = $_POST['processeur'] ?? '';
    $carte_graphique = $_POST['carte_graphique'] ?? '';
    $ram = $_POST['ram'] ?? '';
    $stockage = $_POST['stockage'] ?? '';
    $taille_ecran = $_POST['taille_ecran'] ?? '';
    $resolution = $_POST['resolution'] ?? '';
    $frequence_rafraichissement = $_POST['frequence_rafraichissement'] ?? NULL;


    $stmt = $conn->prepare("INSERT INTO produits (nom, description, prix, categorie_id, image, processeur, carte_graphique, ram, stockage, taille_ecran, resolution, frequence_rafraichissement) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdisssssssi", $nom, $description, $prix, $categorie_id, $image, $processeur, $carte_graphique, $ram, $stockage, $taille_ecran, $resolution, $frequence_rafraichissement);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Le produit a été ajouté avec succès.";
    } else {
        $_SESSION['error_message'] = "Erreur lors de l'ajout du produit : " . $stmt->error;
    }
    $stmt->close();
} else {
    $_SESSION['error_message'] = "Veuillez remplir tous les champs requis.";
}


header("Location: CRUD.php");
exit();
