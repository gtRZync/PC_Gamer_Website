<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'boutique');
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données.");
}

// Vérification de la présence des données de paiement dans la session
// if (!isset($_SESSION['checkout_data'])) {
//     header('Location: panier.php');
//     exit;
// }

$checkout_data = $_SESSION['checkout_data'];
$produits = $checkout_data['produits'];
$total = $checkout_data['total'];
$email = $checkout_data['email'];

// Effacement des données de paiement de la session après utilisation
unset($_SESSION['checkout_data']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #343a40;
            color: white;
        }
        .card {
            border-radius: 16px;
            background-color: #495057;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Détails de Paiement</h2>

    <div class="card shadow-2-strong mb-5 mb-lg-0">
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6 col-lg-4 col-xl-3 mb-4 mb-md-0">
                    <!-- Options de méthode de paiement -->
                    <form action="payment_processing.php" method="POST">
                        <div class="d-flex flex-row pb-3">
                            <div class="d-flex align-items-center pe-2">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" value="credit" checked />
                            </div>
                            <div class="rounded border w-100 p-3">
                                <p class="d-flex align-items-center mb-0">
                                    <i class="fab fa-cc-mastercard fa-2x text-body pe-2"></i>Carte de Crédit
                                </p>
                            </div>
                        </div>
                        <div class="d-flex flex-row pb-3">
                            <div class="d-flex align-items-center pe-2">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="debitCard" value="debit" />
                            </div>
                            <div class="rounded border w-100 p-3">
                                <p class="d-flex align-items-center mb-0">
                                    <i class="fab fa-cc-visa fa-2x text-body pe-2"></i>Carte de Débit
                                </p>
                            </div>
                        </div>
                        <div class="d-flex flex-row">
                            <div class="d-flex align-items-center pe-2">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="paypal" value="paypal" />
                            </div>
                            <div class="rounded border w-100 p-3">
                                <p class="d-flex align-items-center mb-0">
                                    <i class="fab fa-cc-paypal fa-2x text-body pe-2"></i>PayPal
                                </p>
                            </div>
                        </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-6">
                    <!-- Champs de paiement -->
                    <div class="row">
                        <div class="col-12 col-xl-6">
                            <div class="form-outline mb-4 mb-xl-5">
                                <input type="text" id="typeName" name="card_name" class="form-control form-control-lg" placeholder="John Smith" required />
                                <label class="form-label" for="typeName">Nom sur la carte</label>
                            </div>

                            <div class="form-outline mb-4 mb-xl-5">
                                <input type="text" id="typeExp" name="card_exp" class="form-control form-control-lg" placeholder="MM/AA" required />
                                <label class="form-label" for="typeExp">Expiration</label>
                            </div>
                        </div>
                        <div class="col-12 col-xl-6">
                            <div class="form-outline mb-4 mb-xl-5">
                                <input type="text" id="typeText" name="card_number" class="form-control form-control-lg" placeholder="1111 2222 3333 4444" required />
                                <label class="form-label" for="typeText">Numéro de Carte</label>
                            </div>

                            <div class="form-outline mb-4 mb-xl-5">
                                <input type="password" id="typeCvv" name="card_cvv" class="form-control form-control-lg" placeholder="&#9679;&#9679;&#9679;" required />
                                <label class="form-label" for="typeCvv">Cvv</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xl-3">
                    <!-- Résumé des coûts -->
                    <div class="d-flex justify-content-between" style="font-weight: 500;">
                        <p class="mb-2">Sous-total</p>
                        <p class="mb-2">€<?= number_format($total, 2) ?></p>
                    </div>

                    <div class="d-flex justify-content-between" style="font-weight: 500;">
                        <p class="mb-0">Livraison</p>
                        <p class="mb-0">€5.00</p>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between mb-4" style="font-weight: 500;">
                        <p class="mb-2">Total (taxes comprises)</p>
                        <p class="mb-2">€<?= number_format($total + 5, 2) ?></p>
                    </div>

                    <!-- Bouton de soumission -->
                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        <div class="d-flex justify-content-between">
                            <span>Passer à la caisse</span>
                            <span>€<?= number_format($total + 5, 2) ?></span>
                        </div>
                    </button>
                    </form> 
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
