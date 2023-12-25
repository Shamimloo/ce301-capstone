<?php
//Define page name
$pageName = "Profile";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';

//Create an array of details
$teacherNewDetails = array(
  // 'teacherName' => $teacherDBName,
  // 'teacherEmail',
  // 'teacherPassword',
  // 'teacherDepartment',
  // 'teacherDesignation',
  // 'teacherGender',
  // 'teacherPhone',
  // 'teacherProfileImage'
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
                  <?php if (!$teacherDBPicture) {
                    echo '<p>' . makeInitials($teacherDBName) . '</p>';
                  } else {
                    echo '<img src="assets/images/profile/' . $teacherDBPicture . '">';
                  } ?>
                </div>
              </div>
              <h6 class="pt-3 mb-0"><?php echo $teacherDBName ?></h6>
              <span class="text-black-50 pt-2"><?php echo $teacherDBEmail ?></span><span> </span>
            </div>
          </div>
          <div class="col-lg-8 col-md-12 col-sm-12 mb-0">
            <?php
            if (isset($_POST["editTeacher"])) {

              //Array - Teacher Name
              if (empty($_POST["editTeacherName"])) {
                authErrorMsg("Please fill up all the required fields.");
              } else {
                $editTeacherName = filterInput($_POST["editTeacherName"]);
                $teacherNewDetails["teacherName"] = $_POST["editTeacherName"];
              }

              //Array - Teacher Email
              if (empty($_POST["editTeacherEmail"])) {
                authErrorMsg("Please fill up all the required fields.");
              } else {
                $editTeacherEmail = filterInput($_POST["editTeacherEmail"]);
                if (!isValidEmail($editTeacherEmail)) {
                  authErrorMsg("Please input a valid email address.");
                } else {
                  $teacherNewDetails["teacherEmail"] = $_POST["editTeacherEmail"];
                }
              }

              //Array - Teacher Phone
              if (!empty($_POST["editTeacherPhone"])) {
                $editTeacherPhone = filterInput($_POST["editTeacherPhone"]);
                $teacherNewDetails["teacherPhone"] = $_POST["editTeacherPhone"];
              }

              //Array - Teacher Department
              if (!empty($_POST["editTeacherDepartment"])) {
                $teacherNewDetails["teacherDepartment"] = $_POST["editTeacherDepartment"];
              }

              //Array - Teacher Designation
              if (!empty($_POST["editTeacherDesignation"])) {
                $teacherNewDetails["teacherDesignation"] = $_POST["editTeacherDesignation"];
              }

              //Array - Teacher Gender
              if (!empty($_POST["editTeacherGender"])) {
                $teacherNewDetails["teacherGender"] = $_POST["editTeacherGender"];
              }

              $shouldUnlinkOldImage = false;

              //Array - Teacher Picture
              if (isset($_FILES['editTeacherProfileImage']) && $_FILES['editTeacherProfileImage']['error'] === UPLOAD_ERR_OK) {
                $editTeacherProfilePicture = uploadFile("assets/images/profile/", "editTeacherProfileImage");
                $teacherNewDetails["teacherProfileImage"] = $editTeacherProfilePicture['file'];

                // Check if an old image exists, and if it's not empty
                if (!empty($teacherDBPicture)) {
                  $shouldUnlinkOldImage = true;
                }
              }

              //Array - Teacher Password
              if (!empty($_POST["editTeacherPassword"]) || !empty($_POST["editTeacherPhone"])) {
                $editTeacherPassword = filterInput($_POST["editTeacherPassword"]);
                if (!isValidPassword($editTeacherPassword)) {
                  authErrorMsg("Password requirements not met. Please input 8 characters with 1 uppercase, 1 number and 1 special char.");
                } else {
                  $teacherNewDetails["teacherPassword"] = password_hash($_POST["editTeacherPassword"], PASSWORD_DEFAULT);
                  DB::startTransaction();
                  DB::update(
                    'teacher',
                    $teacherNewDetails,
                    "teacherID=%i",
                    $teacherDBID
                  );

                  //Upon successful submission
                  $success = DB::affectedRows();
                  if ($success) {
                    DB::commit();

                    // Unlink the old image here after successful DB update
                    if ($shouldUnlinkOldImage && !empty($teacherDBPicture)) {
                      unlink("assets/images/profile/" . $teacherDBPicture);
                    }

                    sweetAlertTimerRedirect('Update Profile', 'Teacher profile successfully updated!', 'success', (SITE_URL . 'admin'));
                  } else {
                    DB::rollback();
                    sweetAlertTimerRedirect('Update Profile', 'Something went wrong, please try again', 'error', (SITE_URL . 'admin'));
                  }
                }
              } else {
                DB::startTransaction();
                DB::update(
                  'teacher',
                  $teacherNewDetails,
                  "teacherID=%i",
                  $teacherDBID
                );

                //Upon successful submission
                $success = DB::affectedRows();
                if ($success) {
                  DB::commit();

                  // Unlink the old image here after successful DB update
                  if ($shouldUnlinkOldImage && !empty($teacherDBPicture)) {
                    unlink("assets/images/profile/" . $teacherDBPicture);
                  }
                  
                  sweetAlertTimerRedirect('Update Profile', 'Teacher profile successfully updated!', 'success', (SITE_URL . 'admin'));
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
                  <div class="col-lg-12 col-md-12 col-sm-12 mt-10"><label class="labels">Name*</label><input type="text" name="editTeacherName" class="form-control" placeholder="first name" value="<?php echo $teacherDBName ?>"></div>

                  <div class="col-lg-12 col-md-12 col-sm-12 mt-30"><label class="labels">Password* (Leave it blank to not change password)</label> <input type="password" name="editTeacherPassword" class="form-control" value=""></div>

                  <div class="col-lg-12 col-md-12 col-sm-12 mt-30"><label class="labels">Email*</label><input type="email" name="editTeacherEmail" class="form-control" placeholder="Enter email" value="<?php echo $teacherDBEmail ?>"></div>

                  <div class="col-lg-12 col-md-12 col-sm-12 mt-30"><label class="labels">Profile Picture</label><input type="file" name="editTeacherProfileImage" id="editTeacherProfileImage" class="form-control"></div>

                  <div id="preview" class="col-lg-12 col-md-12 col-sm-12 text-center">
                    <div class="preview-border preview-profile"></div>
                  </div>

                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30"><label class="labels">Department</label>
                    <br>
                    <select class="form-select form-select-option" name="editTeacherDepartment" aria-label="Default select example">
                      <option disabled>Select options below: </option>
                      <option <?php if ($teacherDBDepartment == 1) {
                                echo 'selected';
                              } ?> value="1">English</option>
                      <option <?php if ($teacherDBDepartment == 2) {
                                echo 'selected';
                              } ?> value="2">Mathematics</option>
                      <option <?php if ($teacherDBDepartment == 3) {
                                echo 'selected';
                              } ?> value="3">Science</option>
                    </select>
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30"><label class="labels">Designation</label>
                    <br><select class="form-select form-select-option" name="editTeacherDesignation" aria-label="Default select example">
                      <option disabled>Select options below: </option>
                      <option <?php if ($teacherDBDesignation == 1) {
                                echo 'selected';
                              } ?> value="1">Teacher</option>
                      <option <?php if ($teacherDBDesignation == 2) {
                                echo 'selected';
                              } ?> value="2">Head of Department</option>
                    </select>
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30"><label class="labels">Gender</label>
                    <br><select class="form-select form-select-option" name="editTeacherGender" aria-label="Default select example">
                      <option disabled>Select options below: </option>
                      <option <?php if ($teacherDBGender == 1) {
                                echo 'selected';
                              } ?> value="1">Male</option>
                      <option <?php if ($teacherDBGender == 2) {
                                echo 'selected';
                              } ?> value="2">Female</option>
                      <option <?php if ($teacherDBGender == 3) {
                                echo 'selected';
                              } ?> value="3">Rather not say</option>
                    </select>
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30"><label class="labels">Phone</label><input type="tel" name="editTeacherPhone" class="form-control" placeholder="Enter phone number" value="<?php echo $teacherDBPhone ?>"></div>
                </div>

                <!--------- Actionables ---------->
                <div class="d-flex align-items-center justify-content-end mt-40">
                  <a href='<?php echo SITE_URL . "admin" ?>' class="btn-tertiary link-grey">Cancel</a>
                  <button class="btn btn-primary profile-button ml-50" name="editTeacher" type="submit">Update Profile</button>
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

    $("#editTeacherProfileImage").change(function() {
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