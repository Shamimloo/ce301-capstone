<?php
//Define page name
$pageName = "Profile";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';

//Create an array of details
$facilitatorNewDetails = array(
  // 'facilitatorName' => $facilitatorDBName,
  // 'facilitatorEmail',
  // 'facilitatorPassword',
  // 'facilitatorDepartment',
  // 'facilitatorDesignation',
  // 'facilitatorGender',
  // 'facilitatorPhone',
  // 'facilitatorProfileImage'
);
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
      <div class="user-pro-list box my-5">
        <div class="row">
          <div class="col-lg-4 col-md-12 col-sm-12 border-right mb-0">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
              <div class="avatar fs-2">
                <div class="profile-img">
                  <?php if (!$facilitatorDBPicture) {
                    echo '<p>' . makeInitials($facilitatorDBName) . '</p>';
                  } else {
                    echo '<img src="assets/images/profile/' . $facilitatorDBPicture . '">';
                  } ?>
                </div>
              </div>
              <h6 class="pt-3 mb-0"><?php echo $facilitatorDBName ?></h6>
              <span class="text-black-50 pt-2"><?php echo $facilitatorDBEmail ?></span><span> </span>
            </div>
          </div>
          <div class="col-lg-8 col-md-12 col-sm-12 mb-0">
            <?php
            if (isset($_POST["editFacilitator"])) {

              //Array - Facilitator Name
              if (empty($_POST["editFacilitatorName"])) {
                authErrorMsg("Please fill up all the required fields.");
              } else {
                $editFacilitatorName = filterInput($_POST["editFacilitatorName"]);
                $facilitatorNewDetails["facilitatorName"] = $_POST["editFacilitatorName"];
              }

              //Array - Facilitator Email
              if (empty($_POST["editFacilitatorEmail"])) {
                authErrorMsg("Please fill up all the required fields.");
              } else {
                $editFacilitatorEmail = filterInput($_POST["editFacilitatorEmail"]);
                if (!isValidEmail($editFacilitatorEmail)) {
                  authErrorMsg("Please input a valid email address.");
                } else {
                  $facilitatorNewDetails["facilitatorEmail"] = $_POST["editFacilitatorEmail"];
                }
              }

              //Array - Facilitator Phone
              if (!empty($_POST["editFacilitatorPhone"])) {
                $editFacilitatorPhone = filterInput($_POST["editFacilitatorPhone"]);
                $facilitatorNewDetails["facilitatorPhone"] = $_POST["editFacilitatorPhone"];
              }

              //Array - Facilitator Designation
              if (!empty($_POST["editFacilitatorDesignation"])) {
                $facilitatorNewDetails["facilitatorDesignation"] = $_POST["editFacilitatorDesignation"];
              }

              //Array - Facilitator Gender
              if (!empty($_POST["editFacilitatorGender"])) {
                $facilitatorNewDetails["facilitatorGender"] = $_POST["editFacilitatorGender"];
              }

              $shouldUnlinkOldImage = false;

              //Array - Facilitator Picture
              if (isset($_FILES['editFacilitatorProfileImage']) && $_FILES['editFacilitatorProfileImage']['error'] === UPLOAD_ERR_OK) {
                $editFacilitatorProfilePicture = uploadFile("assets/images/profile/", "editFacilitatorProfileImage");
                $facilitatorNewDetails["facilitatorProfileImage"] = $editFacilitatorProfilePicture['file'];

                // Check if an old image exists, and if it's not empty
                if (!empty($facilitatorDBPicture)) {
                  $shouldUnlinkOldImage = true;
                }
              }

              //Array - Facilitator Password
              if (!empty($_POST["editFacilitatorPassword"])) {
                $editFacilitatorPassword = filterInput($_POST["editFacilitatorPassword"]);
                if (!isValidPassword($editFacilitatorPassword)) {
                  authErrorMsg("Password requirements not met. Please input 8 characters with 1 uppercase, 1 number and 1 special char.");
                } else {
                  $facilitatorNewDetails["facilitatorPassword"] = password_hash($_POST["editFacilitatorPassword"], PASSWORD_DEFAULT);
                  DB::startTransaction();
                  DB::update(
                    'facilitator',
                    $facilitatorNewDetails,
                    "facilitatorID=%i",
                    $facilitatorDBID
                  );

                  //Upon successful submission
                  $success = DB::affectedRows();
                  if ($success) {
                    DB::commit();

                    // Unlink the old image here after successful DB update
                    if ($shouldUnlinkOldImage && !empty($facilitatorDBPicture)) {
                      unlink("assets/images/profile/" . $facilitatorDBPicture);
                    }

                    sweetAlertTimerRedirect('Update Profile', 'Facilitator profile successfully updated!', 'success', (SITE_URL . 'admin'));
                  } else {
                    DB::rollback();
                    sweetAlertTimerRedirect('Update Profile', 'Something went wrong, please try again', 'error', (SITE_URL . 'admin'));
                  }
                }
              } else {
                DB::startTransaction();
                DB::update(
                  'facilitator',
                  $facilitatorNewDetails,
                  "facilitatorID=%i",
                  $facilitatorDBID
                );

                //Upon successful submission
                $success = DB::affectedRows();
                if ($success) {
                  DB::commit();

                  // Unlink the old image here after successful DB update
                  if ($shouldUnlinkOldImage && !empty($facilitatorDBPicture)) {
                    unlink("assets/images/profile/" . $facilitatorDBPicture);
                  }
                  
                  sweetAlertTimerRedirect('Update Profile', 'Facilitator profile successfully updated!', 'success', (SITE_URL . 'admin'));
                } else {
                  DB::rollback();
                  sweetAlertTimerRedirect('Update Profile', 'No changes recorded!', 'success', (SITE_URL . 'admin'));
                }
              }
            }
            ?>

            <!---------- Main Title ---------->
            <div class="p-3 py-5">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-right">Profile Settings</h4>
              </div>

              <!---------Form ---------->
              <form method="POST" enctype="multipart/form-data">
                <div class="row mt-3">
                  <div class="col-lg-12 col-md-12 col-sm-12 mt-10"><label class="labels">Name*</label><input type="text" name="editFacilitatorName" class="form-control" placeholder="first name" value="<?php echo $facilitatorDBName ?>"></div>

                  <div class="col-lg-12 col-md-12 col-sm-12 mt-30"><label class="labels">Password* (Leave it blank to not change password)</label> <input type="password" name="editFacilitatorPassword" class="form-control" value=""></div>

                  <div class="col-lg-12 col-md-12 col-sm-12 mt-30"><label class="labels">Email*</label><input type="email" name="editFacilitatorEmail" class="form-control" placeholder="Enter email" value="<?php echo $facilitatorDBEmail ?>"></div>

                  <div class="col-lg-12 col-md-12 col-sm-12 mt-30"><label class="labels">Profile Picture</label><input type="file" name="editFacilitatorProfileImage" id="editFacilitatorProfileImage" class="form-control"></div>

                  <div id="preview" class="col-lg-12 col-md-12 col-sm-12 text-center">
                    <div class="preview-border preview-profile"></div>
                  </div>

                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30"><label class="labels">Designation</label>
                    <br><select class="form-select form-select-option" name="editFacilitatorDesignation" aria-label="Default select example">
                      <option disabled>Select options below: </option>
                      <option <?php if ($facilitatorDBDesignation == 1) {
                                echo 'selected';
                              } ?> value="1">Facilitator</option>
                      <option <?php if ($facilitatorDBDesignation == 2) {
                                echo 'selected';
                              } ?> value="2">Head of Department</option>
                    </select>
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30"><label class="labels">Gender</label>
                    <br><select class="form-select form-select-option" name="editFacilitatorGender" aria-label="Default select example">
                      <option disabled>Select options below: </option>
                      <option <?php if ($facilitatorDBGender == 1) {
                                echo 'selected';
                              } ?> value="1">Male</option>
                      <option <?php if ($facilitatorDBGender == 2) {
                                echo 'selected';
                              } ?> value="2">Female</option>
                      <option <?php if ($facilitatorDBGender == 3) {
                                echo 'selected';
                              } ?> value="3">Rather not say</option>
                    </select>
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30"><label class="labels">Phone</label><input type="tel" name="editFacilitatorPhone" class="form-control" placeholder="Enter phone number" value="<?php echo $facilitatorDBPhone ?>"></div>
                </div>

                <!--------- Actionables ---------->
                <div class="d-flex align-items-center justify-content-end mt-40">
                  <a href='<?php echo SITE_URL . "admin" ?>' class="btn-tertiary link-grey">Cancel</a>
                  <button class="btn btn-primary profile-button ml-50" name="editFacilitator" type="submit">Update Profile</button>
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
    // Upload Image Preview
    $('.preview-profile').hide();

    $("#editFacilitatorProfileImage").change(function() {
      if (this.files && this.files[0]) {
        var fileReader = new FileReader();
        fileReader.onload = function(event) {
          $(".preview-profile").fadeIn().show();
          $(".preview-profile").html('<div class="my-auto d-flex justify-content-center" style="overflow:hidden; width:300px; height:300px;"><img src="' + event.target.result + '" height="300px" /></div>');
          $(".preview-profile").addClass("d-flex");
          $(".preview-profile").addClass("justify-content-center");
        };
        fileReader.readAsDataURL(this.files[0]);
      }
    });
  </script>

</body>

</html>