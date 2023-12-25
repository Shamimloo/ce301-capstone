<?php
//Define Page Name
$pageName = "Delete Student House";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/footer.php';

//Check if the user is admin, if not redirect away
// if (!isTeacherLoggedIn()) {
//   jsRedirect(SITE_URL . 'admin');
// }

//Update house from database
if (isset($_GET["houseID"])) {
  $getHouseID = $_GET["houseID"];
  DB::startTransaction();
  DB::update('house', [
    'houseStatus' => 0,
  ], "houseID=%i", $getHouseID);
}
DB::commit();
sweetAlertTimerRedirect('Student House Delete', 'House Successfully Deleted', 'success', (SITE_URL . "studenthouse-summary"));
