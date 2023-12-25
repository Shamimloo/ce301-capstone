<?php
// Define Page Name
$pageName = "Learner Deactivate";

// Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/footer.php';

// Check if the user is authorized (e.g., admin) - You may adjust this as needed
// if (!isAuthorizedUser()) {
//   jsRedirect(SITE_URL . 'login'); // Redirect to login or appropriate page
// }

// Update learner from the database
if (isset($_GET["learnerID"])) {
  $getLearnerID = $_GET["learnerID"];
  DB::startTransaction();
  DB::update('learner', [
    'learnerStatus' => 1, // Set the status to 1 (Inactive) as per your database schema
  ], "learnerID=%i", $getLearnerID);
}
DB::commit();

sweetAlertTimerRedirect('Learner Deactivated', 'Learner Successfully Deactivated', 'success', (SITE_URL . "learner-summary"));
?>
