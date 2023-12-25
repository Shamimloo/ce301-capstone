<?php
// include "assets/lib/db.class.php";
// include "assets/lib/config.php";
// include "assets/lib/functions.php";
// include "assets/lib/validation.php";
// include "assets/lib/alerts.php";

//If the user is logged in
if (!isLearnerLoggedIn()) {
  jsRedirect(SITE_URL . "quizzes-login");
} else {
  //Clear & Destroy all sessions
  session_unset(); // remove all session variables
  session_destroy(); // destroy the session
  jsRedirect(SITE_URL . "quizzes-login");
}
