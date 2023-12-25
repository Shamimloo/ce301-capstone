<?php
//Define Page Name
$pageName = "Delete Quiz";
//Include Header & Footer
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/footer.php';


// Check if question ID and level IDs are provided
if (isset($_POST['questionID']) && isset($_POST['levelIDs'])) {
  $questionID = $_POST['questionID'];
  $levelIDs = $_POST['levelIDs'];

  // Convert level IDs to array
  $levelIDs = explode(',', $levelIDs);

  // Delete existing question-level mappings
  DB::delete('questionLevel', 'questionID=%i', $questionID);

  // Insert new question-level mappings
  foreach ($levelIDs as $levelID) {
    DB::insert('questionLevel', array(
      'questionID' => $questionID,
      'levelID' => $levelID
    ));
  }

  echo 'success';
} else {
  echo 'error';
}
