<?php
//Define page name
$pageName = "Questions";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';

//Initialize variables for loading
$editQuestionTime = $editCategoryID = $editCorrectAnswerTitle = $editCorrectAnswerDescription = $editWrongAnswerTitle = $editWrongAnswerDescription = "";
$shouldUnlinkOldQuestionImage = $shouldUnlinkOldCorrectAnswerImage =  $shouldUnlinkOldWrongAnswerImage = false;
//Create an array of details
$questionNewDetails = array();
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
          <div class="col-lg-12 col-md-12 col-sm-12 box my-5">
            <div class="p-3 py-5">
              <?php
              if (!isset($_GET["questionID"]) || $_GET["questionID"] == "") {
                jsRedirect(SITE_URL . 'admin');
              } else {
                $questionDBquery = DB::query("SELECT * FROM `question` WHERE questionID=%i", $_GET["questionID"]);

                foreach ($questionDBquery as $questionDBQueryResult) {
                  $questionDBqueryID = $questionDBQueryResult["questionID"];
                  $questionDBqueryTitle = $questionDBQueryResult["questionTitle"];
                  $questionDBqueryImage = $questionDBQueryResult["questionImage"];
                  $questionDBqueryDescription = $questionDBQueryResult["questionDescription"];
                  $questionDBqueryTime = $questionDBQueryResult["questionTime"];
                  $questionDBqueryType = $questionDBQueryResult["questionType"];

                  $questionDBqueryCorrectAnswerTitle = $questionDBQueryResult["questionCorrectAnswerTitle"];
                  $questionDBqueryCorrectAnswerImage = $questionDBQueryResult["questionCorrectAnswerImage"];
                  $questionDBqueryCorrectAnswerDescription = $questionDBQueryResult["questionCorrectAnswerDescription"];

                  $questionDBqueryWrongAnswerTitle = $questionDBQueryResult["questionWrongAnswerTitle"];
                  $questionDBqueryWrongAnswerImage = $questionDBQueryResult["questionWrongAnswerImage"];
                  $questionDBqueryWrongAnswerDescription = $questionDBQueryResult["questionWrongAnswerDescription"];


                  $questionDBQueryCategoryID = $questionDBQueryResult["categoryID"];
                }

                if (isset($_POST["editQuestion"])) {

                  ///////////////////////////////////////////////////////////// 
                  //Question Details
                  /////////////////////////////////////////////////////////////

                  //ISSET POST form - Add Name Text Field

                  if (!empty($_POST["editQuestionTitle"])) {
                    $editQuestionTitle = filterInput($_POST["editQuestionTitle"]);
                    $questionNewDetails["questionTitle"] = $editQuestionTitle;
                  }

                  //Array - Question Picture
                  if (isset($_FILES['editQuestionImage']) && $_FILES['editQuestionImage']['error'] === UPLOAD_ERR_OK) {
                    $editQuestionImage = uploadFile("assets/images/question/", "editQuestionImage");
                    $questionNewDetails["questionImage"] = $editQuestionImage['file'];

                    // Check if an old image exists, and if it's not empty
                    if (!empty($questionDBqueryImage)) {
                      $shouldUnlinkOldQuestionImage = true;
                    }
                  }

                  //ISSET POST - Text Area field
                  if (isset($_POST['editQuestionDescription']) && ($_POST['editQuestionDescription'] != "")) {
                    //Text area have text
                    $editQuestionDescription = $_POST['editQuestionDescription'];
                    $questionNewDetails["questionDescription"] = $editQuestionDescription;
                  } else {
                    //Text area no text
                    $editQuestionDescription = null;
                  }

                  //ISSET POST - Category Add DropDown field
                  if (!empty($_POST["editCategoryID"])) {
                    $questionNewDetails["categoryID"] = $_POST["editCategoryID"];
                  }

                  //ISSET POST - Question Time
                  if (!empty($_POST["editQuestionTime"])) {
                    $questionNewDetails["questionTime"] = $_POST["editQuestionTime"];
                  }

                  ///////////////////////////////////////////////////////////// 
                  //Answer Details
                  /////////////////////////////////////////////////////////////

                  //ISSET POST - Correct Answer Explanation Title
                  if (!empty($_POST["editCorrectAnswerTitle"])) {
                    $editCorrectAnswerTitle = filterInput($_POST["editCorrectAnswerTitle"]);
                    $questionNewDetails["questionCorrectAnswerTitle"] = $editCorrectAnswerTitle;
                  }

                  //ISSET POST - Correct Answer Explanation Image
                  if (isset($_FILES['editCorrectAnswerImage']) && $_FILES['editCorrectAnswerImage']['error'] === UPLOAD_ERR_OK) {
                    $editCorrectAnswerImage = uploadFile("assets/images/question/", "editCorrectAnswerImage");
                    $questionNewDetails["questionCorrectAnswerImage"] = $editCorrectAnswerImage['file'];

                    // Check if an old image exists, and if it's not empty
                    if (!empty($questionDBqueryCorrectAnswerImage)) {
                      $shouldUnlinkOldCorrectAnswerImage = true;
                    }
                  }

                  //ISSET POST - Correct Answer Explanation Description
                  if (isset($_POST['editCorrectAnswerDescription']) && ($_POST['editCorrectAnswerDescription'] != "")) {
                    //Text area have text
                    $editCorrectAnswerDescription = $_POST['editCorrectAnswerDescription'];
                    $questionNewDetails["questionCorrectAnswerDescription"] = $editCorrectAnswerDescription;
                  } else {
                    //Text area no text
                    $editCorrectAnswerDescription = null;
                  }

                  //ISSET POST - Wrong Answer Explanation Title
                  if (!empty($_POST["editWrongAnswerTitle"])) {
                    $editWrongAnswerTitle = filterInput($_POST["editWrongAnswerTitle"]);
                    $questionNewDetails["questionWrongAnswerTitle"] = $editWrongAnswerTitle;
                  }

                  //ISSET POST - Wrong Answer Explanation Image
                  if (isset($_FILES['editWrongAnswerImage']) && $_FILES['editWrongAnswerImage']['error'] === UPLOAD_ERR_OK) {
                    $editWrongAnswerImage = uploadFile("assets/images/question/", "editWrongAnswerImage");
                    $questionNewDetails["questionWrongAnswerImage"] = $editWrongAnswerImage['file'];

                    // Check if an old image exists, and if it's not empty
                    if (!empty($questionDBqueryWrongAnswerImage)) {
                      $shouldUnlinkOldWrongAnswerImage = true;
                    }
                  }

                  //ISSET POST - Wrong Answer Explanation Description
                  if (isset($_POST['editWrongAnswerDescription']) && ($_POST['editWrongAnswerDescription'] != "")) {
                    //Text area have text
                    $editWrongAnswerDescription = $_POST['editWrongAnswerDescription'];
                    $questionNewDetails["questionWrongAnswerDescription"] = $editWrongAnswerDescription;
                  } else {
                    //Text area no text
                    $editWrongAnswerDescription = null;
                  }

                  ///////////////////////////////////////////////////////////// 
                  //Option Details
                  /////////////////////////////////////////////////////////////

                  //ISSET POST - Multi Choice Text Options
                  if (empty($_POST["editMultiAnswerOption"])) {
                    $editMultiAnswerOption = null;
                  } else {
                    $editMultiAnswerOption = $_POST["editMultiAnswerOption"];
                  }

                  //ISSET POST - Multi Choice Text Select Correct Answer
                  if (!isset($_POST["editMultiAnswerCorrectOption"])) {
                    $editMultiAnswerCorrectOption = null;
                  } else {
                    $editMultiAnswerCorrectOption = $_POST["editMultiAnswerCorrectOption"];
                  }

                  // Check if any item in the array is an empty string
                  $hasEmptyString = in_array('', $editMultiAnswerOption, true);

                  // Check if any item in the array is empty
                  $hasEmptyValue = false;
                  if (is_array($editMultiAnswerOption)) {
                    foreach ($editMultiAnswerOption as $item) {
                      if (empty($item)) {
                        $hasEmptyValue = true;
                        break;
                      }
                    }
                  }

                  // Check if the array does not have a length of 4
                  $isNotLengthFour = (count($editMultiAnswerOption) !== 4);

                  if ($editQuestionTitle == "" || $editMultiAnswerOption == "" || $editMultiAnswerCorrectOption == "" || $hasEmptyString || $hasEmptyValue || $isNotLengthFour) {
                    authErrorMsg("All required fields need to be filled.");
                  } else {

                    //Check if fields contain the same information
                    if (
                      $editQuestionTitle == $questionDBqueryTitle &&  $editQuestionDescription == $questionDBqueryDescription && $editQuestionTime == $questionDBqueryTime
                      && $editCorrectAnswerTitle == $questionDBqueryCorrectAnswerTitle && $editCorrectAnswerDescription == $questionDBqueryCorrectAnswerDescription &&
                      $editWrongAnswerTitle == $editWrongAnswerDescription
                    ) {
                      sweetAlertTimerRedirect('Edit Question', 'No changes were made', 'success', (SITE_URL . "question-summary"));
                    } else {

                      DB::startTransaction();

                      DB::update('question', $questionNewDetails, "questionID=%i", $questionDBqueryID);

                      DB::delete('option', "questionID=%i", $questionDBqueryID);

                      for ($i = 0; $i < 4; $i++) { //Insert data for Option
                        if (isset($_POST["editMultiAnswerCorrectOption"][$i])) {
                          DB::insert('option', [
                            'optionText' => $editMultiAnswerOption[$i],
                            'optionCorrect' => 1,
                            'questionID' => $questionDBqueryID
                          ]);
                        } else {
                          DB::insert('option', [
                            'optionText' => $editMultiAnswerOption[$i],
                            'optionCorrect' => 0,
                            'questionID' => $questionDBqueryID
                          ]);
                        }
                      }

                      DB::update('question', ['questionDateUpdated' => date('Y-m-d H:i:s')], "questionID=%i", $questionDBqueryID);

                      //Successful Upload to DB
                      $success = DB::affectedRows();
                      if ($success) {
                        DB::commit();

                        // Unlink the old image here after successful DB update
                        if ($shouldUnlinkOldQuestionImage && !empty($questionDBqueryImage)) {
                          unlink("assets/images/question/" . $questionDBqueryImage);
                        }
                        // Unlink the old image here after successful DB update
                        if ($shouldUnlinkOldCorrectAnswerImage && !empty($questionDBqueryCorrectAnswerImage)) {
                          unlink("assets/images/question/" . $questionDBqueryCorrectAnswerImage);
                        }
                        // Unlink the old image here after successful DB update
                        if ($shouldUnlinkOldWrongAnswerImage && !empty($questionDBqueryWrongAnswerImage)) {
                          unlink("assets/images/question/" . $questionDBqueryWrongAnswerImage);
                        }

                        sweetAlertTimerRedirect('Edit Question', 'Question successfully updated!', 'success', (SITE_URL . "question-summary"));
                      } else {
                        DB::rollback();
                        sweetAlertTimerRedirect('Edit Question', 'An error occured. Please try again later.', 'error', (SITE_URL . "question-add"));
                      }
                    }
                  }
                }
              }
              ?>
              <div class="d-flex justify-content-between align-items-center mb-10">
                <h3 class="text-right">Add New</h3>
              </div>
              <div class="line mb-50"></div>

              <!-------------------------------------------------- Question Details Section -------------------------------------------------->
              <div class="mb-30">
                <h4 class="text-right">1. Question Details</h4>
              </div>
              <form method="POST" enctype="multipart/form-data">
                <div class="mt-30">
                  <label for="editQuestionTitle" class="labels">Question Title*</label>
                  <input type="text" name="editQuestionTitle" id="editQuestionTitle" class="form-control must-fill" placeholder="Insert question" value="<?php echo $questionDBqueryTitle; ?>">
                </div>
                <div class="mt-30">
                  <label for="editQuestionImage" class="labels">Question Image</label>
                  <div class="image-preview">
                    <input type="file" name="editQuestionImage" id="editQuestionImage" class="form-control">
                  </div>
                </div>

                <!---------- Display image preview if image was uploaded ---------->
                <?php if ($questionDBqueryImage != null) { ?>
                  <div class="row mt-10">
                    <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                      <div class="preview-border preview-question-image d-flex justify-content-center">
                        <?php
                        echo '<div class="my-auto" style="width:90%;"><img src="assets/images/question/' . $questionDBqueryImage . '" height="300px" width="300px" /></div>';
                        ?>
                      </div>
                    </div>
                  </div>
                <?php } ?>

                <div class="mt-30">
                  <label for="editQuestionDescription" class="labels">Question Description</label>
                  <textarea name="editQuestionDescription" class="form-control" id="editQuestionDescription" rows="10" cols="80"><?php echo $questionDBqueryDescription; ?></textarea>
                </div>
                <div class="row">
                  <div class="col-lg-12 mt-30"><label class="labels" for="editQuestionType">Question Type</label>
                    <br>
                    <select disabled class="form-select form-select-option" name="editQuestionType" id="editQuestionType" aria-label="Default select example">
                      <option disabled>Select options below: </option>
                      <option <?php if ($questionDBqueryType == 1) {
                                echo 'selected';
                              } ?> value="1">Multiple Choice - Text
                      </option>
                      <option <?php if ($questionDBqueryType == 2) {
                                echo 'selected';
                              } ?> value="2">Multiple Answers - Text
                      </option>
                      <option <?php if ($questionDBqueryType == 3) {
                                echo 'selected';
                              } ?> value="3">True or False
                      </option>
                      <option <?php if ($questionDBqueryType == 4) {
                                echo 'selected';
                              } ?> value="4">Multiple Choice - Image
                      </option>
                    </select>
                  </div>
                  <div class="col-lg-6 mt-30">
                    <label class="labels">Question Category</label>
                    <br>
                    <select disabled class="form-select form-select-option" name="editCategoryID" id="editCategoryID">
                      <option disabled>Select option: </option>
                      <?php
                      //Populate all the possible categorys
                      $queryByCategory = DB::query("SELECT * FROM `category` WHERE categoryStatus>%i", 0);
                      foreach ($queryByCategory as $queryCategory) {
                        $queryCategoryID = $queryCategory["categoryID"];
                        $queryCategoryName = $queryCategory["categoryName"];
                      ?>
                        <option value="<?php echo $queryCategoryID; ?>" <?php if ($queryCategoryID == $questionDBQueryCategoryID) {
                                                                          echo 'selected';
                                                                        } ?>><?php echo $queryCategoryName; ?>
                        </option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-lg-6 mt-30"><label class="labels" for="editQuestionTime">Question Time*</label>
                    <select class="form-select form-select-option" name="editQuestionTime" id="editQuestionTime">
                      <option disabled>Select option: </option>
                      <option <?php if ($questionDBqueryTime == 20) {
                                echo 'selected';
                              } ?> value="20">20
                      </option>
                      <option <?php if ($questionDBqueryTime == 30) {
                                echo 'selected';
                              } ?> value="30">30
                      </option>
                      <option <?php if ($questionDBqueryTime == 40) {
                                echo 'selected';
                              } ?> value="40">40
                      </option>
                      <option <?php if ($questionDBqueryTime == 50) {
                                echo 'selected';
                              } ?> value="50">50
                      </option>
                      <option <?php if ($questionDBqueryTime == 60) {
                                echo 'selected';
                              } ?> value="60">60
                      </option>
                    </select>
                  </div>
                </div>
                <div class="line my-5"></div>

                <!-------------------------------------------------- Option Details Section -------------------------------------------------->
                <div class="mb-30">
                  <h4 class="text-right">2. Option Details</h4>
                  <p>Choose a correct option by selecting a button on the right.</p>
                </div>
                <div class="edit-multiple-choice-text">
                  <!--- Multiple Choice Text --->
                  <?php
                  $queryDBOption = DB::query("SELECT `option`.* FROM `option` WHERE `option`.questionID=%i", $_GET["questionID"]);
                  $i = 0;

                  //Populate all options
                  foreach ($queryDBOption as $queryDBOptionResults) {
                    $queryDBOptionID = $queryDBOptionResults["optionID"];
                    $queryDBOptionText = $queryDBOptionResults["optionText"];
                    $queryDBOptionCorrect = $queryDBOptionResults["optionCorrect"];
                    $queryDBQuestionID = $queryDBOptionResults["questionID"];
                  ?>
                    <div class="row d-flex justify-content-center align-items-center mt-30">
                      <label class="labels">Option <?php echo $i + 1; ?>*</label>
                      <div class="col-lg-11 col-md-10 col-sm-10">
                        <input type="text" class="form-control" name="editMultiAnswerOption[]" id="editMultiAnswerOption<?php echo $i; ?>" value="<?php echo $queryDBOptionText; ?>" placeholder="Enter Option <?php echo $i + 1; ?>">
                      </div>
                      <div class="col-lg-1 col-md-2 col-sm-2 text-end">
                        <input type="checkbox" class="form-check-input" name="editMultiAnswerCorrectOption[<?php echo $i; ?>]" value="<?php echo $i; ?>" <?php
                                                                                                                                                          if ($queryDBOptionCorrect == 1) {
                                                                                                                                                            echo 'checked';
                                                                                                                                                          }
                                                                                                                                                          ?>>
                      </div>
                    </div>
                  <?php
                    $i++;
                  }
                  ?>
                </div>
                <div class="line my-5"></div>

                <!-------------------------------------------------- Answer Details Section -------------------------------------------------->
                <div class="mb-30">
                  <h4 class="text-right">3. Answer Details</h4>
                </div>
                <div class="answer-explanation mt-30 mb-30">
                  <div class="d-flex justify-content-between" style="flex-flow: row wrap;">
                    <div class="image-container">
                      <div class="col-lg-12 col-md-12 col-sm-12">
                        <label class="labels">Correct Answer Title</label>
                        <input type="text" class="form-control must-fill mb-20" name="editCorrectAnswerTitle" id="editCorrectAnswerTitle" placeholder="Correct Answer" value="<?php echo $questionDBqueryCorrectAnswerTitle; ?>">
                      </div>
                      <div>
                        <label class="labels">Correct Answer Image</label>
                        <input type="file" class="form-control mb-10" name="editCorrectAnswerImage" id="editCorrectAnswerImage" placeholder="Select file">
                      </div>

                      <!-- Display image preview if image was uploaded -->
                      <?php if ($questionDBqueryCorrectAnswerImage != null) { ?>
                        <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                          <div class="preview-border preview-correct-answer-image d-flex justify-content-center"><?php echo '<div class="my-auto" style="width:90%;"><img src="assets/images/question/' . $questionDBqueryCorrectAnswerImage . '" height="300px" width="300px" /></div>'; ?>
                          </div>
                        </div>
                      <?php } ?>

                      <div class="col-lg-12 col-md-12 col-sm-12 mt-20">
                        <label class="labels">Correct Answer Explanation</label>
                        <textarea name="editCorrectAnswerDescription" class="form-control must-fill" id="editCorrectAnswerDescription" rows="10" cols="80"><?php echo $questionDBqueryCorrectAnswerDescription; ?></textarea>
                      </div>
                    </div>
                    <div class="image-container">
                      <div class="col-lg-12 col-md-12 col-sm-12">
                        <label class="labels">Wrong Answer Title</label>
                        <input type="text" class="form-control must-fill mb-20" name="editWrongAnswerTitle" id="editWrongAnswerTitle" placeholder="Wrong Answer " value="<?php echo $questionDBqueryWrongAnswerTitle; ?>">
                      </div>

                      <div class="col-lg-12 col-md-12 col-sm-12">
                        <label class="labels">Wrong Answer Image</label>
                        <input type="file" class="form-control mb-10" name="editWrongAnswerImage" id="editWrongAnswerImage" placeholder="Select file">
                      </div>

                      <!-- Display image preview if image was uploaded -->
                      <?php if ($questionDBqueryWrongAnswerImage != null) { ?>
                        <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                          <div class="preview-border preview-wrong-answer-image d-flex justify-content-center">
                            <?php
                            echo '<div class="my-auto" style="width:90%;"><img src="assets/images/question/' . $questionDBqueryWrongAnswerImage . '" height="300px" width="300px" /></div>';
                            ?>
                          </div>
                        </div>
                      <?php } ?>

                      <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                        <div class="preview-border preview-wrong-answer-image"></div>
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 mt-20">
                        <label class="labels">Wrong Answer Explanation</label>
                        <textarea name="editWrongAnswerDescription" class="form-control must-fill" id="editWrongAnswerDescription" rows="10" cols="80"><?php echo $questionDBqueryWrongAnswerDescription; ?></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <!-------------------------------------------------- Submit / Cancel Button -------------------------------------------------->
                <div class="d-flex align-items-center justify-content-end">
                  <a href="<?php echo SITE_URL . "question-summary" ?>" class="btn-tertiary link-grey">Cancel</a>
                  <button class="btn btn-primary profile-button ml-50" id="editQuestion" name="editQuestion" type="submit">Edit Question</button>
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

  <script type="text/javascript">
    ckEditorComponent('editQuestionDescription');
    ckEditorComponent('editCorrectAnswerDescription');
    ckEditorComponent('editWrongAnswerDescription');

    // Upload Image Preview
    $('.preview-question-image').hide();
    $('.preview-correct-option-image').hide();
    $('.preview-wrong-option-image').hide();

    $('.preview-correct-answer-image').hide();
    $('.preview-wrong-answer-image').hide();

    $("#editQuestionImage").change(function() {
      imagePreview(this, '.preview-question-image');
    });

    $("#editCorrectImageOption").change(function() {
      imagePreview(this, '.preview-correct-option-image');
    });

    $("#editWrongImageOption").change(function() {
      imagePreview(this, '.preview-wrong-option-image');
    });

    $("#editCorrectAnswerImage").change(function() {
      imagePreview(this, '.preview-correct-answer-image');
    });

    $("#editWrongAnswerImage").change(function() {
      imagePreview(this, '.preview-wrong-answer-image');
    });
  </script>
</body>

</html>