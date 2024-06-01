<?php
// Define page name
$pageName = "Learner";
// Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';

// Initialize variables for loading
$addLearnerName = $addLearnerStatus = $addGroupID = '';
?>

<?php
// Check if the "addLearner" POST request is set
if (isset($_POST["addLearner"])) {
  // Sanitize and retrieve input data
  $addLearnerName = filterInput($_POST["addLearnerName"]);
  $addLearnerStatus = isset($_POST["addLearnerStatus"]) ? $_POST["addLearnerStatus"] : null;
  $addGroupID = isset($_POST["addGroupID"]) ? $_POST["addGroupID"] : null;

  // Validate required fields
  if (empty($addLearnerName) || empty($addGroupID)) {
    authErrorMsg("Please fill up all the required fields.");
  } else {
    // Insert the learner into the database
    DB::startTransaction();
    DB::insert('learner', [
      'learnerName' => $addLearnerName,
      'learnerStatus' => $addLearnerStatus,
      'groupID' => $addGroupID,
      'learnerDateCreated' => date("Y-m-d H:i:s"),
      'learnerDateUpdated' => date("Y-m-d H:i:s"),
    ]);

    // Check if the insertion was successful
    $success = DB::affectedRows();
    if ($success) {
      DB::commit();
      sweetAlertTimerRedirect('Add Learner', 'Learner successfully added!', 'success', (SITE_URL . "learner-summary"));
    } else {
      DB::rollback();
      sweetAlertTimerRedirect('Add Learner', 'No changes were recorded.', 'error', (SITE_URL . "learner-summary"));
    }
  }
}
?>

<body class="sidebar-expand">
  <!-- Sidebar / Navbar Include -->
  <?php
  include 'assets/templates/dashboard/sidebar.php';
  include 'assets/templates/dashboard/navbar.php';
  ?>

  <!-- Main Content -->
  <div class="overlay"></div>
  <div class="main">
    <div class="main-content dashboard">
      <div class="col-12 mt-5">
        <div class="row">
          <div class="col-12 box my-5">
            <?php
            // ISSET POST bulk import form - Bulk import
            if (isset($_POST["importFile"])) { //import file button pressed

              // ISSET POST - Class Add DropDown field
              $addClassID = isset($_POST["addGroupID"]) ? $_POST["addGroupID"] : null;

              // Commence opening of file
              $filename = $_FILES["importLearnerList"]["tmp_name"];
              $file = fopen($filename, "r"); // file open the $filename 

              // Declare state of errors spotted & initialize count
              rewind($file); // Reset file pointer to beginning of file                                    
              $count = 0;
              $errorspotted = 0;
              $validEntries = []; // This will store validated entries
              $firstColumnEntries = [];

              // Check through csv file data without inserting into the database
              while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
                // Skip first row
                $count++;
                if ($count == 1) {
                  continue;
                }

                // Check if specific columns are empty
                if (empty($getData[0])) {
                  break;
                }

                // Check for duplicate entries in the first column
                $convertCol1 = (int)$getData[0];
                if (in_array($convertCol1, $firstColumnEntries)) {
                  $errorspotted = 6; // A new error code for duplicate entries in the CSV.
                  break;
                } else {
                  $firstColumnEntries[] = $convertCol1;
                }

                // If no errors for this row, store in valid entries
                $validEntries[] = [
                  'learnerName' => $getData[0],
                  'groupID' => $addClassID,
                ];
              }

              // Check if errors spotted first
              if ($errorspotted == 6) {
                authErrorMsg("Duplicate entries found in the file.");
              } else {
                // If all rows are valid, perform a bulk insert
                DB::startTransaction();

                // Bulk insert all valid entries
                DB::insert('learner', $validEntries);

                $success = DB::affectedRows();
                if ($success) {
                  DB::commit();
                  sweetAlertTimerRedirect('File Uploaded', 'File successfully uploaded!', 'success', (SITE_URL . "learner-summary"));
                } else {
                  DB::rollback();
                  authErrorMsg("Invalid File: Please upload CSV File.");
                }
              }

              fclose($file); // close the file that is opened
            }
            ?>
            <div class="p-3 py-5">
              <!-- Main Title -->
              <div>
                <h4 class="text-right">Import File</h4>
                <p>Download the template <a href="https://drive.google.com/uc?export=download&id=1M2X2O8tBvLssayLaxpvEgtDKEon0mwfy" class="btn-tertiary" download>here</a>. <br />Please fill in the <b>Learner Names</b> column in the template. </p>
              </div>

              <!-- Upload File Form -->
              <form method="post" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-20">
                    <input type="file" name="importLearnerList" id="uploadLearnerList" class="form-control">
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-20">
                    <select class="form-select form-select-option" name="addGroupID">
                      <option selected="true" disabled="disabled">Select Group</option>
                      <?php
                      $queryDBGroup = DB::query("SELECT * FROM `learnerGroup`");
                      foreach ($queryDBGroup as $queryDBGroupResults) {
                        $queryDBGroupID = $queryDBGroupResults["groupID"];
                        $queryDBGroupName = $queryDBGroupResults["groupName"];
                      ?>
                        <option value="<?php echo $queryDBGroupID; ?>"><?php echo $queryDBGroupName; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <!-- Actionables -->
                <div class="d-flex justify-content-end align-items-center mt-30">
                  <button class="btn btn-primary profile-button" name="importFile" type="submit">Upload</button>
                </div>
              </form>

              <!-- Main Title -->
              <div class="d-flex justify-content-between align-items-center mb-30">
                <h4 class="text-right">Add New Learner</h4>
              </div>

              <!-- Form for Adding Individual Learner -->
              <form method="POST">
                <div class="mt-10">
                  <label for="addLearnerName" class="labels">Name*</label>
                  <input type="text" name="addLearnerName" id="addLearnerName" class="form-control" placeholder="Enter learner name">
                </div>
                <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-12 mt-30">
                    <label class="labels">Status*</label>
                    <select class="form-select form-select-option" name="addLearnerStatus" aria-label="Default select example">
                      <option value="1">Inactive</option>
                      <option value="2">Active</option>
                    </select>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 mt-30">
                    <label for="addGroupID" class="labels">Group*</label>
                    <select class="form-select form-select-option" name="addGroupID" id="addGroupID">
                      <option selected="true" disabled="disabled">Select Group</option>
                      <?php
                      $queryDBGroup = DB::query("SELECT * FROM `learnerGroup`");
                      foreach ($queryDBGroup as $queryDBGroupResults) {
                        $queryDBGroupID = $queryDBGroupResults["groupID"];
                        $queryDBGroupName = $queryDBGroupResults["groupName"];
                      ?>
                        <option value="<?php echo $queryDBGroupID; ?>"><?php echo $queryDBGroupName; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <!-- Actionables -->
                <div class="d-flex align-items-center justify-content-end mt-40">
                  <a href="<?php echo SITE_URL . "learnergroup-summary" ?>" class="btn-tertiary link-grey">Cancel</a>
                  <button class="btn btn-primary profile-button ml-50" name="addLearner" type="submit">Add Learner</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>

  <!-- End of Main Content -->

  <!-- Include Footer -->
  <?php
  include 'assets/templates/dashboard/footer.php';
  ?>

  <!-- Custom Script -->
  <script src="assets/js/app.js"></script>

</body>

</html>