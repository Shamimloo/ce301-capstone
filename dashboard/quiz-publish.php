<?php
//Define Page Name
$pageName = "Publish Quiz";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/footer.php';

//Check if the user is admin, if not redirect away
// if (!isTeacherLoggedIn()) {
//   jsRedirect(SITE_URL . 'admin');
// }

//Update quiz from database
if (isset($_GET["quizID"])) {
  $getQuizID = $_GET["quizID"];
  DB::startTransaction();
  DB::update('quiz', [
    'quizStatus' => 2,
    'quizDateUpdated' => date('Y-m-d H:i:s'),
    'quizDatePublished' => date('Y-m-d H:i:s'),
  ], "quizID=%i", $getQuizID);
}
DB::commit();
sweetAlertTimerRedirect('Quiz Published', 'Quiz Successfully Published', 'success', (SITE_URL . "quiz-summary"));
