<!---------- Main Header ---------->
<div class="main-header">
  <div class="d-flex">
    <div class="mobile-toggle" id="mobile-toggle">
      <i class='bx bx-menu'></i>
    </div>
    <div class="main-title">
      <?php echo ucwords($pageName); ?>
    </div>
  </div>

  <div class="d-flex align-items-center">
    <div class="dropdown d-inline-block">
      <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" onclick="location.replace('?site=admin');">
        <div class="avatar header-profile-container fs-2">
          <div class="mini-profile-img">
            <?php if (!$facilitatorDBPicture) {
              echo '<p>' . makeInitials($facilitatorDBName) . '</p>';
            } else {
              echo '<img src="assets/images/profile/' . $facilitatorDBPicture . '">';
            } ?>
          </div>
        </div>
        <span class="info d-xl-inline-block">
          <span class="d-block fs-20 font-w600 text-dark"><?php echo ucfirst($facilitatorDBName) ?></span>
          <span class="d-block color-span"><?php echo $facilitatorDBEmail; ?></span>
        </span>
      </button>
    </div>
    <div class="ml-30 mr-10">
      <a href="<?php echo SITE_URL . "logout" ?>"><i class="text-dark fa-solid fa-arrow-right-from-bracket fs-5"></i></a>
    </div>
  </div>
</div>