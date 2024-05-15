<?php
//Define Page Name
$pageName = "Delete Learner Group";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/footer.php';

// Check if the user is admin, if not redirect away
if (!isFacilitatorLoggedIn()) {
  jsRedirect(SITE_URL . 'admin');
}

//Update group from database
if (isset($_GET["groupID"])) {
  $getGroupID = $_GET["groupID"];
  DB::startTransaction();
  DB::update('learnerGroup', [
    'groupStatus' => 0,
  ], "groupID=%i", $getGroupID);
}
DB::commit();
sweetAlertTimerRedirect('Learner Group Delete', 'Group Successfully Deleted', 'success', (SITE_URL . "learnergroup-summary"));
