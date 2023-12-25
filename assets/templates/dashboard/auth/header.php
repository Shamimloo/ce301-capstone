<?php
// Check if facilitator is not logged in, redirect them to the login page
if (!isFacilitatorLoggedIn()) {
  jsRedirect(SITE_URL . 'login');
}

// Initialize variables with logged in facilitatorEmail session or cookie
if (isset($_SESSION["facilitatorEmail"])) {
  $facilitatorEmail = $_SESSION["facilitatorEmail"];
} elseif (isset($_COOKIE["facilitatorEmail"])) {
  $facilitatorEmail = $_COOKIE["facilitatorEmail"];
}

// Query currently logged in facilitator from the database
$facilitatorDBQuery = DB::query("SELECT * FROM facilitator WHERE facilitatorEmail=%s", $facilitatorEmail);
foreach ($facilitatorDBQuery as $facilitatorDBQueryResult) {
  $facilitatorDBID = $facilitatorDBQueryResult["facilitatorID"];
  $facilitatorDBName = $facilitatorDBQueryResult["facilitatorName"];
  $facilitatorDBEmail = $facilitatorDBQueryResult["facilitatorEmail"];
  $facilitatorDBPassword = $facilitatorDBQueryResult["facilitatorPassword"];
  $facilitatorDBPermission = $facilitatorDBQueryResult["facilitatorPermission"];
  $facilitatorDBDateCreated = $facilitatorDBQueryResult["facilitatorDateCreated"];
  $facilitatorDBPicture = $facilitatorDBQueryResult["facilitatorProfileImage"];
  $facilitatorDBStatus = $facilitatorDBQueryResult["facilitatorStatus"];
  $facilitatorDBPhone = $facilitatorDBQueryResult["facilitatorPhone"];
  $facilitatorDBGender = $facilitatorDBQueryResult["facilitatorGender"];
  $facilitatorDBDesignation = $facilitatorDBQueryResult["facilitatorDesignation"];
}
?>
