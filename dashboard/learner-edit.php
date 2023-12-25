<?php
//Define page name
$pageName = "Edit Student";
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

            //Query the student 
            $studentDBQuery = DB::query("SELECT * FROM `student` WHERE studentID=%i", $_GET["studentID"]);
            foreach ($studentDBQuery as $studentDBQueryResult) {
              $studentDBQueryID = $studentDBQueryResult["studentID"];
              $studentDBQueryName = $studentDBQueryResult["studentName"];
              $studentDBQueryIndex = $studentDBQueryResult["studentIndex"];
              $studentDBQueryYear = $studentDBQueryResult["studentYear"];
              $studentDBQueryStatus = $studentDBQueryResult["studentStatus"];
              $studentDBQueryClass = $studentDBQueryResult["classID"];
              $studentDBQueryHouse = $studentDBQueryResult["houseID"];
            }

            //ISSET POST form - Add Page
            if (isset($_POST["editStudent"])) {

              //ISSET POST form - Filter Inputs
              $editStudentName = filterInput($_POST["editStudentName"]);
              $editStudentIndex = filterInput($_POST["editStudentIndex"]);

              //ISSET POST - Status Add DropDown field
              if (empty($_POST["editStudentYear"])) {
                $editStudentYear = null;
              } else {
                $editStudentYear = $_POST["editStudentYear"];
              }

              //ISSET POST - Status Add DropDown field
              if (empty($_POST["editStudentStatus"])) {
                $editStudentStatus = null;
              } else {
                $editStudentStatus = $_POST["editStudentStatus"];
              }

              //ISSET POST - Class Add DropDown field
              if (empty($_POST["editClassID"])) {
                $editClassID = null;
              } else {
                $editClassID = $_POST["editClassID"];
              }

              //ISSET POST - House Add DropDown field
              if (empty($_POST["editHouseID"])) {
                $editHouseID = null;
              } else {
                $editHouseID = $_POST["editHouseID"];
              }

              //check if required inputs are not empty
              if ($editStudentName == "" ||  $editStudentIndex == "") {
                authErrorMsg("Please fill up all the required fields.");
              } else {
                //Update student in DB
                DB::startTransaction();
                DB::update('student', [
                  'studentName' => $editStudentName,
                  'studentIndex' => $editStudentIndex,
                  'studentYear' => $editStudentYear,
                  'studentStatus' => $editStudentStatus,
                  'classID' => $editClassID,
                  'houseID' => $editHouseID,
                ], "studentID=%i", $studentDBQueryID);;

                //Student successfully updated
                $success = DB::affectedRows();
                if ($success) {
                  DB::commit();
                  sweetAlertTimerRedirect('Edit Student', 'Student successfully edited!', 'success', (SITE_URL . "student-summary"));
                } else {
                  DB::rollback();
                  sweetAlertTimerRedirect('Edit Student', 'No changes recorded!', 'success', (SITE_URL . "student-summary"));
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
                  <label for="editStudentName" class="labels">Name*</label>
                  <input type="text" name="editStudentName" id="editStudentName" class="form-control" placeholder="Enter student name" value="<?php echo $studentDBQueryName ?>">
                </div>
                <div class="row">
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                    <label for="editStudentIndex" class="labels">Index*</label>
                    <input type="number" name="editStudentIndex" id="editStudentIndex" class="form-control" placeholder="Enter student index" value="<?php echo $studentDBQueryIndex ?>">
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                    <label for="editStudentYear" class="labels">Year*</label>
                    <select name="editStudentYear" class="form-control form-select-option" id='editStudentYear' value="<?php echo $studentDBQueryYear ?>"> </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-12 mt-30"><label class="labels">Status*</label>
                    <br><select class="form-select form-select-option" name="editStudentStatus" aria-label="Default select example">
                      <option disabled>Actions: </option>
                      <option <?php if ($studentDBQueryStatus == 2) {
                                echo 'selected';
                              } ?> value="2">Active</option>
                      <option <?php if ($studentDBQueryStatus == 1) {
                                echo 'selected';
                              } ?> value="1">Inactive</option>
                    </select>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 mt-30">
                    <label for="editClassID" class="labels">Class*</label> <br>
                    <select class="form-select form-select-option" name="editClassID" id="editClassID">
                      <option disabled>Select Class: </option>
                      <?php
                      $queryDBClass = DB::query("SELECT * FROM `class` WHERE classStatus=%i", 2);
                      //Populate all the possible active classes
                      foreach ($queryDBClass as $queryDBClassResults) {
                        $queryDBClassID = $queryDBClassResults["classID"];
                        $queryDBClassName = $queryDBClassResults["className"];
                      ?>
                        <option value="<?php echo $queryDBClassID; ?>" <?php if ($queryDBClassID == $studentDBQueryClass) {
                                                                          echo 'selected';
                                                                        } ?>><?php echo $queryDBClassName; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 mt-30">
                    <label for="editHouseID" class="labels">House*</label><br>
                    <select class="form-select form-select-option" name="editHouseID" id="editHouseID">
                      <option disabled>Select House: </option>
                      <?php
                      $queryDBHouse = DB::query("SELECT * FROM `house` WHERE houseStatus=%i", 2);
                      //Populate all the possible active houses
                      foreach ($queryDBHouse as $queryDBHouseResults) {
                        $queryDBHouseID = $queryDBHouseResults["houseID"];
                        $queryDBHouseName = $queryDBHouseResults["houseName"];
                      ?>
                        <option value="<?php echo $queryDBHouseID; ?>" <?php if ($queryDBHouseID == $studentDBQueryHouse) {
                                                                          echo 'selected';
                                                                        } ?>><?php echo $queryDBHouseName; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                </div>

                <!--------- Actionables ---------->
                <div class="d-flex align-items-center justify-content-end mt-40">
                  <a href="<?php echo SITE_URL . "student-summary" ?>" class="btn-tertiary link-grey">Cancel</a>
                  <button class="btn btn-primary profile-button ml-50" name="editStudent" type="submit">Update Student</button>
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

    let dateDropdown = document.getElementById('editStudentYear');

    let currentYear = new Date().getFullYear();
    let earliestYear = 2000;

    while (currentYear >= earliestYear) {
      let dateOption = document.createElement('option');
      dateOption.text = currentYear;
      dateOption.value = currentYear;
      dateDropdown.add(dateOption);
      currentYear -= 1;
    }
  </script>


</body>

</html>