<!---------- Header Include ---------->
<?php
$pageName = "Login";
//Include template files
include 'assets/templates/quiz/header.php';

//Clear & Destroy all sessions
session_unset(); // remove all session variables
session_destroy(); // destroy the session

// Start the session
session_start();
$companyID = 1;



//pageID = 4 , categoryID = 1, quizID = 1

if (isset($_POST["login"])) {
  $groupID = $_POST["group"];
  $learnerID = $_POST["learnerID"];

  //Store pageID inside
  $_SESSION["pageID"] = $_GET["pageID"];

  $groupName = DB::queryFirstField("SELECT learnerGroup.groupName FROM learnerGroup WHERE groupID=%i", $groupID);

  $learnerName = DB::queryFirstField("SELECT learner.learnerName FROM learner WHERE learnerID=%i", $learnerID);

  loginLearnerSession($groupID, $groupName, $learnerID, $learnerName);

  // $_GET on QR code pageID and categoryID
  if (isset($_GET["pageID"])) {
    // $_SESSION["categoryID"] = $_GET["categoryID"];

    $_SESSION["categoryID"] = DB::queryFirstField("SELECT categoryID FROM `page` WHERE pageID=%i ", $_SESSION["pageID"]);
    $_SESSION["quizID"] = DB::queryFirstField("SELECT quizID FROM quizCategory WHERE categoryID=%i", $_SESSION["categoryID"]); //remember to add status here

    if (!empty($_SESSION["pageID"]) && !empty($_SESSION["quizID"]) && !empty($_SESSION["categoryID"])) {
      jsRedirect(SITE_URL . "quizzes-quiz-info");
    } else {
      sweetAlertTimerRedirect("Invalid Session", "Please Contact the Administrator.", "error", SITE_URL . "quizzes-404");
    }
  } else {
    sweetAlertTimerRedirect("Invalid Session", "Please Scan the QR code again.", "error", SITE_URL . "quizzes-404");
  }
}
?>

<!---------- Main Content ---------->

<body>

  <!---------- Log In page ---------->
  <div class="login-bg">
    <section id="card-container-login">
      <div class="container-md">
        <div class="row">
          <div class="col-12"></div>
          <div class="card-body-content">
            <div class="row">
              <div class="col-lg-6 col-md-10 col-sm-11 mx-auto">
                <div class="card">
                  <div class="login-logo text-center mb-5">
                    <img src="assets/images/site-logo.png" alt="logo" class="auth-logo">
                  </div>
                  <div class="card-body">
                    <form method="POST">
                      <div class="form-group form-login">
                        <!-- <label for="level" class="form-label">Primary</label> -->
                        <select class="form-control" name="group" onchange="getGroup(this.value);">
                          <option selected>Select your Learner Group</option>
                          <?php
                          $groupQuery = DB::query("SELECT * FROM `learnerGroup` WHERE groupStatus=%i and companyID= %i", 2, $companyID);
                          foreach ($groupQuery as $groupResult) {
                            $groupID = $groupResult["groupID"];
                            $groupName = $groupResult["groupName"];
                          ?>
                            <option value="<?php echo $groupID ?>"><?php echo $groupName ?></option>
                          <?php
                          }
                          ?>
                        </select>
                      </div>

                      <div class="form-group form-login">
                        <select id="learnerID" class="arrow-down form-control" name="learnerID" onchange="getName(this.value);">
                          <option selected>Select your Name</option>
                        </select>
                      </div>

                      <div class="form-group row text-center">
                        <div class="col-12">
                          <div class="mt-2" id="replace-button">
                            <button type="button" class="btn btn-primary" disabled>Log In <i class="fas fa-sign-in-alt ms-1"></i></button>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>


  <!---------- Footer Include ---------->
  <?php
  include 'assets/templates/quiz/footer.php';
  ?>

  <!---------- Ajax ---------->
  <script>
    function getGroup(groupID) {
      $.ajax({
        type: "POST",
        url: "<?php echo SITE_URL . 'quizzes-login-filter' ?>",
        data: {
          groupID: groupID,
        },
        success: function(data) {
          $("#learnerID").html(data);
        }
      });
    };

    function getName(learnerID) {
      $.ajax({
        type: "POST",
        url: "<?php echo SITE_URL . 'quizzes-login-filter' ?>",
        data: {
          learnerID: learnerID,
        },
        success: function(data) {
          $("#replace-button").html(data);
        }
      });
    };

    // function getClass(classID) {
    //   $.ajax({
    //     type: "POST",
    // url: "<?php echo SITE_URL . 'quizzes-login-filter' ?>",
    //     data: {
    //       classID: classID,
    //     },
    //     success: function(data) {
    //       $("#index").html(data);
    //     }
    //   });
    // };
  </script>

</body>

</html>