<?php
//Define Page Name
$pageName = "Publish Page";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/footer.php';

//Check if the user is admin, if not redirect away
// if (!isTeacherLoggedIn()) {
//   jsRedirect(SITE_URL . 'admin');
// }

//Update course from database
if (isset($_GET["pageID"])) {
  $getPageID = $_GET["pageID"];
  DB::startTransaction();
  DB::update('page', [
    'pageStatus' => 2,
    'pageDatePublished' => date('Y-m-d H:i:s'),
  ], "pageID=%i", $getPageID);
}
DB::commit();
sweetAlertTimerRedirect('Page Published', 'Page Successfully Published', 'success', (SITE_URL . "infopage-summary"));
