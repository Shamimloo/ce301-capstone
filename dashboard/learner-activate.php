<?php
// Define Page Name
$pageName = "Activate Learner"; // Updated page title
// Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/footer.php';

// Check if the user is admin, if not redirect away
// if (!isTeacherLoggedIn()) {
//   jsRedirect(SITE_URL . 'admin');
// }

// Update learner from database
if (isset($_GET["learnerID"])) { // Updated variable name
  $getLearnerID = $_GET["learnerID"]; // Updated variable name
  DB::startTransaction();
  DB::update('learner', [ // Updated table name
    'learnerStatus' => 2, // Updated column name
  ], "learnerID=%i", $getLearnerID); // Updated table name and variable name
  DB::commit();
  sweetAlertTimerRedirect('Learner Activated', 'Learner Successfully Activated', 'success', (SITE_URL . "learner-summary")); // Updated success message and URL
}
?>
