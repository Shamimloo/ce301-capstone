<?php
// include "assets/lib/db.class.php";
// include "assets/lib/config.php";
// include "assets/lib/functions.php";
// include "assets/lib/validation.php";
// include "assets/lib/alerts.php";
// include "assets/lib/database.php";

//Calling of query number 
if (isset($_POST["query"])) {

  //State - Times up
  if ($_POST["query"] == 9) {
    DB::update('studentQuiz', [
      'studentQuizStatus' => 1,
      'studentQuizDateTimeEnded' => date('Y-m-d H:i:s'),
    ], "studentQuizID=%i", $_SESSION["studentQuizID"]);
  } else {

    // Submit button pressed --> Reduce the questions remaining
    $_SESSION["remainingQns"]--;

    // Submit button pressed --> Reset question start time
    $_SESSION["questionStartedTime"] = "";

    //State - Correct Question 
    if ($_POST["query"] == 1) {
      $_SESSION["currentScore"]++;

      // tabulate score
      // 0 - 49% = 0
      // 50 - 59% = 2
      // 60 - 69% = 3
      // 70 - 79% = 4
      // 80 - 89% = 5
      // Calculate the percentage
      $percentage = ($_SESSION["currentScore"] / $_SESSION["numOfQns"]) * 100;

      // Determine the score based on the percentage
      if ($percentage < 50) {
          $scoreCurrent = 0;
      } elseif ($percentage >= 50 && $percentage < 60) {
          $scoreCurrent = 2;
      } elseif ($percentage >= 60 && $percentage < 70) {
          $scoreCurrent = 3;
      } elseif ($percentage >= 70 && $percentage < 80) {
          $scoreCurrent = 4;
      } elseif ($percentage >= 80) {
          $scoreCurrent = 5;
      }

      // update the score
      DB::update('studentQuiz', [
        'studentQuizScore' => $scoreCurrent
      ], "studentQuizID=%i", $_SESSION["studentQuizID"]);
    }

    //State - Completed quiz
    if ($_SESSION["remainingQns"] == 0) {
      DB::update('studentQuiz', [
        'studentQuizStatus' => 2,
        'studentQuizDateTimeEnded' => date('Y-m-d H:i:s'),
      ], "studentQuizID=%i", $_SESSION["studentQuizID"]);
    } else {
      // Increment the current question
      $_SESSION["currentQn"]++;
    }
  }
}
