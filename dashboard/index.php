<?php
if (isset($_SESSION['cookie']) && $_SESSION["cookie"] == 1) {
  // Set cookies for facilitator, including companyID
  loginFacilitatorCookies($_SESSION["facilitatorName"], $_SESSION["facilitatorEmail"], $_SESSION["facilitatorPermission"], $_SESSION["facilitatorDesignation"], $_SESSION["companyID"]);
}


//Define page name
$pageName = "Dashboard";
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
      <div class="col-12 box user-pro-list my-5">
        <div class="row">
          <div class="col-lg-5 col-md-5 col-sm-12 my-auto text-center">
            <div class="avatar fs-2">
              <div class="profile-img">
                <!-- Profile Picture -->
                <?php if (!$facilitatorDBPicture) {
                  echo '<p>' . makeInitials($facilitatorDBName) . '</p>';
                } else {
                  echo '<img src="assets/images/profile/' . $facilitatorDBPicture . '">';
                } ?>
              </div>
            </div>
            <div class="pro-user mt-20">
              <h5 class="pro-user-username text-dark pt-1"><?php echo ucfirst($facilitatorDBName) ?></h5>
              <p class="pro-user-desc text-muted fs-10"><?php echo $facilitatorDBEmail ?></p>
            </div>
            <div class="mt-5 text-center"><a href="<?php echo SITE_URL . "profile-edit" ?>" class="btn-tertiary">Edit Profile</a></div>
          </div>
          <!-- Information Details -->
          <div class="profile-details col-lg-6 col-md-6 col-sm-12 my-5">
            <h5 class="pb-3">Information Details</h5>
            <div class="row pt-30">
              <div class="col-lg-6 col-md-6 col-sm-5 profile-details-title">Employee ID</div>
              <div class="col-lg-6 col-md-6 col-sm-7 text-end">
                <?php if (!$facilitatorDBID) {
                  echo '<span class="badge badge-warning">' . "Not Available" . '</span>';
                } else {
                  echo '<span class="">' . "#" . str_pad($facilitatorDBID, 4, '0', STR_PAD_LEFT) . '</span>';
                } ?>
              </div>
            </div>
            <div class="line"></div>
            <div class="row pt-30">
              <div class="col-lg-6 col-md-6 col-sm-5 profile-details-title">Designation</div>
              <div class="col-lg-6 col-md-6 col-sm-7 text-end">
                <?php if (!$facilitatorDBDesignation) {
                  echo '<span class="badge badge-warning">' . "Not Available" . '</span>';
                } elseif ($facilitatorDBDesignation == 1) {
                  echo '<span class="badge badge-primary-light">' . "Facilitator" . '</span>';
                } elseif ($facilitatorDBDesignation == 2) {
                  echo '<span class="badge badge-warning">' . "Head of Department" . '</span>';
                } ?>
              </div>
            </div>
            <div class="line"></div>
            <div class="row pt-30">
              <div class="col-lg-6 col-md-6 col-sm-5 profile-details-title">Date Joined</div>
              <div class="col-lg-6 col-md-6 col-sm-7 text-end"><?php echo mySQLDate($facilitatorDBDateCreated) ?></div>
            </div>
            <div class="line"></div>
            <div class="row pt-30">
              <div class="col-lg-6 col-md-6 col-sm-5 profile-details-title">Phone Number</div>
              <div class="col-lg-6 col-md-6 col-sm-7 text-end">
                <?php if (!$facilitatorDBPhone) {
                  echo '<span class="badge badge-warning">' . "Not Available" . '</span>';
                } else {
                  echo '<span class="">' . $facilitatorDBPhone . '</span>';
                } ?>
              </div>
            </div>
            <div class="line"></div>
            <div class="row pt-30">
              <div class="col-lg-6 col-md-6 col-sm-5 profile-details-title">Status</div>
              <div class="col-lg-6 col-md-6 col-sm-7 text-end">
                <?php if ($facilitatorDBStatus == 0) {
                  echo '<span class="badge badge-danger">' . "Inactive" . '</span>';
                } else {
                  echo '<span class="badge badge-success">' . "Active" . '</span>';
                } ?>
              </div>
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