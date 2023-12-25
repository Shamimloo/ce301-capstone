<?php
//Define page name
$pageName = "Questions";
//Include Header
include 'assets/templates/dashboard/header.php';
include_once 'assets/templates/dashboard/auth/header.php';

//Initialize variables for loading
$addQuestionTitle = $addQuestionDescription = $addQuestionType = $addQuestionTime = $addCategoryID = "";
$multiChoiceTextOption[0] = $multiChoiceTextOption[1] = $multiChoiceTextOption[2] = $multiChoiceTextOption[3] = "";
$multiAnswerOption[0] = $multiAnswerOption[1] = $multiAnswerOption[2] = $multiAnswerOption[3] = "";
$addCorrectAnswerTitle = $addWrongAnswerTitle = $addCorrectAnswerDescription = $addWrongAnswerDescription = "";


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
            <div class="message"></div>
            <div class="p-3 py-5">
              <p class='alert alert-danger hide mb-30'></p>
              <?php
              if (isset($_POST["addQuestion"])) {

                ///////////////////////////////////////////////////////////// 
                //Question Details
                /////////////////////////////////////////////////////////////

                //ISSET POST form - Add Name Text Field
                if (empty($_POST["addQuestionTitle"])) {
                  $addQuestionTitle = null;
                } else {
                  $addQuestionTitle = $_POST["addQuestionTitle"];
                }

                //ISSET POST - Image Upload field
                if (empty($_FILES["addQuestionImage"])) {
                  $addQuestionImage = null;
                } else {
                  $addQuestionImage = uploadFile("assets/images/question/", "addQuestionImage");
                }

                //ISSET POST - Text Area field
                if (isset($_POST['addQuestionDescription']) && ($_POST['addQuestionDescription'] != "")) {
                  //Text area have text
                  $addQuestionDescription = $_POST['addQuestionDescription'];
                } else {
                  //Text area no text
                  $addQuestionDescription = null;
                }

                //ISSET POST - Question Type DropDown field
                if (empty($_POST["addQuestionType"])) {
                  $addQuestionType = null;
                } else {
                  $addQuestionType = $_POST["addQuestionType"];
                }

                //ISSET POST - Category Add DropDown field
                if (empty($_POST["addCategoryID"])) {
                  $addCategoryID = null;
                } else {
                  $addCategoryID = $_POST["addCategoryID"];
                }

                //ISSET POST - Question Time
                if (empty($_POST["addQuestionTime"])) {
                  $addQuestionTime = null;
                } else {
                  $addQuestionTime = $_POST["addQuestionTime"];
                }

                ///////////////////////////////////////////////////////////// 
                //Answer Details
                /////////////////////////////////////////////////////////////

                //ISSET POST - Correct Answer Explanation Title
                if (empty($_POST["addCorrectAnswerTitle"])) {
                  $addCorrectAnswerTitle = null;
                } else {
                  $addCorrectAnswerTitle = $_POST["addCorrectAnswerTitle"];
                }

                //ISSET POST - Correct Answer Explanation Image
                if (empty($_FILES["addCorrectAnswerImage"])) {
                  $addCorrectAnswerImage = null;
                } else {
                  $addCorrectAnswerImage = uploadFile("assets/images/question/", "addCorrectAnswerImage");
                }

                //ISSET POST - Correct Answer Explanation Description
                if (isset($_POST['addCorrectAnswerDescription']) && ($_POST['addCorrectAnswerDescription'] != "")) {
                  //Text area have text
                  $addCorrectAnswerDescription = $_POST['addCorrectAnswerDescription'];
                } else {
                  //Text area no text
                  $addCorrectAnswerDescription = null;
                }

                //ISSET POST - Wrong Answer Explanation Title
                if (empty($_POST["addWrongAnswerTitle"])) {
                  $addWrongAnswerTitle = null;
                } else {
                  $addWrongAnswerTitle = $_POST["addWrongAnswerTitle"];
                }

                //ISSET POST - Wrong Answer Explanation Image
                if (empty($_FILES["addWrongAnswerImage"])) {
                  $addWrongAnswerImage = null;
                } else {
                  $addWrongAnswerImage = uploadFile("assets/images/question/", "addWrongAnswerImage");
                }

                //ISSET POST - Wrong Answer Explanation Description
                if (isset($_POST['addWrongAnswerDescription']) && ($_POST['addWrongAnswerDescription'] != "")) {
                  //Text area have text
                  $addWrongAnswerDescription = $_POST['addWrongAnswerDescription'];
                } else {
                  //Text area no text
                  $addWrongAnswerDescription = null;
                }

                ///////////////////////////////////////////////////////////// 
                //Insert data where question type = 1
                /////////////////////////////////////////////////////////////

                if ($addQuestionType == 1) {
                  //ISSET POST - Multi Choice Text Options
                  if (!isset($_POST["multiChoiceTextOption"])) {
                    $multiChoiceTextOption = null;
                  } else {
                    $multiChoiceTextOption = $_POST["multiChoiceTextOption"];
                  }

                  //ISSET POST - Multi Choice Text Select Correct Answer
                  if (!isset($_POST["multiChoiceTextCorrect"])) {
                    $multiChoiceTextCorrect = null;
                  } else {
                    $multiChoiceTextCorrect = $_POST["multiChoiceTextCorrect"];
                  }

                  // Check if any item in the array is an empty string
                  $hasEmptyString = in_array('', $multiChoiceTextOption, true);

                  // Check if any item in the array is empty
                  $hasEmptyValue = false;
                  if (is_array($multiChoiceTextOption)) {
                    foreach ($multiChoiceTextOption as $item) {
                      if (empty($item)) {
                        $hasEmptyValue = true;
                        break;
                      }
                    }
                  }

                  // Check if the array does not have a length of 4
                  $isNotLengthFour = (count($multiChoiceTextOption) !== 4);

                  if ($addQuestionTitle == "" ||  $addQuestionTime == "" || $multiChoiceTextOption == "" || $multiChoiceTextCorrect == "" || $hasEmptyString || $hasEmptyValue || $isNotLengthFour) {
                    authErrorMsg("Please fill up all required fields.");
                  } else {

                    DB::startTransaction();
                    DB::insert('question', [ // Insert data for Question
                      'questionTitle' => $addQuestionTitle,
                      'questionDescription' => $addQuestionDescription,
                      'questionType' => $addQuestionType,
                      'questionImage' => $addQuestionImage['file'],
                      'questionTime' => $addQuestionTime,

                      'questionCorrectAnswerTitle' => $addCorrectAnswerTitle,
                      'questionCorrectAnswerImage' => $addCorrectAnswerImage['file'],
                      'questionCorrectAnswerDescription' => $addCorrectAnswerDescription,

                      'questionWrongAnswerTitle' => $addWrongAnswerTitle,
                      'questionWrongAnswerImage' => $addWrongAnswerImage['file'],
                      'questionWrongAnswerDescription' => $addWrongAnswerDescription,

                      'categoryID' => $addCategoryID
                    ]);

                    $lastQuestionID = DB::insertId();



                    for ($i = 0; $i < 4; $i++) { //Insert data for Option
                      if ($multiChoiceTextCorrect == $i) {
                        DB::insert('option', [
                          'optionText' => $multiChoiceTextOption[$i],
                          'optionCorrect' => 1,
                          'questionID' => $lastQuestionID
                        ]);
                      } else {
                        DB::insert('option', [
                          'optionText' => $multiChoiceTextOption[$i],
                          'optionCorrect' => 0,
                          'questionID' => $lastQuestionID
                        ]);
                      }
                    }

                    //Successful Upload to DB
                    $success = DB::affectedRows();
                    if ($success) {
                      DB::commit();
                      sweetAlertTimerRedirect('Add Question', 'Question successfully added!', 'success', (SITE_URL . "question-summary"));
                    } else {
                      DB::rollback();
                      sweetAlertTimerRedirect('Add Question', 'An error occured. Please try again later.', 'error', (SITE_URL . "question-add"));
                    }
                  }
                } else if ($addQuestionType == 2) {

                  ///////////////////////////////////////////////////////////// 
                  //Insert data where question type = 2
                  /////////////////////////////////////////////////////////////

                  //ISSET POST - Multi Choice Text Options
                  if (empty($_POST["multiAnswerOption"])) {
                    $multiAnswerOption = null;
                  } else {
                    $multiAnswerOption = $_POST["multiAnswerOption"];
                  }

                  //ISSET POST - Multi Choice Text Select Correct Answer
                  if (empty($_POST["multiAnswerCorrectOption"])) {
                    $multiAnswerCorrectOption = null;
                  } else {
                    $multiAnswerCorrectOption = $_POST["multiAnswerCorrectOption"];
                  }

                  // Check if any item in the array is an empty string
                  $hasEmptyString = in_array('', $multiAnswerOption, true);

                  // Check if any item in the array is empty
                  $hasEmptyValue = false;
                  if (is_array($multiAnswerOption)) {
                    foreach ($multiAnswerOption as $item) {
                      if (empty($item)) {
                        $hasEmptyValue = true;
                        break;
                      }
                    }
                  }

                  // Check if the array does not have a length of 4
                  $isNotLengthFour = (count($multiAnswerOption) !== 4);

                  if ($addQuestionTitle == "" ||  $addQuestionTime == "" || $multiAnswerOption == "" || $multiAnswerCorrectOption == "" || $hasEmptyString || $hasEmptyValue || $isNotLengthFour) {
                    authErrorMsg("Please fill up all required fields.");
                  } else {
                    //Check array if its not empty - return an array with all checked values
                    if (!empty($_POST["multiAnswerCorrectOption"])) {
                      DB::startTransaction();
                      DB::insert('question', [ // Insert data for Question
                        'questionTitle' => $addQuestionTitle,
                        'questionDescription' => $addQuestionDescription,
                        'questionType' => $addQuestionType,
                        'questionImage' => $addQuestionImage['file'],
                        'questionTime' => $addQuestionTime,

                        'questionCorrectAnswerTitle' => $addCorrectAnswerTitle,
                        'questionCorrectAnswerImage' => $addCorrectAnswerImage['file'],
                        'questionCorrectAnswerDescription' => $addCorrectAnswerDescription,

                        'questionWrongAnswerTitle' => $addWrongAnswerTitle,
                        'questionWrongAnswerImage' => $addWrongAnswerImage['file'],
                        'questionWrongAnswerDescription' => $addWrongAnswerDescription,

                        'categoryID' => $addCategoryID
                      ]);

                      for ($i = 0; $i < 4; $i++) { //Insert data for Option
                        if (isset($_POST["multiAnswerCorrectOption"][$i])) {
                          DB::insert('option', [
                            'optionText' => $multiAnswerOption[$i],
                            'optionCorrect' => 1,
                            'questionID' => $lastQuestionID
                          ]);
                        } else {
                          DB::insert('option', [
                            'optionText' => $multiAnswerOption[$i],
                            'optionCorrect' => 0,
                            'questionID' => $lastQuestionID
                          ]);
                        }
                      }

                      //Successful Upload to DB
                      $success = DB::affectedRows();
                      if ($success) {
                        DB::commit();
                        sweetAlertTimerRedirect('Add Question', 'Question successfully added!', 'success', (SITE_URL . "question-summary"));
                      } else {
                        DB::rollback();
                        sweetAlertTimerRedirect('Add Question', 'An error occured. Please try again later.', 'error', (SITE_URL . "question-add"));
                      }
                    } else {
                      authErrorMsg("Please fill up all required fields.");
                    }
                  }
                } else if ($addQuestionType == 3) {

                  ///////////////////////////////////////////////////////////// 
                  //Insert data where question type = 3
                  /////////////////////////////////////////////////////////////

                  //ISSET POST - True/False Options
                  if (empty($_POST["trueOrFalseOption"])) {
                    $trueOrFalseOption = null;
                  } else {
                    $trueOrFalseOption = $_POST["trueOrFalseOption"];
                  }

                  //ISSET POST - Multi Choice Text Select Correct Answer
                  if (!isset($_POST["trueOrFalseCorrect"])) {
                    $trueOrFalseCorrect = null;
                  } else {
                    $trueOrFalseCorrect = $_POST["trueOrFalseCorrect"];
                  }

                  if ($addQuestionTitle == "" ||  $addQuestionTime == "" || $trueOrFalseOption == "" || $trueOrFalseCorrect == "") {
                    authErrorMsg("Please fill up all required fields.");
                  } else {


                    DB::startTransaction();
                    DB::insert('question', [ // Insert data for Question
                      'questionTitle' => $addQuestionTitle,
                      'questionDescription' => $addQuestionDescription,
                      'questionType' => $addQuestionType,
                      'questionImage' => $addQuestionImage['file'],
                      'questionTime' => $addQuestionTime,

                      'questionCorrectAnswerTitle' => $addCorrectAnswerTitle,
                      'questionCorrectAnswerImage' => $addCorrectAnswerImage['file'],
                      'questionCorrectAnswerDescription' => $addCorrectAnswerDescription,

                      'questionWrongAnswerTitle' => $addWrongAnswerTitle,
                      'questionWrongAnswerImage' => $addWrongAnswerImage['file'],
                      'questionWrongAnswerDescription' => $addWrongAnswerDescription,

                      'categoryID' => $addCategoryID
                    ]);

                    $lastQuestionID = DB::insertId();

                    for ($i = 0; $i < 2; $i++) { //Insert data for Option
                      if ($trueOrFalseCorrect == $i) {
                        DB::insert('option', [
                          'optionText' => $trueOrFalseOption[$i],
                          'optionCorrect' => 1,
                          'questionID' => $lastQuestionID
                        ]);
                      } else {
                        DB::insert('option', [
                          'optionText' => $trueOrFalseOption[$i],
                          'optionCorrect' => 0,
                          'questionID' => $lastQuestionID
                        ]);
                      }
                    }

                    //Successful Upload to DB
                    $success = DB::affectedRows();
                    if ($success) {
                      DB::commit();
                      sweetAlertTimerRedirect('Add Question', 'Question successfully added!', 'success', (SITE_URL . "question-summary"));
                    } else {
                      DB::rollback();
                      sweetAlertTimerRedirect('Add Question', 'An error occured. Please try again later.', 'error', (SITE_URL . "question-add"));
                    }
                  }
                } else if ($addQuestionType == 4) {

                  ///////////////////////////////////////////////////////////// 
                  //Insert data where question type = 4
                  /////////////////////////////////////////////////////////////

                  //ISSET POST - Image Answer Options
                  if (empty($_FILES["correctImageOption"])) {
                    $correctImageOption = null;
                  } else {
                    $correctImageOption = uploadFile("assets/images/question/", "correctImageOption");
                  }

                  //ISSET POST - Wrong Answer Explanation Image
                  if (empty($_FILES["wrongImageOption"])) {
                    $wrongImageOption = null;
                  } else {
                    $wrongImageOption = uploadFile("assets/images/question/", "wrongImageOption");
                  }

                  if ($addQuestionTitle == "" ||  $addQuestionTime == "" || $correctImageOption == "" || $wrongImageOption == "" || $correctImageOption['file'] == null || $wrongImageOption['file'] == null) {
                    authErrorMsg("Please fill up all required fields.");
                  } else {

                    DB::startTransaction();
                    DB::insert('question', [ // Insert data for Question
                      'questionTitle' => $addQuestionTitle,
                      'questionDescription' => $addQuestionDescription,
                      'questionType' => $addQuestionType,
                      'questionImage' => $addQuestionImage['file'],
                      'questionTime' => $addQuestionTime,

                      'questionCorrectAnswerTitle' => $addCorrectAnswerTitle,
                      'questionCorrectAnswerImage' => $addCorrectAnswerImage['file'],
                      'questionCorrectAnswerDescription' => $addCorrectAnswerDescription,

                      'questionWrongAnswerTitle' => $addWrongAnswerTitle,
                      'questionWrongAnswerImage' => $addWrongAnswerImage['file'],
                      'questionWrongAnswerDescription' => $addWrongAnswerDescription,

                      'categoryID' => $addCategoryID
                    ]);

                    $lastQuestionID = DB::insertId();

                    DB::insert('option', [
                      'optionText' => $correctImageOption['file'],
                      'optionCorrect' => 1,
                      'questionID' => $lastQuestionID
                    ]);

                    DB::insert('option', [
                      'optionText' => $wrongImageOption['file'],
                      'optionCorrect' => 0,
                      'questionID' => $lastQuestionID
                    ]);

                    //Successful Upload to DB
                    $success = DB::affectedRows();
                    if ($success) {
                      DB::commit();
                      sweetAlertTimerRedirect('Add Question', 'Question successfully added!', 'success', (SITE_URL . "question-summary"));
                    } else {
                      DB::rollback();
                      sweetAlertTimerRedirect('Add Question', 'An error occured. Please try again later.', 'error', (SITE_URL . "question-add"));
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
                  <label for="addQuestionTitle" class="labels">Question Title*</label>
                  <input type="text" name="addQuestionTitle" id="addQuestionTitle" class="form-control must-fill" placeholder="Insert question" value="<?php echo $addQuestionTitle; ?>">
                </div>
                <div class="mt-30">
                  <label for="addQuestionImage" class="labels">Question Image</label>
                  <div class="image-preview">
                    <input type="file" name="addQuestionImage" id="addQuestionImage" class="form-control">
                  </div>
                </div>
                <div>
                  <div class="row mt-10">
                    <div id="preview" class="col-lg-12 col-md-12 col-sm-12 text-center">
                      <div class="preview-border preview-question-image"></div>
                    </div>
                  </div>
                </div>
                <div class="mt-30">
                  <label for="addQuestionDescription" class="labels">Question Description</label>
                  <textarea name="addQuestionDescription" class="form-control" id="addQuestionDescription" rows="10" cols="80"><?php echo $addQuestionDescription; ?></textarea>
                </div>
                <div class="row">
                  <div class="col-lg-12 mt-30"><label class="labels" for="addQuestionType">Question Type</label>
                    <br>
                    <select class="form-select form-select-option" name="addQuestionType" id="addQuestionType" aria-label="Default select example">
                      <option disabled>Select options below: </option>
                      <option <?php if ($addQuestionType == 1) {
                                echo 'selected';
                              } ?> value="1">Multiple Choice - Text
                      </option>
                      <option <?php if ($addQuestionType == 2) {
                                echo 'selected';
                              } ?> value="2">Multiple Answers - Text
                      </option>
                      <option <?php if ($addQuestionType == 3) {
                                echo 'selected';
                              } ?> value="3">True or False
                      </option>
                      <option <?php if ($addQuestionType == 4) {
                                echo 'selected';
                              } ?> value="4">Multiple Choice - Image
                      </option>
                    </select>
                  </div>
                  <div class="col-lg-6 mt-30">
                    <label class="labels">Question Category</label>
                    <br>
                    <select class="form-select form-select-option" name="addCategoryID" id="addCategoryID">
                      <option disabled>Select option: </option>
                      <?php
                      //Populate all the possible categorys
                      $queryByCategory = DB::query("SELECT * FROM `category` WHERE categoryStatus>%i", 0);
                      foreach ($queryByCategory as $queryCategory) {
                        $queryCategoryID = $queryCategory["categoryID"];
                        $queryCategoryName = $queryCategory["categoryName"];
                      ?>
                        <option value="<?php echo $queryCategoryID; ?>" <?php if ($queryCategoryID == $addCategoryID) {
                                                                          echo 'selected';
                                                                        } ?>><?php echo $queryCategoryName; ?>
                        </option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-lg-6 mt-30"><label class="labels" for="addQuestionTime">Question Time*</label>
                    <select class="form-select form-select-option" name="addQuestionTime" id="addQuestionTime">
                      <option disabled>Select option: </option>
                      <?php
                      $seconds = 10;
                      for ($i = 2; $i <= 6; $i++) {
                      ?>
                        <option value="<?php echo $seconds * $i; ?>" <?php if (($seconds * $i) == $addQuestionTime) {
                                                                        echo 'selected';
                                                                      } ?>><?php echo $seconds * $i; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>

                </div>
                <div class="line my-5"></div>

                <!-------------------------------------------------- Option Details Section -------------------------------------------------->
                <div class="option-details-title mb-30">
                  <h4 class="text-right">2. Option Details</h4>
                  <p>Choose one or multiple correct answers by <b>checking the boxes on the right</b>.</p>
                </div>
                <div class="multiple-choice-text">
                  <!--- Multiple Choice Text --->
                  <div class="row d-flex justify-content-center align-items-center mt-30">
                    <label class="labels">Option 01</label>
                    <div class="col-lg-11 col-md-10 col-sm-10">
                      <input type="text" class="form-control multiChoiceTextOption" name="multiChoiceTextOption[]" id="multiChoiceTextOption1" value="<?php echo $multiChoiceTextOption[0]; ?>" placeholder="Enter Option 1">
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 text-end">
                      <input class="form-check-input" type="radio" name="multiChoiceTextCorrect" value="0" id="multiChoiceTextCorrect1" <?php if (isset($_POST["multiChoiceTextCorrect"]) && $_POST["multiChoiceTextCorrect"] == "0") {
                                                                                                                                          echo 'checked';
                                                                                                                                        } ?>>
                    </div>
                  </div>
                  <div class="row d-flex justify-content-center align-items-center mt-20">
                    <label class="labels">Option 02</label>
                    <div class="col-lg-11 col-md-10 col-sm-10">
                      <input type="text" class="form-control multiChoiceTextOption" name="multiChoiceTextOption[]" id="multiChoiceTextOption2" value="<?php echo $multiChoiceTextOption[1]; ?>" placeholder="Enter Option 2">
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 text-end">
                      <input class="form-check-input" type="radio" name="multiChoiceTextCorrect" value="1" id="multiChoiceTextCorrect2" <?php if (isset($_POST["multiChoiceTextCorrect"]) && $_POST["multiChoiceTextCorrect"] == "1") {
                                                                                                                                          echo 'checked';
                                                                                                                                        } ?>>
                    </div>
                  </div>
                  <div class="row d-flex justify-content-center align-items-center mt-20">
                    <label class="labels">Option 03</label>
                    <div class="col-lg-11 col-md-10 col-sm-10">
                      <input type="text" class="form-control multiChoiceTextOption" name="multiChoiceTextOption[]" id="multiChoiceTextOption3" value="<?php echo $multiChoiceTextOption[2]; ?>" placeholder="Enter Option 3">
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 text-end">
                      <input class="form-check-input" type="radio" name="multiChoiceTextCorrect" value="2" id="multiChoiceTextCorrect3" <?php if (isset($_POST["multiChoiceTextCorrect"]) && $_POST["multiChoiceTextCorrect"] == "2") {
                                                                                                                                          echo 'checked';
                                                                                                                                        } ?>>
                    </div>
                  </div>
                  <div class="row d-flex justify-content-center align-items-center mt-20">
                    <label class="labels">Option 04</label>
                    <div class="col-lg-11 col-md-10 col-sm-10">
                      <input type="text" class="form-control multiChoiceTextOption" name="multiChoiceTextOption[]" id="multiChoiceTextOption4" value="<?php echo $multiChoiceTextOption[3]; ?>" placeholder="Enter Option 4">
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 text-end">
                      <input class="form-check-input" type="radio" name="multiChoiceTextCorrect" value="3" id="multiChoiceTextCorrect4" <?php if (isset($_POST["multiChoiceTextCorrect"]) && $_POST["multiChoiceTextCorrect"] == "3") {
                                                                                                                                          echo 'checked';
                                                                                                                                        } ?>>
                    </div>
                  </div>
                </div>
                <div class="multiple-answer-text">
                  <!---------- Multiple Answers Text ---------->
                  <div class="row d-flex justify-content-center align-items-center mt-30">
                    <label class="labels">Option 01</label>
                    <div class="col-lg-11 col-md-10 col-sm-10">
                      <input type="text" class="form-control" name="multiAnswerOption[]" id="multiAnswerOption1" placeholder="Enter Option 1" value="<?php echo $multiAnswerOption[0]; ?>">
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 text-end">
                      <input type="checkbox" class="form-check-input" name="multiAnswerCorrectOption[0]" value="0" <?php if (isset($_POST["multiAnswerCorrectOption"]) && $_POST["multiAnswerCorrectOption"] == "0") {
                                                                                                                      echo 'checked';
                                                                                                                    } ?>>
                    </div>
                  </div>
                  <div class="row d-flex justify-content-center align-items-center mt-20">
                    <label class="labels">Option 02</label>
                    <div class="col-lg-11 col-md-10 col-sm-10">
                      <input type="text" class="form-control" name="multiAnswerOption[]" id="multiAnswerOption2" placeholder="Enter Option 2" value="<?php echo $multiAnswerOption[1]; ?>">
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 text-end">
                      <input type="checkbox" class="form-check-input" name="multiAnswerCorrectOption[1]" value="1" <?php if (isset($_POST["multiAnswerCorrectOption"]) && $_POST["multiAnswerCorrectOption"] == "1") {
                                                                                                                      echo 'checked';
                                                                                                                    } ?>>
                    </div>
                  </div>
                  <div class="row d-flex justify-content-center align-items-center mt-20">
                    <label class="labels">Option 03</label>
                    <div class="col-lg-11 col-md-10 col-sm-10">
                      <input type="text" class="form-control" name="multiAnswerOption[]" id="multiAnswerOption3" placeholder="Enter Option 3" value="<?php echo $multiAnswerOption[2]; ?>">
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 text-end">
                      <input type="checkbox" class="form-check-input" name="multiAnswerCorrectOption[2]" value="2" <?php if (isset($_POST["multiAnswerCorrectOption"]) && $_POST["multiAnswerCorrectOption"] == "2") {
                                                                                                                      echo 'checked';
                                                                                                                    } ?>>
                    </div>
                  </div>
                  <div class="row d-flex justify-content-center align-items-center mt-20">
                    <label class="labels">Option 04</label>
                    <div class="col-lg-11 col-md-10 col-sm-10">
                      <input type="text" class="form-control" name="multiAnswerOption[]" id="multiAnswerOption4" placeholder="Enter Option 4" value="<?php echo $multiAnswerOption[3]; ?>">
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 text-end">
                      <input type="checkbox" class="form-check-input" name="multiAnswerCorrectOption[3]" value="3" <?php if (isset($_POST["multiAnswerCorrectOption"]) && $_POST["multiAnswerCorrectOption"] == "3") {
                                                                                                                      echo 'checked';
                                                                                                                    } ?>>
                    </div>
                  </div>
                </div>
                <div class="true-or-false">
                  <!---------- True or False ---------->
                  <div class="row d-flex justify-content-center align-items-center mt-30">
                    <label class="labels">Option 01*</label>
                    <div class="col-lg-11 col-md-10 col-sm-10">
                      <input type="text" class="form-control must-fill true-or-false-option" name="trueOrFalseOption[]" id="trueOrFalseOption1" value="True" placeholder="Enter Option 1" readonly="readonly">
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 text-end">
                      <input class="form-check-input must-fill" type="radio" name="trueOrFalseCorrect" value="0" id="trueOrFalseCorrect1" <?php if (isset($_POST["trueOrFalseCorrect"]) && $_POST["trueOrFalseCorrect"] == "0") {
                                                                                                                                            echo 'checked';
                                                                                                                                          } ?>>
                    </div>
                  </div>
                  <div class="row d-flex justify-content-center align-items-center mt-20">
                    <label class="labels">Option 02*</label>
                    <div class="col-lg-11 col-md-10 col-sm-10">
                      <input type="text" class="form-control must-fill true-or-false-option" name="trueOrFalseOption[]" id="trueOrFalseOption2" value="False" placeholder="Enter Option 2" readonly="readonly">
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 text-end">
                      <input class="form-check-input must-fill" type="radio" name="trueOrFalseCorrect" value="1" id="trueOrFalseCorrect2" <?php if (isset($_POST["trueOrFalseCorrect"]) && $_POST["trueOrFalseCorrect"] == "1") {
                                                                                                                                            echo 'checked';
                                                                                                                                          } ?>>
                    </div>
                  </div>
                </div>
                <div class="multiple-choice-image">
                  <!--- Multiple Choice Image --->
                  <div class="d-flex justify-content-between" style="flex-flow: row wrap;">
                    <div class="image-container">
                      <div class="col-lg-12 col-md-12 col-sm-12">
                        <label class="labels">Correct Image Option*</label>
                        <input type="file" name="correctImageOption" id="correctImageOption" class="form-control" placeholder="Select file">
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                        <div class="preview-border preview-correct-option-image"></div>
                      </div>
                    </div>
                    <div class="image-container">
                      <div class="col-lg-12 col-md-12 col-sm-12">
                        <label class="labels">Wrong Image Option*</label>
                        <input type="file" name="wrongImageOption" id="wrongImageOption" class="form-control" placeholder="Select file">
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                        <div class="preview-border preview-wrong-option-image"></div>
                      </div>
                    </div>
                  </div>
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
                        <input type="text" class="form-control must-fill mb-20" name="addCorrectAnswerTitle" id="addCorrectAnswerTitle" placeholder="Correct Answer" value="<?php echo $addCorrectAnswerTitle; ?>">
                      </div>
                      <div>
                        <label class="labels">Correct Answer Image</label>
                        <input type="file" class="form-control mb-10" name="addCorrectAnswerImage" id="addCorrectAnswerImage" placeholder="Select file">
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 text-center preview-container">
                        <div class="preview-border preview-correct-answer-image"></div>
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 mt-20">
                        <label class="labels">Correct Answer Explanation</label>
                        <textarea name="addCorrectAnswerDescription" class="form-control must-fill" id="addCorrectAnswerDescription" rows="10" cols="80"><?php echo $addCorrectAnswerDescription; ?></textarea>
                      </div>
                    </div>
                    <div class="image-container">
                      <div class="col-lg-12 col-md-12 col-sm-12">
                        <label class="labels">Wrong Answer Title</label>
                        <input type="text" class="form-control must-fill mb-20" name="addWrongAnswerTitle" id="addWrongAnswerTitle" placeholder="Wrong Answer " value="<?php echo $addWrongAnswerTitle; ?>">
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12">
                        <label class="labels">Wrong Answer Image</label>
                        <input type="file" class="form-control mb-10" name="addWrongAnswerImage" id="addWrongAnswerImage" placeholder="Select file">
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 text-center">
                        <div class="preview-border preview-wrong-answer-image"></div>
                      </div>
                      <div class="col-lg-12 col-md-12 col-sm-12 mt-20">
                        <label class="labels">Wrong Answer Explanation</label>
                        <textarea name="addWrongAnswerDescription" class="form-control must-fill" id="addWrongAnswerDescription" rows="10" cols="80"><?php echo $addWrongAnswerDescription; ?></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <!-------------------------------------------------- Submit / Cancel Button -------------------------------------------------->
                <div class="d-flex align-items-center justify-content-end">
                  <a href="<?php echo SITE_URL . "question-summary" ?>" class="btn-tertiary link-grey">Cancel</a>
                  <button class="btn btn-primary profile-button ml-50" id="addQuestion" name="addQuestion" type="submit">Add Question</button>
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
</body>

<script type="text/javascript">
  ckEditorComponent('addQuestionDescription');
  ckEditorComponent('addCorrectAnswerDescription');
  ckEditorComponent('addWrongAnswerDescription');

  $(document).ready(function() {
    // On refresh check if there are values selected
    if (sessionStorage.selectVal) {
      // Select the value stored
      `            $("select#addQuestionType").val(sessionStorage.selectVal);
`
    }

    // Get question type value
    function getQuestionLayout(value) {
      if (value == 1) {
        $(".multiple-choice-text").show();
        $(".multiple-answer-text").hide();
        $(".true-or-false").hide();
        $(".multiple-choice-image").hide();
        $(".option-details-title > p").show();

      } else if (value == 2) {
        $(".multiple-answer-text").show();
        $(".true-or-false").hide();
        $(".multiple-choice-image").hide();
        $(".multiple-choice-text").hide();
        $(".option-details-title > p").show();

      } else if (value == 3) {
        $(".true-or-false").show();
        $(".multiple-choice-text").hide();
        $(".multiple-answer-text").hide();
        $(".multiple-choice-image").hide();
        $(".option-details-title > p").show();

      } else if (value == 4) {
        $(".multiple-choice-image").show();
        $(".multiple-choice-text").hide();
        $(".multiple-answer-text").hide();
        $(".true-or-false").hide();
        $(".option-details-title > p").hide();
      }
    }

    // On change store the value
    $("select#addQuestionType").change(function() {
      var getValue = $(this).val();
      sessionStorage.setItem('selectVal', getValue);

      getQuestionLayout(getValue);
    });

    // Get the value of the specified local storage item:
    var options = sessionStorage.getItem('selectVal');
    getQuestionLayout(options);
  })

  // Upload Image Preview
  $('.preview-question-image').hide();
  $('.preview-correct-option-image').hide();
  $('.preview-wrong-option-image').hide();

  $('.preview-correct-answer-image').hide();
  $('.preview-wrong-answer-image').hide();

  $("#addQuestionImage").change(function() {
    imagePreview(this, '.preview-question-image');
  });

  $("#correctImageOption").change(function() {
    imagePreview(this, '.preview-correct-option-image');
  });

  $("#wrongImageOption").change(function() {
    imagePreview(this, '.preview-wrong-option-image');
  });

  $("#addCorrectAnswerImage").change(function() {
    imagePreview(this, '.preview-correct-answer-image');
  });

  $("#addWrongAnswerImage").change(function() {
    imagePreview(this, '.preview-wrong-answer-image');
  });
</script>

</html>