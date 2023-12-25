<?php
//Define page name
$pageName = "Student";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';

//Initialize variables for loading
$addStudentName = $addStudentIndex = $addStudentYear = $addStudentStatus = $addClassID = $addHouseID = '';
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
            <?php
            //ISSET POST bulk import form - Bulk import
            if (isset($_POST["importFile"])) { //import file button pressed

              //ISSET POST - Class Add DropDown field
              if (empty($_POST["addClassID"])) {
                $addClassID = null;
              } else {
                $addClassID = $_POST["addClassID"];
              }

              //ISSET POST - Class Add DropDown field
              if (empty($_POST["addHouseID"])) {
                $addHouseID = null;
              } else {
                $addHouseID = $_POST["addHouseID"];
              }

              if ($addClassID == "" || $addHouseID == "" || $_FILES["importStudentList"]["size"] == 0) { //Check for empty input fields
                authErrorMsg("Please fill up all the required fields.");
              } else {

                //Commence opening of file
                $filename = $_FILES["importStudentList"]["tmp_name"];
                $file = fopen($filename, "r"); //file open the $filename 

                //Count file rows
                $fileRows = file("$filename");
                $countFileRows = count($fileRows);

                //Check database for class capacity of selected class
                $queryClassCapacity = DB::query("SELECT `classCapacity` FROM `class` WHERE classID=%i", $addClassID);
                foreach ($queryClassCapacity as $result) {
                  $resultClassCapacity = $result["classCapacity"];
                }

                // //Check database for number of students in selected class
                // $queryNumOfStudents = DB::query("SELECT * FROM `class` WHERE classID=%i", $addClassID);
                // $countNumOfStudents = count($queryNumOfStudents);

                if ($countFileRows > $resultClassCapacity) { //Check if class capacity has reached max
                  authErrorMsg("You have reached the maximum number of students in the class.");
                } else {
                  echo "YAY";
                }

                // //Delete all rows that are tied to that class ID
                // DB::startTransaction();
                // DB::delete('student', "classID=%i", $addClassID);
                // DB::commit();

                // //Declare state of errors spotted & initialize count
                // rewind($file); // Reset file pointer to beginning of file                                    
                // $count = 0;
                // $errorspotted = false;

                // //Check through csv file data without inserting into database
                // while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) //fgetcsv(file, length, separator, enclosure)
                // {
                //     //skip first row
                //     $count++;
                //     if ($count == 1) {
                //         continue;
                //     }

                //     //covert columns to be exactly int
                //     $convertCol1 = (int)$getData[0];
                //     $convertCol2 = (int)$getData[1];

                //     if ($convertCol1 == false || $convertCol2 == false) {
                //         $errorspotted = true;
                //         break;
                //     }
                // }

                // //Check if errors spotted first
                // if ($errorspotted == true) {
                //     authErrorMsg("Errors spotted in the file. Please amend to follow the template file given!");
                // } else {

                //     rewind($file); // Reset file pointer to beginning of file                                    
                //     $count = 0;
                //     // If all data is valid, commence inserting to database
                //     while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) //fgetcsv(file, length, separator, enclosure)
                //     {
                //         //skip first row
                //         $count++;
                //         if ($count == 1) {
                //             continue;
                //         }

                //         //Insert into DB --> Ensure the columns are in sync with those in the database
                //         DB::startTransaction();
                //         DB::insert('student', [
                //             'studentIndex' => $getData[0],
                //             'studentYear' => $getData[1],
                //             'studentName' => $getData[2],
                //             'classID' => $addClassID,
                //             'houseID' => $addHouseID
                //         ]);

                //         $success = DB::affectedRows();
                //         if ($success) {
                //             DB::commit();
                //             sweetAlertTimerRedirect('File Uploaded', 'File successfully uploaded!', 'success', (SITE_URL . "student-summary"));
                //         } else {
                //             DB::rollback();
                //             authErrorMsg("Invalid File: Please upload CSV File.");
                //         }
                //     }
                // }

                fclose($file); //close the file that is opened          

              }
            }
            ?>
            <div class="p-3 py-5">
              <!---------- Main Title ---------->
              <div class="">
                <h4 class="text-right">Import File</h4>
                <p>Download the template <a href="https://drive.google.com/uc?export=download&id=1jFtZvhn_FNh2SsTnazNU72nIGzId0NAI" class="btn-tertiary" download>here</a>.</p>
              </div>

              <!--------- Upload File Form ---------->
              <form method="post" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-lg-12 col-md-12 col-sm-12 mt-20">
                    <input type="file" name="importStudentList" id="uploadStudentList" class="form-control" accept=".csv,.xls,.xlsx" value="<?php echo $uploadFile ?>">
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-20">
                    <label for="addClassID" class="labels">Class*</label><br>
                    <select class="form-select form-select-option" name="addClassID" id="addClassID" onchange="getClass(this.value);">
                      <option selected="true" disabled="disabled">Select Class</option>
                      <?php
                      $queryDBClass = DB::query("SELECT * FROM `class` WHERE classStatus=%i", 2);
                      //Populate all the possible active classes
                      foreach ($queryDBClass as $queryDBClassResults) {
                        $queryDBClassID = $queryDBClassResults["classID"];
                        $queryDBClassName = $queryDBClassResults["className"];
                      ?>
                        <option value="<?php echo $queryDBClassID; ?>" <?php if ($queryDBClassID == $addClassID) {
                                                                          echo 'selected';
                                                                        } ?>><?php echo $queryDBClassName; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-20">
                    <label for="addHouseID" class="labels">House*</label><br>
                    <select class="form-select form-select-option" name="addHouseID" id="addHouseID" onchange="getClass(this.value);">
                      <option selected="true" disabled="disabled">Select House </option>
                      <?php
                      $queryDBHouse = DB::query("SELECT * FROM `house` WHERE houseStatus=%i", 2);
                      //Populate all the possible active houses
                      foreach ($queryDBHouse as $queryDBHouseResults) {
                        $queryDBHouseID = $queryDBHouseResults["houseID"];
                        $queryDBHouseName = $queryDBHouseResults["houseName"];
                      ?>
                        <option value="<?php echo $queryDBHouseID; ?>" <?php if ($queryDBHouseID == $addHouseID) {
                                                                          echo 'selected';
                                                                        } ?>><?php echo $queryDBHouseName; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                  <!--------- Actionables ---------->
                  <div class="d-flex justify-content-end align-items-center mt-30">
                    <button class="btn btn-primary profile-button" name="importFile" type="submit">Upload</button>
                  </div>
                </div>
              </form>

              <div class="d-flex justify-content-between align-items-center">
                <div class="line w-45 mt-50 mb-20"></div>
                <h6 class="mt-50 mb-20 disabled-text">OR</h6>
                <div class="line w-45 mt-50 mb-20"></div>
              </div>
              <?php

              //ISSET POST form - Add Student
              if (isset($_POST["addStudent"])) {

                //ISSET POST - Filter Inputs
                $addStudentName = filterInput($_POST["addStudentName"]);

                //ISSET POST - Status Add DropDown field
                if (empty($_POST["addStudentStatus"])) {
                  $addStudentStatus = null;
                } else {
                  $addStudentStatus = $_POST["addStudentStatus"];
                }

                //ISSET POST - Status Add DropDown field
                if (empty($_POST["addStudentYear"])) {
                  $addStudentYear = null;
                } else {
                  $addStudentYear = $_POST["addStudentYear"];
                }

                //ISSET POST - Class Add DropDown field
                if (empty($_POST["addClassID"])) {
                  $addClassID = null;
                } else {
                  $addClassID = $_POST["addClassID"];
                }

                //ISSET POST - House Add DropDown field
                if (empty($_POST["addHouseID"])) {
                  $addHouseID = null;
                } else {
                  $addHouseID = $_POST["addHouseID"];
                }

                //ISSET POST - House Add DropDown field
                if (empty($_POST["addStudentIndex"])) {
                  $addStudentIndex = null;
                } else {
                  $addStudentIndex = $_POST["addStudentIndex"];
                }

                //check if required inputs are not empty
                if ($addStudentName == "" ||  $addStudentIndex == "" || $addClassID == "" || $addHouseID == "") {
                  authErrorMsg("Please fill up all the required fields.");
                } else {
                  //Check if index number for that class is taken
                  $checkIndexNumber = DB::query("SELECT * FROM student WHERE (studentYear = '$addStudentYear') && (ClassID = '$addClassID') && (StudentIndex = '$addStudentIndex') && (studentStatus=%i)", 2);
                  $indexNumberTaken = DB::count();
                  if ($indexNumberTaken) {
                    sweetAlertReload('error', 'Add Student', 'Index Number Taken!', 2000);
                  } else {

                    //Check if student exists in DB
                    $checkStudentQuery = DB::query("SELECT * FROM student WHERE (studentYear = '$addStudentYear') && (ClassID = '$addClassID') && (StudentName = '$addStudentName') && (StudentIndex = '$addStudentIndex')");
                    $studentDBExist = DB::count();
                    if ($studentDBExist) {
                      sweetAlertTimerRedirect('Add Student', 'Student exist in system!', 'error', (SITE_URL . "student-summary"));
                    } else {

                      //Insert into DB
                      DB::startTransaction();
                      DB::insert('student', [
                        'studentName' => $addStudentName,
                        'studentIndex' => $addStudentIndex,
                        'studentYear' => $addStudentYear,
                        'studentStatus' => $addStudentStatus,
                        'classID' => $addClassID,
                        'houseID' => $addHouseID,
                      ]);

                      //Student successfully added
                      $success = DB::affectedRows();
                      if ($success) {
                        DB::commit();
                        sweetAlertTimerRedirect('Add Student', 'Student successfully added!', 'success', (SITE_URL . "student-summary"));
                      } else {
                        DB::rollback();
                        sweetAlertTimerRedirect('Add Student', 'No changes recorded!', 'success', (SITE_URL . "student-summary"));
                      }
                    }
                  }
                }
              }
              ?>

              <!---------- Main Title ---------->
              <div class="d-flex justify-content-between align-items-center mb-30">
                <h4 class="text-right">Add New</h4>
              </div>

              <!--------- Form ---------->
              <form method="POST">
                <div class="mt-10">
                  <label for="addStudentName" class="labels">Name*</label>
                  <input type="text" name="addStudentName" id="addStudentName" class="form-control" placeholder="Enter student name" value="<?php echo $addStudentName ?>">
                </div>
                <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-12 mt-30"><label class="labels">Status*</label>
                    <br><select class="form-select form-select-option" name="addStudentStatus" aria-label="Default select example">
                      <option disabled>Actions </option>
                      <option <?php if ($addStudentStatus == 2) {
                                echo 'selected';
                              } ?> value="2">Active</option>
                      <option <?php if ($addStudentStatus == 1) {
                                echo 'selected';
                              } ?> value="1">Inactive</option>
                    </select>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 mt-30">
                    <label for="addClassID" class="labels">Class*</label> <br>
                    <select class="form-select form-select-option" name="addClassID" id="addClassID" onchange="getClass(this.value);">
                      <option selected="true" disabled="disabled">Select Class</option>
                      <?php
                      $queryDBClass = DB::query("SELECT * FROM `class` WHERE classStatus=%i", 2);
                      //Populate all the possible active classes
                      foreach ($queryDBClass as $queryDBClassResults) {
                        $queryDBClassID = $queryDBClassResults["classID"];
                        $queryDBClassName = $queryDBClassResults["className"];
                      ?>
                        <option value="<?php echo $queryDBClassID; ?>" <?php if ($queryDBClassID == $addClassID) {
                                                                          echo 'selected';
                                                                        } ?>><?php echo $queryDBClassName; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 mt-30">
                    <label for="addHouseID" class="labels">House*</label><br>
                    <select class="form-select form-select-option" name="addHouseID" id="addHouseID" onchange="getClass(this.value);">
                      <option selected="true" disabled="disabled">Select House </option>
                      <?php
                      $queryDBHouse = DB::query("SELECT * FROM `house` WHERE houseStatus=%i", 2);
                      //Populate all the possible active houses
                      foreach ($queryDBHouse as $queryDBHouseResults) {
                        $queryDBHouseID = $queryDBHouseResults["houseID"];
                        $queryDBHouseName = $queryDBHouseResults["houseName"];
                      ?>
                        <option value="<?php echo $queryDBHouseID; ?>" <?php if ($queryDBHouseID == $addHouseID) {
                                                                          echo 'selected';
                                                                        } ?>><?php echo $queryDBHouseName; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                  <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                      <label for="addStudentIndex" class="labels">Index*</label>
                      <select id="student-index" class="form-select form-select-option" name="addStudentIndex" aria-label="Default select example">
                        <option selected="true" disabled="disabled">Select student index</option>
                        <?php
                        $classQuery = DB::query("SELECT classCapacity FROM class WHERE classID=%i", $addClassID);
                        foreach ($classQuery as $classResult) {
                          $classCapacity = $classResult["classCapacity"];
                        }
                        for ($i = 1; $i <= $classCapacity; $i++) {
                          $studentName = "";
                          $studentQuery = DB::query("SELECT * FROM student WHERE classID=%i and studentIndex=%i", $addClassID, $i);
                          foreach ($studentQuery as $studentResult) {
                            $studentName = $studentResult["studentName"];
                          }
                          if ($studentName != "") {
                        ?>
                            <option value="<?php echo $i ?>" disabled><?php echo $i ?> - <?php echo $studentName ?></option>
                          <?php
                          } else {
                          ?>
                            <option value="<?php echo $i ?>" <?php if ($i == $addStudentIndex) {
                                                                echo 'selected';
                                                              } ?>><?php echo $i ?></option>
                        <?php
                          }
                        }
                        ?>
                      </select>
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                      <label for="addStudentYear" class="labels">Year*</label>
                      <select name="addStudentYear" class="form-control form-select-option" id='addStudentYear' value="<?php echo $addStudentYear ?>"> </select>
                    </div>
                  </div>

                </div>

                <!--------- Actionables ---------->
                <div class="d-flex align-items-center justify-content-end mt-40">
                  <a href="<?php echo SITE_URL . "student-summary" ?>" class="btn-tertiary link-grey">Cancel</a>
                  <button class="btn btn-primary profile-button ml-50" name="addStudent" type="submit">Add Student</button>
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

  <script>
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
  </script>

</body>

</html>