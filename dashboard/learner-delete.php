<?php
// Define Page Name
$pageName = "Delete Learner"; // Updated page title

// Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/footer.php';

// Check if the user is authorized (e.g., admin) - You may adjust this as needed
// if (!isAuthorizedUser()) {
//   jsRedirect(SITE_URL . 'login'); // Redirect to login or appropriate page
// }

// Delete learner from the database
if (isset($_GET["learnerID"])) { // Updated variable name
  $getLearnerID = $_GET["learnerID"]; // Updated variable name
  DB::startTransaction();
  DB::delete('learner', "learnerID=%i", $getLearnerID); // Updated table name and variable name
}
DB::commit();

sweetAlertTimerRedirect('Learner Deleted', 'Learner Successfully Deleted', 'success', (SITE_URL . "learner-summary")); // Updated success message and URL
?>

if (isset($_GET["facilitatorID"])) {
  DB::startTransaction();
  DB::update('facilitator', [
    'facilitatorStatus' => 0,
  ], "facilitatorID=%i", $_GET["facilitatorID"]);
  DB::commit();
  sweetAlertTimerRedirect('Facilitator Deleted', 'Facilitator Successfully Removed', 'success', (SITE_URL . "facilitator-summary"));
}
?>
