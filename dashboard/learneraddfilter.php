<?php
// include "assets/lib/db.class.php";
// include "assets/lib/config.php";
// include "assets/lib/functions.php";
// include "assets/lib/validation.php";
// include "assets/lib/alerts.php";
?>

<?php
//Add student for seleted class
if (!empty($_POST["classID"])) {
  $addClassID = $_POST["classID"];
?>
  <option selected>Select student index</option>

  <?php
  $classQuery = DB::query("SELECT classCapacity FROM class WHERE classID=%i", $addClassID);
  foreach ($classQuery as $classResult) {
    $classCapacity = $classResult["classCapacity"];
  }
  for ($i = 1; $i <= $classCapacity; $i++) {
    $studentName = "";
    $studentQuery = DB::query("SELECT * FROM student WHERE classID=%i AND studentIndex=%i", $addClassID, $i);
    foreach ($studentQuery as $studentResult) {
      $studentName = $studentResult["studentName"];
    }
    if ($studentName != "") {
  ?>
      <option value="<?php echo $i ?>" disabled><?php echo $i ?> - <?php echo $studentName ?></option>
    <?php
    } else {
    ?>
      <option value="<?php echo $i ?>"><?php echo $i ?></option>
<?php
    }
  }
}
?>