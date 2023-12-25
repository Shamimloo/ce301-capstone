<?php
//Define Page Name
$pageName = "Unpublish Page";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/footer.php';

//Check if the user is admin, if not redirect away
// if (!isTeacherLoggedIn()) {
//   jsRedirect(SITE_URL . 'admin');
// }

//Update infopage from DB
if (isset($_GET["pageID"])) {
  $getPageID = $_GET["pageID"];
  DB::startTransaction();
  DB::update('page', [
    'pageStatus' => 1,
  ], "pageID=%i", $getPageID);
}
DB::commit();
sweetAlertTimerRedirect('Page Removed', 'Page Successfully Brought Down', 'success', (SITE_URL . "infopage-summary"));
