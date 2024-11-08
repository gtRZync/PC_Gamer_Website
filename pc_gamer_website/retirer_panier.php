<?php
session_start();
$produit_id = $_GET['id'];

if (isset($_SESSION['panier'][$produit_id])) {
    $_SESSION['panier'][$produit_id]--;
    if ($_SESSION['panier'][$produit_id] <= 0) {
        unset($_SESSION['panier'][$produit_id]);
    }
}

header('Location: panier.php');
