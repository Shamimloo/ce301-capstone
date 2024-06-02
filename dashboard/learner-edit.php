<?php
//Define page name
$pageName = "Edit Learner";
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
          <div class="col-12 box my-5">
            <?php

            //Query the learner 
            $learnerDBQuery = DB::query("SELECT * FROM `learner` WHERE learnerID=%i", $_GET["learnerID"]);
            foreach ($learnerDBQuery as $learnerDBQueryResult) {
              $learnerDBQueryID = $learnerDBQueryResult["learnerID"];
              $learnerDBQueryName = $learnerDBQueryResult["learnerName"];
              $learnerDBQueryStatus = $learnerDBQueryResult["learnerStatus"];
              $learnerDBQueryGroup = $learnerDBQueryResult["groupID"];
            }

            //ISSET POST form - Add Page
            if (isset($_POST["editLearner"])) {

              //ISSET POST form - Filter Inputs
              $editLearnerName = filterInput($_POST["editLearnerName"]);


              //ISSET POST - Status Add DropDown field
              if (empty($_POST["editLearnerStatus"])) {
                $editLearnerStatus = null;
              } else {
                $editLearnerStatus = $_POST["editLearnerStatus"];
              }

              //ISSET POST - Group Add DropDown field
              if (empty($_POST["editGroupID"])) {
                $editGroupID = null;
              } else {
                $editGroupID = $_POST["editGroupID"];
              }


              //check if required inputs are not empty
              if ($editLearnerName == "") {
                authErrorMsg("Please fill up all the required fields.");
              } else {
                //Update learner in DB
                DB::startTransaction();
                DB::update('learner', [
                  'learnerName' => $editLearnerName,
                  'learnerStatus' => $editLearnerStatus,
                  'groupID' => $editGroupID,
                ], "learnerID=%i", $learnerDBQueryID);;

                //Learner successfully updated
                $success = DB::affectedRows();
                if ($success) {
                  DB::commit();
                  sweetAlertTimerRedirect('Edit Learner', 'Learner successfully edited!', 'success', (SITE_URL . "learner-summary"));
                } else {
                  DB::rollback();
                  sweetAlertTimerRedirect('Edit Learner', 'No changes recorded!', 'success', (SITE_URL . "learner-summary"));
                }
              }
            }
            ?>

            <!---------- Main Title ---------->
            <div class="p-3 py-5">
              <div class="d-flex justify-content-between align-items-center mb-30">
                <h4 class="text-right">Add New</h4>
              </div>

              <!---------Form ---------->
              <form method="POST">
                <div class="mt-10">
                  <label for="editLearnerName" class="labels">Name*</label>
                  <input type="text" name="editLearnerName" id="editLearnerName" class="form-control" placeholder="Enter learner name" value="<?php echo $learnerDBQueryName ?>">
                </div>
                <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-12 mt-30"><label class="labels">Status*</label>
                    <br><select class="form-select form-select-option" name="editLearnerStatus" aria-label="Default select example">
                      <option disabled>Actions: </option>
                      <option <?php if ($learnerDBQueryStatus == 2) {
                                echo 'selected';
                              } ?> value="2">Active</option>
                      <option <?php if ($learnerDBQueryStatus == 1) {
                                echo 'selected';
                              } ?> value="1">Inactive</option>
                    </select>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 mt-30">
                    <label for="editGroupID" class="labels">Group*</label> <br>
                    <select class="form-select form-select-option" name="editGroupID" id="editGroupID">
                      <option disabled>Select Learner Group: </option>
                      <?php
                      $queryDBGroup = DB::query("SELECT * FROM `learnerGroup` WHERE groupStatus=%i", 2);
                      //Populate all the possible active groupes
                      foreach ($queryDBGroup as $queryDBGroupResults) {
                        $queryDBGroupID = $queryDBGroupResults["groupID"];
                        $queryDBGroupName = $queryDBGroupResults["groupName"];
                      ?>
                        <option value="<?php echo $queryDBGroupID; ?>" <?php if ($queryDBGroupID == $learnerDBQueryGroup) {
                                                                          echo 'selected';
                                                                        } ?>><?php echo $queryDBGroupName; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <!--------- Actionables ---------->
                <div class="d-flex align-items-center justify-content-end mt-40">
                  <a href="<?php echo SITE_URL . "learner-summary" ?>" class="btn-tertiary link-grey">Cancel</a>
                  <button class="btn btn-primary profile-button ml-50" name="editLearner" type="submit">Update Learner</button>
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

  <script>
    //CKEditor.js
    CKEDITOR.replace('addPageDescription');
  </script>


</body>

</html>