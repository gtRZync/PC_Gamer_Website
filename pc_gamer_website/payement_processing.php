<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input data
    $email = $_SESSION['checkout_data']['email'] ?? '';
    $total = $_POST['total'] ?? 0;
    $card_name = $_POST['card_name'];
    $expiration = $_POST['expiration'];
    $card_number = $_POST['card_number'];
    $cvv = $_POST['cvv'];
    
    // Here, integrate with a payment gateway
    // Example: process payment with payment API

    // On success, redirect to a confirmation page
    header('Location: confirmation.php');
    exit;
} else {
    header('Location: payer.php');
    exit;
}
//Ne fonctionne pas 
