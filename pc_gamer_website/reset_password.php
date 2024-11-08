<?php
$conn = new mysqli('localhost', 'root', '', 'boutique');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $conn->real_escape_string($_POST['token']);
    $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
    $sql = "UPDATE utilisateurs SET password='$new_password', reset_token=NULL WHERE reset_token='$token'";

    if ($conn->query($sql) === TRUE) {
        echo "Mot de passe réinitialisé avec succès.";
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    $token = $_GET['token'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser le mot de passe</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Réinitialiser le mot de passe</h2>
    <form action="reset_password.php" method="post">
        <input type="hidden" name="token" value="<?= $token ?>">
        <label for="new_password">Nouveau mot de passe :</label>
        <input type="password" id="new_password" name="new_password" required>
        <button type="submit">Réinitialiser</button>
    </form>
</body>
</html>
