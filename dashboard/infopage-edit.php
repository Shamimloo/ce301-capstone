<?php
//Define page name
$pageName = "Info Page";
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
            //Query the page from DB 
            $pageDBQuery = DB::query("SELECT `page`.* FROM `page` WHERE `page`.pageID=%i", $_GET["pageID"]);
            foreach ($pageDBQuery as $pageDBQueryResult) {
              $pageDBQueryID = $pageDBQueryResult["pageID"];
              $pageDBQueryName = $pageDBQueryResult["pageName"];
              $pageDBQueryImage = $pageDBQueryResult["pageImage"];
              $pageDBQueryDescription = $pageDBQueryResult["pageDescription"];
              $pageDBQueryStatus = $pageDBQueryResult["pageStatus"];
              $pageDBQueryCategory = $pageDBQueryResult["categoryID"];
            }

            //ISSET POST form - Edit Page
            if (isset($_POST["editPage"])) {

              //ISSET POST - Edit Name Text Field
              $editPageName = filterInput($_POST["editPageName"]);

              //ISSET POST - Edit Text Area field
              if (isset($_POST['editPageDescription']) && ($_POST['editPageDescription'] != "")) {
                //Text area have text
                $editPageDescription = $_POST['editPageDescription'];
              } else {
                //Text area no text
                $editPageDescription = null;
              }

              $shouldUnlinkOldImage = false;

              //ISSET POST - Image Upload field
              if (isset($_FILES['editPageImage']) && $_FILES['editPageImage']['error'] === UPLOAD_ERR_OK) {
                $editPageImage = uploadFile("assets/images/infopage/", "editPageImage");

                // Check if an old image exists, and if it's not empty
                if (!empty($pageDBQueryImage)) {
                  $shouldUnlinkOldImage = true;
                }
              } else {
                $editPageImage = null;
              }

              //ISSET POST - Edit Category Add DropDown field
              if (empty($_POST["editPageCategoryID"])) {
                $editPageCategoryID = null;
              } else {
                $editPageCategoryID = $_POST["editPageCategoryID"];
              }

              //check if required inputs are not empty
              if ($editPageName == "" ||  $editPageDescription == "" ) {
                authErrorMsg("Please fill up all required fields.");
              } else {

                //No image uploaded
                if ($editPageImage == null) {
                  DB::startTransaction();
                  DB::update('page', [
                    'pageName' => $editPageName,
                    'pageDescription' => $editPageDescription,
                    'pageDateUpdated' => date('Y-m-d H:i:s'),
                    'categoryID' => $editPageCategoryID,
                  ], "pageID=%i", $pageDBQueryID);
                } else {
                  //Image uploaded
                  DB::startTransaction();
                  DB::update('page', [
                    'pageName' => $editPageName,
                    'pageDescription' => $editPageDescription,
                    'pageImage' => $editPageImage['file'],
                    'categoryID' => $editPageCategoryID,
                    'pageDateUpdated' => date('Y-m-d H:i:s'),
                  ], "pageID=%i", $pageDBQueryID);
                  }
                }

                //Successful Upload to DB
                $success = DB::affectedRows();
                if ($success) {
                  DB::commit();

                  // Delete the old image file from the server
                  // if (file_exists("assets/images/infopage/" . $pageDBQueryImage) && $editPageImage == null) {
                  //   
                  // }

                  // Unlink the old image here after successful DB update
                  if ($shouldUnlinkOldImage && !empty($pageDBQueryImage)) {
                    unlink("assets/images/infopage/" . $pageDBQueryImage);
                  }

                  sweetAlertTimerRedirect('Edit Info Page', 'Info Pages successfully edited!', 'success', (SITE_URL . "infopage-summary"));
                } else {
                  DB::rollback();
                  sweetAlertTimerRedirect('Edit Info Page', 'Info Pages successfully edited!', 'success', (SITE_URL . "infopage-summary"));
                }
              }
            
            ?>
            <!---------- Main Title ---------->
            <div class="p-3 py-5">
              <div class="d-flex justify-content-between align-items-center mb-30">
                <h4 class="text-right">Edit</h4>
              </div>

              <!---------Form ---------->
              <form method="POST" enctype="multipart/form-data">
                <div class="row mt-3">
                  <div class="mt-10">
                    <label for="editPageName" class="labels">Name*</label>
                    <input type="text" name="editPageName" id="editPageName" class="form-control" placeholder="Enter page name" value="<?php echo $pageDBQueryName ?>">
                  </div>
                  <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 mt-30">
                      <label for="addPageImage" class="labels">Image*</label>
                      <input type="file" name="editPageImage" id="editPageImage" class="form-control" placeholder="Select file" value="<?php echo $addPageImage ?>">
                    </div>
                    <!-- Display image preview if image was uploaded -->
                    <?php if ($pageDBQueryImage != null) { ?>
                      <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                        <div class="preview-border preview-edit-page-image d-flex justify-content-center"><?php echo '<div class="my-auto" style="width:90%;"><img src="assets/images/infopage/' . $pageDBQueryImage . '" height="300px" width="300px" /></div>'; ?>
                        </div>
                      </div>
                    <?php } ?>
                    <div class="col-lg-12 col-md-12 col-sm-12 mt-30">
                      <label for="editPageCategoryID" class="labels">Category*</label>
                      <br>
                      <select class="form-select form-select-option" name="editPageCategoryID" id="editPageCategoryID">
                        <option disabled>Select Category: </option>
                        <?php
                        $queryDBCategory = DB::query("SELECT * FROM `category` WHERE categoryStatus=%i", 2);
                        foreach ($queryDBCategory as $queryDBCategoryResults) {
                          $queryDBCategoryID = $queryDBCategoryResults["categoryID"];
                          $queryDBCategoryName = $queryDBCategoryResults["categoryName"];
                        ?>
                          <option value="<?php echo $queryDBCategoryID; ?>" <?php if ($queryDBCategoryID == $pageDBQueryCategory) {
                                                                              echo 'selected';
                                                                            } ?>><?php echo $queryDBCategoryName; ?></option>
                        <?php
                        }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div class="mt-30">
                    <label for="editPageDescription" class="labels">Description*</label>
                    <textarea name="editPageDescription" class="form-control" id="editPageDescription" rows="10" cols="80"><?php echo $pageDBQueryDescription ?></textarea>
                  </div>

                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30"><label class="labels">Status*</label>
                    <br><select disabled class="form-select form-select-option" name="editPageStatus" aria-label="Default select example">
                      <option disabled>Actions: </option>
                      <option <?php if ($pageDBQueryStatus == 2) {
                                echo 'selected';
                              } ?> value="2">Publish Now</option>
                      <option <?php if ($pageDBQueryStatus == 1) {
                                echo 'selected';
                              } ?> value="1">Save to draft</option>
                    </select>
                  </div>
                </div>

                <!--------- Actionables ---------->
                <div class="d-flex align-items-center justify-content-end mt-40">
                  <a href="<?php echo SITE_URL . "infopage-summary" ?>" class="btn-tertiary link-grey">Cancel</a>
                  <button class="btn btn-primary profile-button ml-50" name="editPage" type="submit">Update Info</button>
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
    CKEDITOR.replace('editPageDescription');

    // Upload Image Preview
    $('.preview-edit-page-image').hide();
    $("#editPageImage").change(function() {
      imagePreview(this, '.preview-edit-page-image');
    });
  </script>


</body>

</html>