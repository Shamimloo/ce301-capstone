<?php
//Define page name
$pageName = "Add Facilitator";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';

//Check if HOD, if not then redirect to dashboard
// if (!isHOD()) {
//   jsRedirect(SITE_URL . 'admin');
// }

// Initialize variables for input fields
$addFacilitatorName = '';
$addFacilitatorEmail = '';
$addFacilitatorPassword = '';
$addFacilitatorDesignation = '';
$addFacilitatorGender = '';
$addFacilitatorStatus = '';
$companyID = ''; // Initialize companyID variable

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

  <div class="overlay"></div>


  <div class="main">
    <div class="main-content dashboard">
      <div class="col-12 mt-5">
        <div class="row">
          <div class="col-12 box my-5">
            <div class="p-3 py-5">

              <?php
              // ISSET POST form - Add Facilitator
              if (isset($_POST["addFacilitator"])) {

                // Array - Facilitator Name
                if (!empty($_POST["addFacilitatorName"]) && $_POST["addFacilitatorName"] != "") {
                  $addFacilitatorName = filterInput($_POST["addFacilitatorName"]);
                  $facilitatorNewDetails["facilitatorName"] = $addFacilitatorName;
                }

                // Array - Facilitator Email
                if (!empty($_POST["addFacilitatorEmail"]) && $_POST["addFacilitatorEmail"] != "") {
                  $addFacilitatorEmail = filterInput($_POST["addFacilitatorEmail"]);
                  if (!isValidEmail($addFacilitatorEmail)) {
                    authErrorMsg("Please input a valid email address.");
                  } else {
                    $facilitatorNewDetails["facilitatorEmail"] = $addFacilitatorEmail;
                  }
                }

                // Array - Facilitator Password
                if (!empty($_POST["addFacilitatorPassword"]) || $_POST["addFacilitatorPassword"] != "") {
                  $addFacilitatorPassword = filterInput($_POST["addFacilitatorPassword"]);
                  $facilitatorNewDetails["facilitatorPassword"] = password_hash($_POST["addFacilitatorPassword"], PASSWORD_DEFAULT);
                }


                // Array - Facilitator Designation
                if (!empty($_POST["addFacilitatorDesignation"])) {
                  $addFacilitatorDesignation = $_POST["addFacilitatorDesignation"];
                  $facilitatorNewDetails["facilitatorDesignation"] = $_POST["addFacilitatorDesignation"];
                }

                // Array - Facilitator Gender
                if (!empty($_POST["addFacilitatorGender"])) {
                  $addFacilitatorGender = $_POST["addFacilitatorGender"];
                  $facilitatorNewDetails["facilitatorGender"] = $_POST["addFacilitatorGender"];
                }

                // Array - Facilitator Status
                if (!empty($_POST["addFacilitatorStatus"])) {
                  $addFacilitatorStatus = $_POST["addFacilitatorStatus"];
                  $facilitatorNewDetails["facilitatorStatus"] = $_POST["addFacilitatorStatus"];
                }

                // Include companyID in the $facilitatorNewDetails array
                $facilitatorNewDetails["companyID"] = $companyID;

                if (empty($_POST["addFacilitatorName"]) || $_POST["addFacilitatorName"] == "" || empty($_POST["addFacilitatorEmail"]) || $_POST["addFacilitatorEmail"] == "" || empty($_POST["addFacilitatorPassword"]) || $_POST["addFacilitatorPassword"] == "") {
                  authErrorMsg("Please fill up all the required fields.");
                } else {
                  if (!isValidPassword($_POST["addFacilitatorPassword"])) {
                    authErrorMsg("Password requirements not met. Please input 8 characters with 1 uppercase, 1 number and 1 special char.");
                  } else {
                    // Check whether facilitator name input matches existing facilitators
                    DB::query("SELECT facilitatorName FROM facilitator WHERE facilitatorName=%s AND facilitatorStatus>%i", $addFacilitatorName, 0);
                    if (DB::count()) {
                      authErrorMsg("Facilitator name already exists.");
                    } else {
                      DB::query("SELECT facilitatorEmail FROM facilitator WHERE facilitatorEmail=%s AND facilitatorStatus>%i", $addFacilitatorEmail, 0);
                      if (DB::count()) {
                        authErrorMsg("Facilitator email already exists.");
                      } else {
                        // Insert facilitator into DB
                        DB::startTransaction();
                        DB::insert(
                          'facilitator',
                          $facilitatorNewDetails
                        );

                        // Facilitator successfully added into DB
                        $success = DB::affectedRows();
                        if ($success) {
                          DB::commit();
                          sweetAlertTimerRedirect('Add Facilitator', 'Facilitator successfully added!', 'success', (SITE_URL . "facilitator-summary"));
                        } else {
                          DB::rollback();
                          sweetAlertTimerRedirect('Add Facilitator', 'No changes recorded!', 'success', (SITE_URL . "facilitator-summary"));
                        }
                      }
                    }
                  }
                }
              }
              ?>

              <!---------- Main Title ---------->
              <div class="d-flex justify-content-between align-items-center mb-30">
                <h4 class="text-right">Add New Facilitator</h4>
              </div>

              <!--------- Form ---------->
              <form method="POST">
                <div class="mt-10">
                  <label for="addFacilitatorName" class="labels">Name*</label>
                  <input type="text" name="addFacilitatorName" id="addFacilitatorName" class="form-control" placeholder="Enter facilitator name" value="<?php echo $addFacilitatorName ?>">
                </div>
                <div class="row">
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                    <label for="addFacilitatorEmail" class="labels">Email*</label>
                    <input type="email" name="addFacilitatorEmail" class="form-control" placeholder="Enter email" value="<?php echo $addFacilitatorEmail ?>">
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                    <label for="addFacilitatorPassword" class="labels">Password*</label>
                    <input type="password" name="addFacilitatorPassword" class="form-control" value="<?php echo $addFacilitatorPassword ?>">
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-12 mt-30">
                    <label class="labels">Designation</label>
                    <br>
                    <select class="form-select form-select-option" name="addFacilitatorDesignation" aria-label="Default select example">
                      <option disabled>Select options below:</option>
                      <option <?php if ($addFacilitatorDesignation == 1) {
                                echo 'selected';
                              } ?> value="1">Facilitator</option>
                      <option <?php if ($addFacilitatorDesignation == 2) {
                                echo 'selected';
                              } ?> value="2">Head of Department</option>
                    </select>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 mt-30">
                    <label class="labels">Gender*</label>
                    <br>
                    <select class="form-select form-select-option" name="addFacilitatorGender" aria-label="Default select example">
                      <option disabled>Select options below:</option>
                      <option <?php if ($addFacilitatorGender == 1) {
                                echo 'selected';
                              } ?> value="1">Male</option>
                      <option <?php if ($addFacilitatorGender == 2) {
                                echo 'selected';
                              } ?> value="2">Female</option>
                      <option <?php if ($addFacilitatorGender == 3) {
                                echo 'selected';
                              } ?> value="3">Rather not say</option>
                    </select>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 mt-30">
                    <label class="labels">Status*</label>
                    <br>
                    <select class="form-select form-select-option" name="addFacilitatorStatus" aria-label="Default select example">
                      <option disabled>Actions</option>
                      <option <?php if ($addFacilitatorStatus == 2) {
                                echo 'selected';
                              } ?> value="2">Active</option>
                      <option <?php if ($addFacilitatorStatus == 1) {
                                echo 'selected';
                              } ?> value="1">Inactive</option>
                    </select>
                  </div>
                </div>

                <!-- Additional fields or controls can be added here as needed -->

                <!--------- Actionables ---------->
                <div class="d-flex align-items-center justify-content-end mt-40">
                  <a href="<?php echo SITE_URL . "facilitator-summary" ?>" class="btn-tertiary link-grey">Cancel</a>
                  <button class="btn btn-primary profile-button ml-50" name="addFacilitator" type="submit">Add Facilitator</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



  <!---------- Footer Include ---------->
  <?php
  include 'assets/templates/dashboard/footer.php';
  ?>

  <!-- <script>
    //AJAX get class
    function getClass(classID) {
      $.ajax({
        type: "POST",
        url: "<?php echo SITE_URL . 'studentaddfilter' ?>",
        data: {
          classID: classID,
        },
        success: function(data) {
          $("#student-index").html(data);
        }
      });
    };
  </script>

  <script>
    //CKEditor 
    CKEDITOR.replace('addPageDescription');

    let dateDropdown = document.getElementById('addStudentYear');

    let currentYear = new Date().getFullYear();
    let earliestYear = 2000;

    while (currentYear >= earliestYear) {
      let dateOption = document.createElement('option');
      dateOption.text = currentYear;
      dateOption.value = currentYear;
      dateDropdown.add(dateOption);
      currentYear -= 1;
    }
  </script> -->

</body>

</html>