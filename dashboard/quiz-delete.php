<?php
//Define Page Name
$pageName = "Delete Quiz";
//Include Header & Footer
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
    'quizStatus' => 0,
  ], "quizID=%i", $getQuizID);
}
DB::commit();
sweetAlertTimerRedirect('Quiz Deleted', 'Quiz Successfully Deleted', 'success', (SITE_URL . "quiz-summary"));
