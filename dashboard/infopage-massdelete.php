<?php
//Define Page Name
$pageName = "Delete Page";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/footer.php';

//Check if the user is admin, if not redirect away
// if (!isTeacherLoggedIn()) {
//   jsRedirect(SITE_URL . 'admin');
// }

//Update page from DB
// Handle mass delete request
// if (isset($_POST['deleteSelectedBtn'])) {
  if (isset($_POST['pageIDs'])) {
    $pageIDs = $_POST['pageIDs'];

    // Convert comma-separated string to array
    if (is_string($pageIDs)) {
      $pageIDs = explode(',', $pageIDs);
    }

    // Delete pages from DB
    foreach ($pageIDs as $pageID) {
      DB::startTransaction();
      DB::update('page', [
        'pageStatus' => 0,
      ], "pageID=%i", (int)$pageID);
      DB::commit();
    }

    jsRedirect(SITE_URL . 'infopage-summary');
  }
// }


// DB::commit();
// sweetAlertTimerRedirect('Page Deleted', 'Page Successfully Deleted', 'success', (SITE_URL . "infopage-summary"));
