<?php
//Define Page Name
$pageName = "Unpublish Quiz";
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
    'quizStatus' => 1,
    'quizDateUpdated' => date('Y-m-d H:i:s'),
  ], "quizID=%i", $getQuizID);
}
DB::commit();
sweetAlertTimerRedirect('Quiz un-published', 'Quiz set as Draft', 'success', (SITE_URL . "quiz-summary"));
