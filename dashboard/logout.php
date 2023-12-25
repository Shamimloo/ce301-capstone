<?php
//Define Page Name
$pageName = "Logout";
//Include Header
include 'assets/templates/dashboard/header.php';

// If the user is logged in as a facilitator
if (isFacilitatorLoggedIn()) {
    if (isset($_COOKIE["facilitatorName"]) && isset($_COOKIE["facilitatorEmail"]) && isset($_COOKIE["facilitatorPermission"])) {
        // Delete the cookie
        setcookie("facilitatorName", "", -100);
        setcookie("facilitatorEmail", "", -100);
        setcookie("facilitatorPermission", "", -100);
        $_SESSION["cookie"] = null;
    } elseif (isset($_SESSION["facilitatorName"]) && isset($_SESSION["facilitatorEmail"]) && isset($_SESSION["facilitatorPermission"]) && isset($_SESSION["facilitatorDesignation"])) {
        // Clear & Destroy all sessions
        session_unset(); // Remove all session variables
        session_destroy(); // Destroy the session
    }
    sweetAlertTimerRedirect('Logout', 'Facilitator successfully logged out!', 'success', (SITE_ROOT . "?site=login"));
} else {
    // Clear & Destroy all sessions
    session_unset(); // Remove all session variables
    session_destroy(); // Destroy the session
    sweetAlertTimerRedirect('Logout', 'Facilitator successfully logged out!', 'success', (SITE_ROOT . "?site=login"));
}


include 'assets/templates/dashboard/footer.php';
