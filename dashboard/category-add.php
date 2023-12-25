<?php
// Define page name
$pageName = "Category";
// Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';

// Check if admin, if not then redirect to dashboard
// if (!isAdmin()) {
//   jsRedirect(SITE_URL . 'admin');
// }

// Initialize variables for loading
$addCategoryName = $addCategoryStatus = '';

if (isset($_SESSION['companyID'])) {
  $companyID = $_SESSION['companyID'];
} else {
  $companyID = $_COOKIE['companyID'];
}
?>

<body class="sidebar-expand">
  <!-- Sidebar / Navbar Include -->
  <?php
  include 'assets/templates/dashboard/sidebar.php';
  include 'assets/templates/dashboard/navbar.php';
  ?>

  <!-- Main Content -->
  <div class="main">
    <div class="main-content dashboard">
      <div class="col-12 mt-5">
        <div class="row">
          <div class="col-12 border-right mb-0 box my-5">
            <?php

            // ISSET POST form - Add Category
            if (isset($_POST["addCategory"])) {
              $addCategoryName = filterInput($_POST["addCategoryName"]);

              // Check whether categoryName input matches existing categories
              DB::query("SELECT categoryID FROM `category` WHERE categoryName=%s", $addCategoryName);
              if (DB::count()) {
                authErrorMsg("Name is already taken.");
              } else {

                // ISSET POST - Category Drop Down
                if (empty($_POST["addCategoryStatus"])) {
                  $addCategoryStatus = null;
                } else {
                  $addCategoryStatus = $_POST["addCategoryStatus"];
                }

                // Check if required inputs are not empty
                if (
                  $addCategoryName == "" || $addCategoryStatus == ""
                ) {
                  authErrorMsg("All fields are required.");
                } else {
                  // Insert Category into DB
                  DB::startTransaction();
                  DB::insert('category', [
                    'categoryName' => $addCategoryName,
                    'categoryStatus' => $addCategoryStatus,
                    'companyID' => $companyID
                  ]);
                  // Category Successfully Added to DB
                  $success = DB::affectedRows();
                  if ($success) {
                    DB::commit();
                    sweetAlertTimerRedirect('Add Category', 'Category successfully added!', 'success', (SITE_URL . "category-summary"));
                  } else {
                    DB::rollback();
                    sweetAlertTimerRedirect('Add Category', 'No changes recorded!', 'error', (SITE_URL . "category-summary"));
                  }
                }
              }
            }
            ?>
            <div class="p-3 py-5">
              <div class="d-flex justify-content-between align-items-center mb-30">
                <h4 class="text-right">Add New</h4>
              </div>
              <form method="POST" enctype="multipart/form-data">
                <div class="mt-10">
                  <label for="addCategoryName" class="labels">Category Title*</label>
                  <input type="text" name="addCategoryName" id="addCategoryName" class="form-control" placeholder="Insert Category Name" value="<?php echo $addCategoryName ?>">
                </div>
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 mt-30">
                    <label for="addCategoryStatus" class="labels">Status*</label>
                    <br>
                    <select class="form-select form-select-option" name="addCategoryStatus" id="addCategoryStatus" aria-label="Default select example">
                      <option disabled>Select option: </option>
                      <option value="2" <?php
                                        if ($addCategoryStatus == 2) {
                                          echo 'selected';
                                        } ?>>Active</option>
                      <option value="1" <?php
                                        if ($addCategoryStatus == 1) {
                                          echo 'selected';
                                        }
                                        ?>>Inactive</option>
                    </select>
                  </div>
                </div>
                <div class="d-flex align-items-center justify-content-end mt-40">
                  <a href="<?php echo SITE_URL . "category-summary" ?>" class="btn-tertiary link-grey">Cancel</a>
                  <button class="btn btn-primary profile-button ml-50" name="addCategory" type="submit">Add Category</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="overlay"></div>

  <!-- Footer Include -->
  <?php
  include 'assets/templates/dashboard/footer.php';
  ?>

</body>

</html>