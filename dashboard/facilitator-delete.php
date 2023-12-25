<?php
// Define Page Name
$pageName = "Delete Facilitator";
// Include Header & Footer
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/footer.php';

// Check if HOD, if not then redirect to dashboard
// if (!isHOD()) {
//   jsRedirect(SITE_URL . 'admin');
// }

// Delete facilitator from database
if (isset($_GET["facilitatorID"])) {
  DB::startTransaction();
  DB::update('facilitator', [
    'facilitatorStatus' => 0,
  ], "facilitatorID=%i", $_GET["facilitatorID"]);
  DB::commit();
  sweetAlertTimerRedirect('Facilitator Deleted', 'Facilitator Successfully Removed', 'success', (SITE_URL . "facilitator-summary"));
}
?>
