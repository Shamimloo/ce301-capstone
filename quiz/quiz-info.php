<!---------- Header Include ---------->
<?php
$pageName = "Quiz Info";

include 'assets/templates/quiz/header.php';

//Check if the session has pageID and quizID
if (empty($_SESSION["pageID"]) || empty($_SESSION["quizID"])) {
  sweetAlertTimerRedirect("Invalid Session", "Please Log In", "error", SITE_URL . "quizzes-login");
}

if (!empty($_GET["quizID"])) {
  $_SESSION["quizID"] = $_GET["quizID"];
}

$quizQuery = DB::query("SELECT * FROM quiz INNER JOIN `quizCategory` ON quiz.quizID = quizCategory.quizID INNER JOIN `category` ON quizCategory.categoryID = `category`.categoryID WHERE quiz.quizID=%i", $_SESSION["quizID"]);
foreach ($quizQuery as $quizResult) {
  $numOfQns = $quizResult["quizQuestionQuantity"];
  $quizCategoryName = $quizResult["categoryName"];
}

if (isset($_POST["startQuiz"])) {

  //Init array for questions
  $_SESSION["questionArray"] = array();

  //Query all available questions in the category stored in session, and trimmed down to number of questions set by quiz
  $questionQuery = DB::query("SELECT DISTINCT question.questionID FROM question WHERE categoryID=%i ORDER BY RAND () LIMIT $numOfQns", $_SESSION["categoryID"]);


  if (empty($questionQuery)) {
    //If there is no questions
    sweetAlertTimerRedirect("Invalid Session", "Please Contact the Administrator. (No questions loaded)", "error", SITE_URL . "quizzes-404");
  } else {
    //If have questions, push into array
    foreach ($questionQuery as $questionResult) {
      $questionID = $questionResult["questionID"];
      array_push($_SESSION["questionArray"], $questionID);
    }
  }

  //If number of questions pushed into array < set number of questions, then equate that to be the number of questions
  if (count($_SESSION["questionArray"]) < $numOfQns) {
    $numOfQns = count($_SESSION["questionArray"]);
  }

  //insert entry into learnerQuiz table
  DB::insert('learnerQuiz', [
    'quizID' => $_SESSION["quizID"],
    'learnerID' => $_SESSION["learnerID"],
    'learnerQuizDateTimeStarted' => date('Y-m-d H:i:s')
  ]);

  $learnerQuizID = DB::insertId();

  $_SESSION["learnerQuizID"] = $learnerQuizID;
  $_SESSION["numOfQns"] = $numOfQns;
  $_SESSION["remainingQns"] = count($_SESSION["questionArray"]);
  $_SESSION["currentQn"] = 0;
  $_SESSION["currentScore"] = 0;
  $_SESSION["questionStartedTime"] = "";
  $_SESSION["quizCategoryName"] = $quizCategoryName;
  jsRedirect(SITE_URL . "quizzes-quiz");
}
?>
<!---------- Main Content ---------->

<body id="body-quiz-info">
  <!---------- Information ---------->
  <div class="info-bg"></div>
  <section id="quiz-info">
    <div class="container-md">
      <div class="row d-flex">
        <div class="col-lg-7 col-md-10 col-sm-10 col-xs-12 mx-auto mx-5 align-self-center">
          <div class="row">
            <h2 class="mb-4 text-light"><?php echo $quizCategoryName; ?> <strong>|</strong> Information</h2>
          </div>
          <?php
          $pageQuery = DB::query("SELECT * FROM `page` WHERE categoryID=%i AND pageStatus=%i AND pageID=%i", $_SESSION["categoryID"], 2, $_SESSION["pageID"]);
          foreach ($pageQuery as $pageResult) {
            $pageID = $pageResult["pageID"];
            $pageName = $pageResult["pageName"];
            $pageDescription = $pageResult["pageDescription"];
            $pageImage = $pageResult["pageImage"];
          ?>
            <div class="card-body-container card">
              <div class="row">
                <div class="col-12 card-image card-body text-center mx-5">
                  <img src="assets/images/infopage/<?php echo $pageImage; ?>" width="100%" height="100%">
                </div>
                <div class="col-12 card-body mx-5 mb-4 p-0">
                  <h3 class="mt-4"><?php echo $pageName ?></h3>
                  <p><?php echo $pageDescription ?></p>
                </div>
                <div class="col-lg-10 col-md-10 col-sm-10 card-body mx-5 mb-4 text-center">
                  <h4 class="mb-4">Ready for Quiz?</h4>
                  <div class="row">
                    <form method="POST" class="mx-auto">
                      <button class="btn btn-primary mb-4 mx-2" type="submit" name="startQuiz">Yes, Start Quiz</button>
                      <a href="<?php echo SITE_URL . "quizzes-logout"; ?>" class="btn btn-secondary mb-4 mx-2">I'm NOT Ready</a>
                    </form>
                  </div>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  <?php
          }
  ?>
  </section>
  <!---------- Footer Include ---------->
  <?php
  include 'assets/templates/quiz/footer.php';
  ?>

</body>

</html>