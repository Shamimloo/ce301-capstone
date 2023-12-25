<?php
// Define Page Name
$pageName = "Deactivate Facilitator";
// Include Header & Footer
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/footer.php';

// Check if HOD, if not then redirect to dashboard
// if (!isHOD()) {
//   jsRedirect(SITE_URL . 'admin');
// }

// Deactivate facilitator from database
if (isset($_GET["facilitatorID"])) {
  DB::startTransaction();
  DB::update('facilitator', [
    'facilitatorStatus' => 1,
  ], "facilitatorID=%i", $_GET["facilitatorID"]);
  DB::commit();
  sweetAlertTimerRedirect('Facilitator Deactivated', 'Facilitator Successfully Deactivated', 'success', (SITE_URL . "facilitator-summary"));
}
?>
