<?php
//Define page name
$pageName = "Quizzes";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';

//Initialize variables for loading
$editQuizTitle = $editQuizDescription = $editQuestionQuantity = $editQuizStatus = $editCategoryID = '';

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
          <div class="box col-lg-12 col-md-12 col-sm-12 my-5">
            <?php

            if (!isset($_GET["quizID"]) || $_GET["quizID"] == "") {
              jsRedirect(SITE_URL . 'admin');
            } else {
              $quizDBQuery = DB::query("SELECT quiz.*, category.companyID, quizCategory.categoryID, category.*
              FROM `quiz`
              INNER JOIN `quizCategory` ON quiz.quizID = quizCategory.quizID
              INNER JOIN `category` ON quizCategory.categoryID = category.categoryID
              WHERE quiz.quizID=%i", $_GET["quizID"]);

              foreach ($quizDBQuery as $quizDBQueryResult) {
                $quizDBQueryID = $quizDBQueryResult["quizID"];
                $quizDBQueryTitle = $quizDBQueryResult["quizTitle"];
                $quizDBQueryDescription = $quizDBQueryResult["quizDescription"];
                $quizDBQueryQuantity = $quizDBQueryResult["quizQuestionQuantity"];
                $quizDBQueryStatus = $quizDBQueryResult["quizStatus"];
                $quizDBQueryCategoryName = $quizDBQueryResult["categoryName"];
                $quizDBQueryCategoryID = $quizDBQueryResult["categoryID"];
                $quizDBQueryCompanyID = $quizDBQueryResult["companyID"];

              }
              //ISSET POST form - Edit Quiz                        
              if (isset($_POST["editQuiz"])) {
                $editQuizTitle = filterInput($_POST["editQuizTitle"]);
                $editQuizDescription = filterInput($_POST["editQuizDescription"]);

                //POST ISSET - Edit Quiz Question Quantity Dropdown
                if (empty($_POST["editQuestionQuantity"])) {
                  $editQuestionQuantity = null;
                } else {
                  $editQuestionQuantity = $_POST["editQuestionQuantity"];
                }

                //POST ISSET - Edit Quiz Question Status Dropdown
                if (empty($_POST["editQuizStatus"])) {
                  $editQuizStatus = null;
                } else {
                  $editQuizStatus = $_POST["editQuizStatus"];
                }

                //POST ISSET - Edit Quiz Category Dropdown
                if (empty($_POST["editCategoryID"])) {
                  $editCategoryID = null;
                } else {
                  $editCategoryID = $_POST["editCategoryID"];
                }

                $numOfQnsAvailable = DB::queryFirstField("SELECT COUNT(*) FROM question WHERE categoryID=%i", $editCategoryID);

                //check if required inputs are not empty
                if ($editQuizTitle == "" ||  $editQuizDescription == "" || $editQuestionQuantity == "" || $editQuizStatus == "" ) {
                  authErrorMsg("All fields are required.");
                } elseif ($editQuestionQuantity < 1 || $editQuestionQuantity > 50) {
                  authErrorMsg("No. of Questions must be in the range of 1 to 50.");
                } elseif ($numOfQnsAvailable < $editQuestionQuantity) {
                  authErrorMsg("No. of Questions available (" . $numOfQnsAvailable . ") is less than your input (" . $editQuestionQuantity . ").");
                } else {
                  if ($editQuizTitle == $quizDBQueryTitle &&  $editQuizDescription == $quizDBQueryDescription && $editQuestionQuantity == $quizDBQueryQuantity && $editQuizStatus == $quizDBQueryStatus && $editCategoryID == $quizDBQueryCategoryID) {
                    authErrorMsg("No changes were made.");
                  } else {

                    DB::startTransaction();
                    DB::update('quiz', [
                      'quizTitle' => $editQuizTitle,
                      'quizDescription' => $editQuizDescription,
                      'quizDateUpdated' => date('Y-m-d H:i:s'),
                      'quizQuestionQuantity' => $editQuestionQuantity,
                      'quizStatus' => $editQuizStatus,
                    ], "quizID=%i", $quizDBQueryID);

                    //Successfully updated
                    $success = DB::affectedRows();
                    if ($success) {
                      DB::commit();
                      sweetAlertTimerRedirect('Edit Quiz', 'Quiz successfully updated!', 'success', (SITE_URL . "quiz-summary"));
                    } else {
                      DB::rollback();
                      sweetAlertTimerRedirect('Edit Quiz', 'No changes recorded!', 'error', (SITE_URL . "quiz-summary"));
                    }
                  }
                }
              }
            }

            ?>
            <!---------- Main Title ---------->
            <div class="p-3 py-5">
              <div class="d-flex justify-content-between align-items-center mb-30">
                <h4 class="text-right">Edit</h4>
              </div>

              <!---------- Form ---------->
              <form method="POST">
                <div class="mt-10">
                  <label for="editQuizTitle" class="labels">Quiz Title*</label>
                  <input type="text" name="editQuizTitle" id="editQuizTitle" class="form-control" placeholder="Insert Quiz Title" value="<?php echo $quizDBQueryTitle ?>">
                </div>
                <div class="mt-30">
                  <label for="editQuizDescription" class="labels">Quiz Description*</label>
                  <textarea type="text" name="editQuizDescription" id="editQuizDescription" placeholder="Insert Quiz Description" class="form-control"><?php echo $quizDBQueryDescription ?></textarea>
                </div>
                <div class="row">
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                    <label for="editCategoryID" class="labels">Category*</label>
                    <br>
                    <input disabled type="text" name="editCategoryName" id="editCategoryName" class="form-control" placeholder="E.g. John Doe" value="<?php echo $quizDBQueryCategoryName ?>">
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                    <label for="editQuestionQuantity" class="labels">No. of Questions (1-50)*</label>
                    <br>
                    <!-- <select class="form-select" name="editQuestionQuantity" id="editQuestionQuantity">
                      <option disabled>Select option: </option>
                      <?php
                      $x = 5;
                      for ($i = 1; $i <= 10; $i++) {
                      ?>
                        <option value="<?php echo $x * $i; ?>" <?php if (($x * $i) == $quizDBQueryQuantity) {
                                                                  echo 'selected';
                                                                } ?>><?php echo $x * $i; ?></option>
                      <?php
                      }
                      ?>
                    </select> -->
                    <input type="number" name="editQuestionQuantity" id="editQuestionQuantity" class="form-control" placeholder="Insert number of questions" value="<?php echo $quizDBQueryQuantity ?>">
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12 mt-30">
                    <label for="editQuizStatus" class="labels">Status*</label>
                    <br>
                    <select class="form-select" name="editQuizStatus" id="editQuizStatus" aria-label="Default select example">
                      <option disabled>Select option: </option>
                      <option value=2 <?php
                                      if ($quizDBQueryStatus == 2) {
                                        echo 'selected';
                                      } ?>>Active
                      </option>
                      <option value=1 <?php
                                      if ($quizDBQueryStatus == 1) {
                                        echo 'selected';
                                      }
                                      ?>>Inactive
                      </option>
                    </select>
                  </div>
                </div>

                <!--------- Actionables ---------->
                <div class="d-flex align-items-center justify-content-end mt-40">
                  <a href="<?php echo SITE_URL . "quiz-summary" ?>" class="btn-tertiary link-grey">Cancel</a>
                  <button class="btn btn-primary profile-button ml-50" name="editQuiz" type="submit">Update Quiz</button>
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