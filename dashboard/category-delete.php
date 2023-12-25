<?php
// Define Page Name
$pageName = "Delete Category";
// Include Header & Footer
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/footer.php';

// Check if admin, if not then redirect to dashboard
// if (!isAdmin()) {
//   jsRedirect(SITE_URL . 'admin');
// }

// Update category status in the database
if (isset($_GET["categoryID"])) {
  DB::startTransaction();
  DB::update('category', [
    'categoryStatus' => 0,
  ], "categoryID=%i", $_GET["categoryID"]);
  DB::commit();
}

// Redirect back to the previous page URL
sweetAlertTimerRedirect('Category Deleted', 'Category Successfully Removed', 'success', (SITE_URL . "category-summary"));
?>
