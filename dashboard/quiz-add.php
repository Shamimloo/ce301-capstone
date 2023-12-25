<?php
//Define page name
$pageName = "Quizzes";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';

//Initialize variables for loading
$addQuizTitle = $addQuizDescription = $addQuestionQuantity = $addQuizStatus = $addCategoryID = '';

if (isset($_SESSION['companyID'])) {
  $companyID = $_SESSION['companyID'];
} else {
  $companyID = $_COOKIE['companyID'];
}
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

            //ISSET POST form - Add Quiz
            if (isset($_POST["addQuiz"])) {
              $addQuizTitle = filterInput($_POST["addQuizTitle"]);
              $addQuizDescription = filterInput($_POST["addQuizDescription"]);

              //POST ISSET - Add Quiz Title
              if (empty($_POST["addQuestionQuantity"])) {
                $addQuestionQuantity = null;
              } else {
                $addQuestionQuantity = $_POST["addQuestionQuantity"];
              }

              //POST ISSET - Add Quiz Status
              if (empty($_POST["addQuizStatus"])) {
                $addQuizStatus = null;
              } else {
                $addQuizStatus = $_POST["addQuizStatus"];
              }

              //POST ISSET - Add Quiz Category
              if (empty($_POST["addCategoryID"])) {
                $addCategoryID = null;
              } else {
                $addCategoryID = $_POST["addCategoryID"];
              }

              //POST ISSET - Add Quiz Level
              if (empty($_POST["addLevelID"])) {
                $addLevelID = null;
              } else {
                $addLevelID = $_POST["addLevelID"];
              }

              $numOfQnsAvailable = DB::queryFirstField("SELECT COUNT(*) FROM question WHERE categoryID=%i", $addCategoryID);

              if ($addQuizTitle == "" || $addQuizDescription == "" || $addQuestionQuantity == "" || $addQuizStatus == "" || $addCategoryID == "" ) {
                authErrorMsg("All fields are required.");
              } elseif ($addQuestionQuantity < 1 || $addQuestionQuantity > 50) {
                authErrorMsg("No. of Questions must be in the range of 1 to 50.");
              } elseif ($numOfQnsAvailable < $addQuestionQuantity) {
                authErrorMsg("No. of Questions available (" . $numOfQnsAvailable . ") is less than your input (" . $addQuestionQuantity . ").");
              } else {
                // Insert into quiz table
                DB::startTransaction();
                DB::insert('quiz', [
                  'quizTitle' => $addQuizTitle,
                  'quizDescription' => $addQuizDescription,
                  'quizQuestionQuantity' => $addQuestionQuantity,
                  'quizStatus' => $addQuizStatus,
                  'quizDateCreated' => date('Y-m-d H:i:s'), // Add the creation date
                  'quizDateUpdated' => date('Y-m-d H:i:s')  // Add the update date
                ]);

                // Get the generated quizID
                $quizID = DB::insertId();

                // Insert into quizCategory table
                DB::insert('quizCategory', [
                  'quizID' => $quizID,
                  'categoryID' => $addCategoryID
                ]);

                // Successfully inserted
                $success = DB::affectedRows();
                if ($success) {
                  DB::commit();
                  sweetAlertTimerRedirect('Add Quiz', 'Quiz successfully added!', 'success', (SITE_URL . "quiz-summary"));
                } else {
                  DB::rollback();
                  sweetAlertTimerRedirect('Add Quiz', 'No changes recorded!', 'error', (SITE_URL . "quiz-summary"));
                }
              }
            }


            ?>

            <!---------- Main Title ---------->
            <div class="p-3 py-5">
              <div class="d-flex justify-content-between align-items-center mb-30">
                <h4 class="text-right">Add New</h4>
              </div>

              <!---------Form ---------->
              <form method="POST">
                <div class="mt-10">
                  <label for="addQuizTitle" class="labels">Quiz Title*</label>
                  <input type="text" name="addQuizTitle" id="addQuizTitle" class="form-control" placeholder="Insert Quiz Title" value="<?php echo $addQuizTitle ?>">
                </div>
                <div class="mt-30">
                  <label for="addQuizDescription" class="labels">Quiz Description*</label>
                  <textarea type="text" name="addQuizDescription" id="addQuizDescription" placeholder="Insert Quiz Description" class="form-control"><?php echo $addQuizDescription ?></textarea>
                </div>
                <div class="row">
                  <div class="col-6 mt-30">
                    <label for="addCategoryID" class="labels">Category*</label>
                    <br>
                    <select class="form-select form-select-option" name="addCategoryID" id="addCategoryID" onchange="getClass(this.value);">
                      <option selected="true" disabled="disabled">Select category</option>
                      <?php
                      $queryByCategory = DB::query("SELECT * FROM `category` WHERE categoryStatus>%i AND companyID = %i ", 0, $companyID);
                      foreach ($queryByCategory as $queryCategory) {
                        $queryCategoryID = $queryCategory["categoryID"];
                        $queryCategoryName = $queryCategory["categoryName"];
                      ?>
                        <option value="<?php echo $queryCategoryID; ?>" <?php if ($queryCategoryID == $addCategoryID) {
                                                                          echo 'selected';
                                                                        } ?>><?php echo $queryCategoryName; ?></option>
                      <?php
                      }
                      ?>
                    </select>
                  </div>
                  <div class="col-6 mt-30">
                    <label for="addQuestionQuantity" class="labels">No. of Questions (1-50)*</label>
                    <br>
                    <!-- <select class="form-select form-select-option" name="addQuestionQuantity" id="addQuestionQuantity" onchange="getClass(this.value);">
                      <option selected="true" disabled="disabled">Select number of questions</option>
                      <?php
                      $x = 5;
                      for ($i = 1; $i <= 10; $i++) {
                      ?>
                        <option value="<?php echo $x * $i; ?>" <?php if (($x * $i) == $addQuestionQuantity) {
                                                                  echo 'selected';
                                                                } ?>><?php echo $x * $i; ?></option>
                      <?php
                      }
                      ?>
                    </select> -->
                    <input type="number" name="addQuestionQuantity" id="addQuestionQuantity" class="form-control" placeholder="Insert number of questions" value="<?php echo $addQuestionQuantity ?>">
                  </div>
                  <div class="col-6 mt-30">
                    <label for="addQuizStatus" class="labels">Status*</label>
                    <br>
                    <select class="form-select form-select-option" name="addQuizStatus" id="addQuizStatus" aria-label="Default select example">
                      <option disabled>Select option</option>
                      <option value=2 <?php
                                      if ($addQuizStatus == 2) {
                                        echo 'selected';
                                      } ?>>Active</option>
                      <option value=1 <?php
                                      if ($addQuizStatus == 1) {
                                        echo 'selected';
                                      }
                                      ?>>Inactive</option>
                    </select>
                  </div>
                </div>

                <!--------- Actionables ---------->
                <div class="d-flex align-items-center justify-content-end mt-40">
                  <a href="<?php echo SITE_URL . "quiz-summary" ?>" class="btn-tertiary link-grey">Cancel</a>
                  <button class="btn btn-primary profile-button ml-50" name="addQuiz" id="addQuiz" type="submit">Add Quiz</button>
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

</html>