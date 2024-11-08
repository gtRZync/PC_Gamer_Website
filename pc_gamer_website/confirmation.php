<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<section class="h-100 h-custom">
    <div class="container">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-10">
                <div class="card p-4">
                    <h2 class="mb-4 fw-bold">Merci pour votre commande !</h2>
                    <p>Votre paiement a été traité avec succès. Un e-mail de confirmation a été envoyé.</p>
                    <a href="index.php" class="btn btn-primary">Retour à la boutique</a>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
