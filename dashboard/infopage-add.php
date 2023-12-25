<?php
//Define page name
$pageName = "Info Page";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';

//Initialize variables for loading
$addPageName = $addPageImage = $addCategoryID = $addPageDescription = $addPageStatus = '';

// Retrieve companyID from cookie or session
$companyID = isset($_SESSION['companyID']) ? $_SESSION['companyID'] : $_COOKIE['companyID'];
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

            //ISSET POST form - Add Page
            if (isset($_POST["addPage"])) {

              //ISSET POST - Add Name Text Field
              $addPageName = filterInput($_POST["addPageName"]);

              //ISSET POST - Add Text Area field
              if (isset($_POST['addPageDescription']) && ($_POST['addPageDescription'] != "")) {
                //Text area have text
                $addPageDescription = $_POST['addPageDescription'];
              } else {
                //Text area no text
                $addPageDescription = null;
              }

              //ISSET POST - Image Upload field
              if (!empty($_FILES['addPageImage']) && $_FILES['addPageImage']['error'] === UPLOAD_ERR_OK) {
                $addPageImage = uploadFile("assets/images/infopage/", "addPageImage");
              } else {
                $addPageImage = null;
              }

              //ISSET POST - Category Add DropDown field
              if (empty($_POST["addCategoryID"])) {
                $addCategoryID = null;
              } else {
                $addCategoryID = $_POST["addCategoryID"];
              }

              //ISSET POST -Status Add DropDown field
              if (empty($_POST["addPageStatus"])) {
                $addPageStatus = null;
              } else {
                $addPageStatus = $_POST["addPageStatus"];
              }

              //Check if required inputs are not empty
              if ($addPageName == "" ||  $addPageDescription == "" || $addPageImage == null) {
                authErrorMsg("Please fill up all required fields.");
              } else {

                //Publishing page
                if ($addPageStatus == 2) {
                  DB::startTransaction();
                  DB::insert('page', [
                    'pageName' => $addPageName,
                    'pageDescription' => $addPageDescription,
                    'pageImage' => $addPageImage['file'],
                    'pageStatus' => $addPageStatus,
                    'pageDatePublished' => date('Y-m-d H:i:s'),
                    'pageDateCreated' => date('Y-m-d H:i:s'),
                    'pageDateUpdated' => date('Y-m-d H:i:s'),
                    'categoryID' => $addCategoryID,
                  ]);

                  $lastPageID = DB::insertId();
                } else {
                  //Save to draft
                  DB::startTransaction();
                  DB::insert('page', [
                    'pageName' => $addPageName,
                    'pageDescription' => $addPageDescription,
                    'pageImage' => $addPageImage['file'],
                    'pageStatus' => $addPageStatus,
                    'categoryID' => $addCategoryID,
                    'pageDateCreated' => date('Y-m-d H:i:s'),
                    'pageDateUpdated' => date('Y-m-d H:i:s')
                  ]);

                  $lastPageID = DB::insertId();
                }

                //Successful Upload to DB
                $success = DB::affectedRows();
                if ($success) {
                  DB::commit();
                  sweetAlertTimerRedirect('Add Info Page', 'Info Pages successfully added!', 'success', (SITE_URL . "infopage-summary"));
                } else {
                  DB::rollback();
                  sweetAlertTimerRedirect('Add Info Page', 'No changes recorded!', 'error', (SITE_URL . "infopage-add"));
                }
              }
            }
            ?>

            <!---------- Main Title ---------->
            <div class="p-3 py-5">
              <div class="d-flex justify-content-between align-items-center mb-30">
                <h3 class="text-right">Add New</h3>
              </div>

              <!---------Form ---------->
              <form method="POST" enctype="multipart/form-data">
                <div class="mt-10">
                  <label for="addPageName" class="labels">Name*</label>
                  <input type="text" name="addPageName" id="addPageName" class="form-control" placeholder="Enter page name" value="<?php echo $addPageName ?>">
                </div>
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 mt-30">
                    <label for="addPageImage" class="labels">Image*</label>
                    <input type="file" name="addPageImage" id="addPageImage" class="form-control" placeholder="Select file" value="<?php echo $addPageImage ?>">
                  </div>
                  <div id="preview" class="col-lg-12 col-md-12 col-sm-12 text-center">
                    <div class="preview-border preview-add-page-image"></div>
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                    <label for="addCategoryID" class="labels">Category*</label>
                    <br>
                    <select class="form-select form-select-option" name="addCategoryID" id="addCategoryID" onchange="getClass(this.value);">
                      <option selected="true" disabled="disabled">Select Category </option>
                      <?php
                      // Query categories based on companyID
                      $queryDBCategory = DB::query("SELECT * FROM `category` WHERE categoryStatus = %i AND companyID = %i", 2, $companyID);
                      foreach ($queryDBCategory as $queryDBCategoryResults) {
                        $queryDBCategoryID = $queryDBCategoryResults["categoryID"];
                        $queryDBCategoryName = $queryDBCategoryResults["categoryName"];
                      ?>
                        <option value="<?php echo $queryDBCategoryID; ?>" <?php if ($queryDBCategoryID == $addCategoryID) {
                                                                            echo 'selected';
                                                                          } ?>><?php echo $queryDBCategoryName; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30"><label for="addPageStatus" class="labels">Status*</label>
                    <br><select class="form-select form-select-option" name="addPageStatus" aria-label="Default select example">
                      <option disabled>Actions: </option>
                      <option <?php if ($addPageStatus == 2) {
                                echo 'selected';
                              } ?> value="2">Publish Now</option>
                      <option <?php if ($addPageStatus == 1) {
                                echo 'selected';
                              } ?> value="1">Save to draft</option>
                    </select>
                  </div>
                </div>
                <div class="mt-30">
                  <label for="addPageDescription" class="labels">Description*</label>
                  <textarea name="addPageDescription" class="form-control" id="addPageDescription" rows="10" cols="80"></textarea>
                </div>
                <div class="row">


                </div>

                <!--------- Actionables ---------->
                <div class="d-flex align-items-center justify-content-end mt-40">
                  <a href="<?php echo SITE_URL . "infopage-summary" ?>" class="btn-tertiary link-grey">Cancel</a>
                  <button class="btn btn-primary profile-button ml-50" id="addPage" name="addPage" type="submit">Add Info</button>
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
    CKEDITOR.replace('addPageDescription');

    // Upload Image Preview
    $('.preview-add-page-image').hide();
    $("#addPageImage").change(function() {
      imagePreview(this, '.preview-add-page-image');
    });
  </script>


</body>

</html>