<?php
session_start();


$logout_success = false; 

if (isset($_SESSION['id'])) { // Check if user is logged in
    
    $_SESSION = array();


    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    
    session_destroy();
    
    // Set the logout success flag
    $logout_success = true;
}

header("Location: index.php?" );
exit();
