<?php
//Define page name
$pageName = "Edit Facilitator";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';

//Check if HOD, if not then redirect to dashboard
// if (!isHOD()) {
//   jsRedirect(SITE_URL . 'admin');
// }

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
          <div class="box col-lg-12 col-md-12 col-sm-12 my-5">
            <?php

            // Query the selected facilitator
            $facilitatorDBQuery = DB::query("SELECT * FROM `facilitator` WHERE facilitatorID=%i", $_GET["facilitatorID"]);
            foreach ($facilitatorDBQuery as $facilitatorDBQueryResult) {
              $facilitatorDBQueryID = $facilitatorDBQueryResult["facilitatorID"];
              $facilitatorDBQueryName = $facilitatorDBQueryResult["facilitatorName"];
              $facilitatorDBQueryEmail = $facilitatorDBQueryResult["facilitatorEmail"];
              $facilitatorDBQueryDesignation = $facilitatorDBQueryResult["facilitatorDesignation"];
              $facilitatorDBQueryGender = $facilitatorDBQueryResult["facilitatorGender"];
              $facilitatorDBQueryStatus = $facilitatorDBQueryResult["facilitatorStatus"];
              $facilitatorDBQueryCompany = $facilitatorDBQueryResult["companyID"];
            }

            // ISSET POST form - Edit Facilitator Details
            if (isset($_POST["editFacilitator"])) {

              // ISSET POST form - Facilitator Designation Dropdown
              if (empty($_POST["editFacilitatorDesignation"])) {
                $editFacilitatorDesignation = null;
              } else {
                $editFacilitatorDesignation = $_POST["editFacilitatorDesignation"];
              }

              // ISSET POST form - Facilitator Gender Dropdown
              if (empty($_POST["editFacilitatorGender"])) {
                $editFacilitatorGender = null;
              } else {
                $editFacilitatorGender = $_POST["editFacilitatorGender"];
              }

              // ISSET POST form - Facilitator Status Dropdown
              if (empty($_POST["editFacilitatorStatus"])) {
                $editFacilitatorStatus = null;
              } else {
                $editFacilitatorStatus = $_POST["editFacilitatorStatus"];
              }

              // ISSET POST form - Facilitator Company
              if (empty($_POST["editFacilitatorCompany"])) {
                $editFacilitatorCompany = null;
              } else {
                $editFacilitatorCompany = $_POST["editFacilitatorCompany"];
              }

              // Update facilitator in DB
              DB::startTransaction();
              DB::update('facilitator', [
                'facilitatorDesignation' => $editFacilitatorDesignation,
                'facilitatorGender' => $editFacilitatorGender,
                'facilitatorStatus' => $editFacilitatorStatus,
                'companyID' => $companyID, 
              ], "facilitatorID=%i", $facilitatorDBQueryID);

              // Facilitator successfully updated
              $success = DB::affectedRows();
              if ($success) {
                DB::commit();
                sweetAlertTimerRedirect('Edit Facilitator', 'Facilitator Profile successfully updated!', 'success', (SITE_URL . "facilitator-summary"));
              } else {
                DB::rollback();
                sweetAlertTimerRedirect('Edit Facilitator', 'No changes recorded!', 'success', (SITE_URL . "facilitator-summary"));
              }
            }
            ?>

            <!---------- Main Title ---------->
            <div class="p-3 py-5">
              <div class="d-flex justify-content-between align-items-center mb-30">
                <h4 class="text-right">Edit</h4>
              </div>

              <!---------- Form ---------->
              <form method="POST">
                <div class="row mt-3">
                  <div class="mt-10">
                    <label for="editFacilitatorName" class="labels">Name*</label>
                    <input disabled type="text" name="editFacilitatorName" id="editFacilitatorName" class="form-control" placeholder="E.g. John Doe" value="<?php echo $facilitatorDBQueryName ?>">
                  </div>

                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                    <label for="editFacilitatorEmail" class="labels">Email*</label>
                    <input disabled type="text" name="editFacilitatorEmail" id="editFacilitatorEmail" class="form-control" placeholder="E.g. johndoe@gmail.com" value="<?php echo $facilitatorDBQueryEmail ?>">
                  </div>

                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                    <label for="editFacilitatorDesignation" class="labels">Designation*</label>
                    <br>
                    <select class="form-select form-select-option" name="editFacilitatorDesignation" id="editFacilitatorDesignation">
                      <option disabled>Select Designation: </option>
                      <option value="1" <?php if ($facilitatorDBQueryDesignation == 1) {
                                          echo 'selected';
                                        } ?>>Facilitator</option>
                      <option value="2" <?php if ($facilitatorDBQueryDesignation == 2) {
                                          echo 'selected';
                                        } ?>>Head of Department</option>
                    </select>
                  </div>

                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                    <label for="editFacilitatorGender" class="labels">Gender*</label>
                    <br>
                    <select class="form-select form-select-option" name="editFacilitatorGender" id="editFacilitatorGender">
                      <option disabled>Select Gender: </option>
                      <option value="1" <?php if ($facilitatorDBQueryGender == 1) {
                                          echo 'selected';
                                        } ?>>Male</option>
                      <option value="2" <?php if ($facilitatorDBQueryGender == 2) {
                                          echo 'selected';
                                        } ?>>Female</option>
                      <option value="3" <?php if ($facilitatorDBQueryGender == 3) {
                                          echo 'selected';
                                        } ?>>Rather not say</option>
                    </select>
                  </div>

                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                    <label for="editFacilitatorStatus" class="labels">Status*</label>
                    <br>
                    <select class="form-select form-select-option" name="editFacilitatorStatus" id="editFacilitatorStatus">
                      <option disabled>Select Status: </option>
                      <option value="2" <?php if ($facilitatorDBQueryStatus == 2) {
                                          echo 'selected';
                                        } ?>>Active</option>
                      <option value="1" <?php if ($facilitatorDBQueryStatus == 1) {
                                          echo 'selected';
                                        } ?>>Inactive</option>
                    </select>
                  </div>
                </div>

                <!--------- Actionables ---------->
                <div class="d-flex align-items-center justify-content-end mt-40">
                  <a href="<?php echo SITE_URL . "facilitator-summary" ?>" class="btn-tertiary link-grey">Cancel</a>
                  <button class="btn btn-primary profile-button ml-50" name="editFacilitator" type="submit">Update Facilitator</button>
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