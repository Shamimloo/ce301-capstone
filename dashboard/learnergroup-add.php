<?php
// Define page name
$pageName = "Learner Group";
// Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';

// Initialize variables for loading
$addGroupName = $addGroupShortName = $addGroupStatus = $companyID = $facilitatorID = '';

// Check if the companyID is available in session or cookie
if (isset($_SESSION["companyID"])) {
  $companyID = $_SESSION["companyID"];
} elseif (isset($_COOKIE["companyID"])) {
  $companyID = $_COOKIE["companyID"];
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
          <div class="col-12 box my-5">
            <?php

            // ISSET POST form - Add Learner Group
            if (isset($_POST["addGroup"])) {
              $addGroupName = filterInput($_POST["addGroupName"]);
              $addGroupShortName = filterInput($_POST["addGroupShortName"]);

              // Check whether groupName input matches existing learner groups
              DB::query("SELECT groupID FROM learnerGroup WHERE groupName=%s", $addGroupName);
              if (DB::count()) {
                authErrorMsg("Name is already taken.");
              } else {
                // ISSET POST - Group Dropdown
                if (empty($_POST["addGroupStatus"])) {
                  $addGroupStatus = null;
                } else {
                  $addGroupStatus = $_POST["addGroupStatus"];
                }

                // ISSET POST - Facilitator Dropdown
                if (empty($_POST["addGroupFacilitator"])) {
                  $addGroupFacilitator = null;
                } else {
                  $addGroupFacilitator = $_POST["addGroupFacilitator"];
                }

                // ISSET POST - Capacity Field
                if (empty($_POST["addGroupCapacity"])) {
                  $addGroupCapacity = null;
                } else {
                  $addGroupCapacity = $_POST["addGroupCapacity"];
                }

                // Check if required inputs are not empty
                if ($addGroupName == "" || $addGroupShortName == "" || $addGroupFacilitator == "" || $addGroupCapacity == "") {
                  authErrorMsg("Please fill up all the required fields.");
                } else {
                  // Insert learner group into DB
                  DB::startTransaction();
                  DB::insert('learnerGroup', [
                    'groupName' => $addGroupName,
                    'groupShortName' => $addGroupShortName,
                    'groupStatus' => $addGroupStatus,
                    'facilitatorID' => $addGroupFacilitator, // Assuming 'facilitatorID' is the correct column name
                    'companyID' => $companyID, // Assuming 'companyID' is the correct column name
                    'groupCapacity' => $addGroupCapacity, // Assuming 'capacity' is the correct column name
                  ]);

                  // Learner Group successfully added into DB
                  $success = DB::affectedRows();
                  if ($success) {
                    DB::commit();
                    sweetAlertTimerRedirect('Add Learner Group', 'Learner Group successfully added!', 'success', (SITE_URL . "learnergroup-summary"));
                  } else {
                    DB::rollback();
                    sweetAlertTimerRedirect('Add Learner Group', 'No changes recorded!', 'success', (SITE_URL . "learnergroup-summary"));
                  }
                }
              }
            }
            ?>
            <!---------- Main Title ---------->
            <div class="p-3 py-5">
              <div class="d-flex justify-content-between align-items-center mb-30">
                <h4 class="text-right">Add New</h4>
              </div>

              <!--------- Form ---------->
              <form method="POST">
                <div class="mt-10">
                  <label class="labels">Name*</label><input type="text" name="addGroupName" class="form-control" placeholder="E.g. Group Name" value="<?php echo $addGroupName ?>">
                </div>
                <div class="row">
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                    <label class="labels">Short Name*</label> <input type="text" name="addGroupShortName" placeholder="E.g. Short Name" class="form-control" value="<?php echo $addGroupShortName ?>">
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                    <label class="labels">Capacity*</label>
                    <input type="number" name="addGroupCapacity" placeholder="E.g. Capacity" class="form-control" value="<?php echo $addGroupCapacity ?>">
                  </div>


                </div>
                <div class="row">
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                    <label class="labels">Facilitator*</label>
                    <!-- You can populate this dropdown with facilitator options -->
                    <select class="form-select form-select-option" name="addGroupFacilitator" aria-label="Default select example">
                      <option disabled>Select facilitator:</option>
                      <?php
                      // Query facilitators from your database and populate the dropdown
                      $facilitators = DB::query("SELECT * FROM facilitator WHERE facilitatorStatus > %i", 0);
                      foreach ($facilitators as $facilitator) {
                        $facilitatorID = $facilitator['facilitatorID'];
                        $facilitatorName = $facilitator['facilitatorName'];
                        // You can set the selected option if the facilitator matches the current group's facilitator
                        $selected = ($addGroupFacilitator == $facilitatorID) ? 'selected' : '';
                        echo "<option value='$facilitatorID' $selected>$facilitatorName</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                    <label class="labels">Status*</label>
                    <br>
                    <select class="form-select form-select-option" name="addGroupStatus" aria-label="Default select example">
                      <option disabled>Select options below:</option>
                      <option <?php if ($addGroupStatus == 2) {
                                echo 'selected';
                              } ?> value="2">Active</option>
                      <option <?php if ($addGroupStatus == 1) {
                                echo 'selected';
                              } ?> value="1">Inactive</option>
                    </select>
                  </div>
                </div>

                <!--------- Actionables ---------->
                <div class="d-flex align-items-center justify-content-end mt-40">
                  <a href="<?php echo SITE_URL . "learnergroup-summary" ?>" class="btn-tertiary link-grey">Cancel</a>
                  <button class="btn btn-primary profile-button ml-50" name="addGroup" type="submit">Add Learner Group</button>
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