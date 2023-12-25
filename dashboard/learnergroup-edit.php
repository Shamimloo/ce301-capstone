<?php
//Define page name
$pageName = "Learner Group";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';
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
        <div class="box col-lg-12 col-md-12 col-sm-12 my-5">
          <?php
          //Query the active learner groups 
          $learnerGroupDBQuery = DB::query("SELECT * FROM `learnerGroup` WHERE groupID=%i", $_GET["groupID"]);
          foreach ($learnerGroupDBQuery as $learnerGroupDBQueryResults) {
            $learnerGroupDBQueryID = $learnerGroupDBQueryResults["groupID"];
            $learnerGroupDBQueryName = $learnerGroupDBQueryResults["groupName"];
            $learnerGroupDBQueryShortName = $learnerGroupDBQueryResults["groupShortName"];
            $learnerGroupDBQueryStatus = $learnerGroupDBQueryResults["groupStatus"];
          }

          // ISSET POST form - Edit Profile
          if (isset($_POST["editGroup"])) {
            $editGroupName = filterInput($_POST["editGroupName"]); // filter the input and grab the name from the input field
            $editGroupShortName = filterInput($_POST["editGroupShortName"]);

            if (empty($_POST["editGroupStatus"])) {
              $editGroupStatus = null;
            } else {
              $editGroupStatus = $_POST["editGroupStatus"];
            }

            // Check if required inputs are not empty
            if ($editGroupName == "" || $editGroupShortName == "") {
              authErrorMsg("Please fill up all the required fields.");
            } else {
              // Check if changes were made
              if ($editGroupName == $learnerGroupDBQueryName && $editGroupShortName == $learnerGroupDBQueryShortName) {
                authErrorMsg("No changes were made.");
              } else {
                // Check whether groupName input matches existing groups
                DB::query("SELECT groupID FROM learnerGroup WHERE groupName=%s", $editGroupName);
                if (DB::count() && $editGroupName != $learnerGroupDBQueryName) {
                  authErrorMsg("Name is already taken.");
                } else {
                  // Update group in DB
                  DB::startTransaction();
                  DB::update('learnerGroup', [
                    'groupName' => $editGroupName,
                    'groupShortName' => $editGroupShortName,
                    'groupStatus' => $editGroupStatus,
                  ], "groupID=%i", $learnerGroupDBQueryID);

                  // Group successfully updated
                  $success = DB::affectedRows();
                  if ($success) {
                    DB::commit();
                    sweetAlertTimerRedirect('Update Learner Group', 'Learner Group successfully updated!', 'success', (SITE_URL . "learnergroup-summary"));
                  } else {
                    DB::rollback();
                    sweetAlertTimerRedirect('Update Learner Group', 'Learner Group update failed!', 'error', (SITE_URL . "learnergroup-summary"));
                  }
                }
              }
            }
          }

          ?>

          <!---------- Main Title ---------->
          <div class="p-3 py-5">
            <div class="d-flex justify-content-between align-items-center mb-30">
              <h4 class="text-right">Edit</h4>
            </div>

            <!---------Form ---------->
            <form method="POST">
              <div class="mt-10">
                <label class="labels">Name*</label>
                <input type="text" name="editGroupName" class="form-control" placeholder="E.g. Group Name" value="<?php echo $learnerGroupDBQueryName ?>">
              </div>
              <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                  <label class="labels">Short Name*</label>
                  <input type="text" placeholder="E.g. Short Name" name="editGroupShortName" class="form-control" value="<?php echo $learnerGroupDBQueryShortName ?>">
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                  <label class="labels">Status*</label>
                  <br>
                  <select class="form-select form-select-option" name="editGroupStatus" aria-label="Default select example">
                    <option disabled>Select options below:</option>
                    <option <?php if ($learnerGroupDBQueryStatus == 2) {
                              echo 'selected';
                            } ?> value="2">Active</option>
                    <option <?php if ($learnerGroupDBQueryStatus == 1) {
                              echo 'selected';
                            } ?> value="1">Inactive</option>
                  </select>
                </div>
              </div>

              <!--------- Actionables ---------->
              <div class="d-flex align-items-center justify-content-end mt-40">
                <a href="<?php echo SITE_URL . "learnergroup-summary" ?>" class="btn-tertiary link-grey">Cancel</a>
                <button class="btn btn-primary profile-button ml-50" name="editGroup" type="submit">Update Group</button>
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