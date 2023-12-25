<?php
// Define Page Name
$pageName = "Deactivate Learner Group";
// Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/footer.php';

// Check if the user has the necessary permissions to deactivate learner groups, if not redirect away
// Replace this condition with your permission check logic
// if (!hasPermissionToDeactivateLearnerGroup()) {
//   jsRedirect(SITE_URL . 'dashboard');
// }

// Update learner group status in the database
if (isset($_GET["groupID"])) {
  $getGroupID = $_GET["groupID"];
  DB::startTransaction();
  DB::update('learnerGroup', [
    'groupStatus' => 1, // Set the status to 'Inactive' (adjust the value as needed)
  ], "groupID=%i", $getGroupID);
  DB::commit();
  sweetAlertTimerRedirect('Learner Group Deactivated', 'Learner Group Successfully Deactivated', 'success', (SITE_URL . "learnergroup-summary"));
} else {
  // Handle the case where groupID is not set (e.g., redirect to an error page)
  // You can customize this part based on your application's requirements.
  // jsRedirect(SITE_URL . 'error-page');
}
?>
