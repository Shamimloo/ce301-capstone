<?php
// Define Page Name
$pageName = "Edit Category";
// Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';

// Check if admin, if not then redirect to dashboard
// if (!isAdmin()) {
//   jsRedirect(SITE_URL . 'admin');
// }

// Initialize variables for loading
$editCategoryName = $editCategoryStatus = '';

if (!isset($_GET["categoryID"]) || $_GET["categoryID"] == "") {
  jsRedirect(SITE_URL . 'admin');
} else {
  // Query category from DB
  $categoryDBQuery = DB::query("SELECT * FROM `category` WHERE categoryID=%i", $_GET["categoryID"]);
  foreach ($categoryDBQuery as $categoryDBQueryResult) {
    $categoryDBQueryID = $categoryDBQueryResult["categoryID"];
    $categoryDBQueryName = $categoryDBQueryResult["categoryName"];
    $categoryDBQueryStatus = $categoryDBQueryResult["categoryStatus"];
  }

  // ISSET POST form - Edit Category
  if (isset($_POST["editCategory"])) {
    $editCategoryName = filterInput($_POST["editCategoryName"]);

    // POST ISSET - Status Drop Down
    if (empty($_POST["editCategoryStatus"])) {
      $editCategoryStatus = null;
    } else {
      $editCategoryStatus = $_POST["editCategoryStatus"];
    }

    // Check if required inputs are not empty
    if ($editCategoryName == "" || $editCategoryStatus == "") {
      authErrorMsg("All fields are required.");
    } else {
      // Check if changes were made
      if ($editCategoryName == $categoryDBQueryName && $editCategoryStatus == $categoryDBQueryStatus) {
        authErrorMsg("No changes were made.");
      } else {
        // Check whether categoryName input matches existing categories
        DB::query("SELECT categoryID from `category` WHERE categoryName=%s", $editCategoryName);
        if (DB::count() && $editCategoryName != $categoryDBQueryName) {
          authErrorMsg("Name is already taken.");
        } else {
          // Update category in DB
          DB::startTransaction();
          DB::update('category', [
            'categoryName' => $editCategoryName,
            'categoryStatus' => $editCategoryStatus,
          ], "categoryID=%i", $categoryDBQueryID);

          // Category successfully updated
          $success = DB::affectedRows();
          if ($success) {
            DB::commit();
            sweetAlertTimerRedirect('Edit Category', 'Category successfully updated!', 'success', (SITE_URL . "category-summary"));
          } else {
            DB::rollback();
            sweetAlertTimerRedirect('Edit Category', 'No changes recorded!', 'error', (SITE_URL . "category-summary"));
          }
        }
      }
    }
  }
}
?>
<body class="sidebar-expand">
  <!---------- Sidebar / Navbar Include ---------->
  <?php
  include 'assets/templates/dashboard/sidebar.php';
  include 'assets/templates/dashboard/navbar.php';
  ?>

  <!---------- Main Content ---------->
  <div class="main">
    <div class="main-content dashboard">
      <div class="col-12 mt-5">
        <div class="row">
          <div class="col-12 border-right mb-0 box my-5">
            <!---------- Main Title ---------->
            <div class="p-3 py-5">
              <div class="d-flex justify-content-between align-items-center mb-30">
                <h4 class="text-right">Edit Category</h4>
              </div>

              <!---------Form ---------->
              <form method="POST">
                <div class="mt-10">
                  <label for="editCategoryName" class="labels">Category Name*</label>
                  <input type="text" name="editCategoryName" id="editCategoryName" class="form-control" placeholder="Insert Category Name" value="<?php echo $categoryDBQueryName ?>">
                </div>
                <div class="mt-30">
                  <label for="editCategoryStatus" class="labels">Status*</label>
                  <br>
                  <select class="form-select form-select-option" name="editCategoryStatus" id="editCategoryStatus" aria-label="Default select example">
                    <option disabled>Select option:</option>
                    <option value=2 <?php
                                    if ($categoryDBQueryStatus == 2) {
                                      echo 'selected';
                                    } ?>>Active</option>
                    <option value=1 <?php
                                    if ($categoryDBQueryStatus == 1) {
                                      echo 'selected';
                                    } ?>>Inactive</option>
                  </select>
                </div>

                <!--------- Actionables ---------->
                <div class="d-flex align-items-center justify-content-end mt-40">
                  <a href="<?php echo SITE_URL . "category-summary" ?>" class="btn-tertiary link-grey">Cancel</a>
                  <button class="btn btn-primary profile-button ml-50" name="editCategory" type="submit">Update Category</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="overlay"></div>

  <!---------- Footer Include ---------->
  <?php
  include 'assets/templates/dashboard/footer.php';
  ?>
</body>

</html>
