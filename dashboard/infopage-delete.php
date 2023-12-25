<?php
//Define Page Name
$pageName = "Delete Page";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/footer.php';

//Check if the user is teacher, if not redirect away
// if (!isTeacherLoggedIn()) {
//   jsRedirect(SITE_URL . 'login');
// }

//Update page from DB
if (isset($_GET["pageID"])) {
  $getPageID = $_GET["pageID"];
  DB::startTransaction();
  DB::update('page', [
    'pageStatus' => 0,
  ], "pageID=%i", $getPageID);
}
DB::commit();
sweetAlertTimerRedirect('Page Deleted', 'Page Successfully Deleted', 'success', (SITE_URL . "infopage-summary"));
