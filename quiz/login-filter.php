<?php
// include "assets/lib/db.class.php";
// include "assets/lib/config.php";
// include "assets/lib/functions.php";
// include "assets/lib/validation.php";
// include "assets/lib/alerts.php";
?>

<?php
//Get Level ID from DB
if (!empty($_POST["groupID"])) {
  $groupID = $_POST["groupID"];
?>
  <option selected>Select your Name</option>
  <?php
  $groupQuery = DB::query("SELECT * FROM learner WHERE groupID=%i AND learnerStatus =%i", $groupID,2);
  foreach ($groupQuery as $groupResult) {
    $learnerID = $groupResult["learnerID"];
    $learnerName = $groupResult["learnerName"];
  ?>
    <option value="<?php echo $learnerID ?>"><?php echo $learnerName ?></option>
<?php
  }
}
?>

<?php
//Get Student ID from DB
if (!empty($_POST["learnerID"])) {
?>
  <button class="btn btn-primary" type="submit" name="login">Log In <i class="fas fa-sign-in-alt ms-1"></i></button>
<?php
}
?>