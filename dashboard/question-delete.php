<?php
//Define Page Name
$pageName = "Delete Question";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/footer.php';

//Check if the user is admin, if not redirect away
// if (!isTeacherLoggedIn()) {
//   jsRedirect(SITE_URL . 'admin');
// }

//Delete question from database
if (isset($_GET["questionID"])) {
  $getQuestionID = $_GET["questionID"];
  DB::startTransaction();
  DB::delete('option', "questionID=%i", $getQuestionID);
  DB::delete('question', "questionID=%i", $getQuestionID);
}
DB::commit();
sweetAlertTimerRedirect('Question Deleted', 'Question Successfully Deleted', 'success', (SITE_URL . "question-summary"));
