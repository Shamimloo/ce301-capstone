<?php
// Define Page Name
$pageName = "Activate Learner Group";
// Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/footer.php';

// Check if the user has the necessary permissions to activate learner groups, if not redirect away
// Replace this condition with your permission check logic
// if (!hasPermissionToActivateLearnerGroup()) {
//   jsRedirect(SITE_URL . 'dashboard');
// }

// Update learner group status in the database
if (isset($_GET["groupID"])) {
  $getGroupID = $_GET["groupID"];
  DB::startTransaction();
  DB::update('learnerGroup', [
    'groupStatus' => 2, // Set the status to 'Active' (adjust the value as needed)
  ], "groupID=%i", $getGroupID);
  DB::commit();
  sweetAlertTimerRedirect('Learner Group Activated', 'Learner Group Successfully Activated', 'success', (SITE_URL . "learnergroup-summary"));
} else {
  // Handle the case where groupID is not set (e.g., redirect to an error page)
  // You can customize this part based on your application's requirements.
  // jsRedirect(SITE_URL . 'error-page');
}
?>
