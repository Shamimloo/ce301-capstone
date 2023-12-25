<?php
// Define Page Name
$pageName = "Activate Facilitator";
// Include Header & Footer
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/footer.php';

// Check if HOD, if not then redirect to dashboard
// if (!isHOD()) {
//   jsRedirect(SITE_URL . 'admin');
// }

// Activate facilitator in the database
if (isset($_GET["facilitatorID"])) {
  DB::startTransaction();
  DB::update('facilitator', [
    'facilitatorStatus' => 2, // Change the status to 2 to activate the facilitator
  ], "facilitatorID=%i", $_GET["facilitatorID"]);
  DB::commit();
  sweetAlertTimerRedirect('Facilitator Restored', 'Facilitator Successfully Restored', 'success', (SITE_URL . "facilitator-summary"));
}
?>
