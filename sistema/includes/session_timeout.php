<?php
if (isset($_SESSION['last_activity']) && isset($_SESSION['idUser'])) {
    $inactive_time = time() - $_SESSION['last_activity'];

    if ($inactive_time > 600) { // 10 minutes of inactivity
        session_unset();
        session_destroy();
        header('location: ../');
        exit;
    } 
}

$_SESSION['last_activity'] = time();
?>
