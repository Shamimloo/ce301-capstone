<!---------- Header Include ---------->
<?php
$pageName = "Home";
include 'assets/templates/quiz/header.php';

//Redirect to login page onstart
jsRedirect(SITE_URL . "quizzes-login");
// jsRedirect(SITE_QUIZ . "login.php");

?>

<!---------- Main Content ---------->

<body id="body">
  <div class="container">
    <div class="row">

      <!---------- Active Quiz ---------->
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 my-4 text-center">
        <h2 class="mb-2">ACTIVE QUIZ</h2>
        <div class="card-container row d-flex justify-content-start">
          <?php
          $quizQuery = DB::query("SELECT * FROM `quiz` WHERE quizStatus=%i AND levelID=%i", 2, $_SESSION["levelID"]);
          foreach ($quizQuery as $quizResult) {
            $quizID = $quizResult["quizID"];
            $quizTitle = $quizResult["quizTitle"];
          ?>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-12 my-2">
              <a href="<?php echo SITE_URL; ?>quizzes-quiz-info&quizID=<?php echo $quizID ?>" class="card-link">
                <div class="card">
                  <img src="assets/images/subject/math.jpeg" class="card-img-top card-quiz" alt="...">
                </div>
                <div class="card-body text-center my-2">
                  <span class="card-title"><?php echo $quizTitle . " " ?></span>
                </div>
              </a>
            </div>
          <?php
          }
          ?>
        </div>
      </div>

      <!---------- Subject ---------->
      <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 my-4 text-center">
        <h2 class="mb-2">SUBJECT</h2>
        <div class="card-container row d-flex justify-content-start">
          <?php
          $subjectQuery = DB::query("SELECT * FROM `subject`");
          foreach ($subjectQuery as $subjectResult) {
            $subjectID = $subjectResult["subjectID"];
            $subjectName = $subjectResult["subjectName"];
            $subjectImage = $subjectResult["subjectImage"];
          ?>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-12 my-2">
              <a href="<?php echo SITE_URL; ?>quizzes-info&subjectID=<?php echo $subjectID ?>" class="card-link">
                <div class="card">
                  <img src="assets/images/subject/<?php echo $subjectImage ?>" class="card-img-top card-quiz" alt="...">
                </div>
                <div class="card-body text-center my-2">
                  <span class="card-title"><?php echo ucwords($subjectName) . " " ?></span>
                </div>
              </a>
            </div>
          <?php
          }
          ?>
        </div>
      </div>
    </div>
  </div>

  <!---------- Footer Include ---------->
  <?php
  include 'assets/templates/quiz/footer.php';
  ?>

</body>

</html>