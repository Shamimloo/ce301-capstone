<?php
//Define page name
$pageName = "Login";
//Include Header
include 'assets/templates/dashboard/header.php';

// If facilitator is logged in then redirect to dashboard straight
// If not redirect out to login page
// Testing the issue key
if (isset($_SESSION['cookie'])) {
  if (isFacilitatorLoggedIn()) {
    jsRedirect(SITE_ROOT . "?site=admin");
  }
}

?>

<body class="vh-100 login-bg">
  <!---------- Main Content ---------->
  <div class="container vh-100">
    <section class="login row h-100 d-flex align-items-center justify-content-center">
      <div class="col-6 col-md-7 col-sm-10 box my-auto">

        <a href="<?php echo SITE_ROOT; ?>?site=admin" class="form-header text-center">
          <img src="assets/images/site-logo.png" alt="site-logo" class="w-65">
        </a>

        <div class="box-header d-flex justify-content-between">
          <!---------- Box Header ---------->
          <div class="action-reg">
            <h4 class="fs-30">Login</h4>
          </div>

        </div>
        <div class="line"></div>

        <!---------- Box Body ---------->
        <div class="box-body">
          <div class="auth-content my-auto">

            <!---------- Login Form Submit ---------->
            <form class="mt-1 pt-2" method="POST">

              <?php
              // Trigger upon submission of form using button name = login
              // ISSET post with validation
              if (isset($_POST['login'])) {
                $email = filterInput($_POST['email']);
                $password = filterInput($_POST['password']);
                if (!isset($_POST["remember"])) {
                  $remember = 0;
                } else {
                  $remember = 1;
                }

                // Required fields not filled in
                if ($email == '' || $password == '') {
                  authErrorMsg("Please fill up all the required fields.");
                } else {
                  // Email invalid.
                  if (!isValidEmail($email)) {
                    authErrorMsg("Please fill up with a valid email.");
                  } else {
                    // Query facilitator from DB
                    $checkDBFacilitator = DB::query('SELECT * FROM facilitator WHERE facilitatorEmail =%s', $email);
                    $facilitatorDBExist = DB::count();
                    foreach ($checkDBFacilitator as $checkDBFacilitatorResult) {
                      $getDBFacilitatorID = $checkDBFacilitatorResult["facilitatorID"];
                      $getDBFacilitatorName = $checkDBFacilitatorResult["facilitatorName"];
                      $getDBFacilitatorEmail = $checkDBFacilitatorResult["facilitatorEmail"];
                      $getDBFacilitatorPassword = $checkDBFacilitatorResult["facilitatorPassword"];
                      $getDBFacilitatorDesignation = $checkDBFacilitatorResult["facilitatorDesignation"];
                      $getDBFacilitatorPermission = $checkDBFacilitatorResult["facilitatorPermission"];
                      $getDBFacilitatorStatus = $checkDBFacilitatorResult["facilitatorStatus"];
                      $getDBFacilitatorCompany = $checkDBFacilitatorResult["companyID"];
                    }
                    // Facilitator doesn't exist, redirect to login page
                    if (!$facilitatorDBExist) {
                      sweetAlert('error', 'Incorrect Email/Password', 'Please try again!',  '2500');
                    } elseif ($getDBFacilitatorStatus == 0 || $getDBFacilitatorStatus == 1) {
                      sweetAlert('error', 'Account Suspended', 'Please contact the HOD!',  '2500');
                    } else {
                      // Check if the password is correct, store session or cookie before redirecting to the main page
                      if (password_verify($password, $getDBFacilitatorPassword)) {
                        if ($remember) {
                          loginFacilitatorSession($getDBFacilitatorName, $getDBFacilitatorEmail, $getDBFacilitatorPermission, $getDBFacilitatorDesignation,$getDBFacilitatorCompany);
                          $_SESSION["cookie"] = 1;
                        } else {
                          loginFacilitatorSession($getDBFacilitatorName, $getDBFacilitatorEmail, $getDBFacilitatorPermission, $getDBFacilitatorDesignation, $getDBFacilitatorCompany);
                          $_SESSION["cookie"] = 0;
                        }
                        sweetAlertTimerRedirect('Facilitator Login', 'Facilitator successfully logged in!', 'success', (SITE_ROOT . "?site=admin"));
                      } else {
                        sweetAlert('error', 'Incorrect Email/Password', 'Please try again!',  '2500');
                      }
                    }
                  }
                }
              }
              ?>

              <!---------- Form fields ---------->
              <div class="mb-24">
                <label class="form-label mb-14">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Your email">
              </div>
              <div class="mb-16">
                <div class="d-flex align-items-start">
                  <div class="flex-grow-1">
                    <label class="form-label mb-14">Password</label>
                  </div>
                </div>
                <div class="input-group auth-pass-inputgroup">
                  <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" aria-label="Password" aria-describedby="password-addon">
                  <button tabindex=-1 class="btn shadow-none ms-0 pass-icon" type="button" id="password-addon"><i class="fa-regular fa-eye-slash"></i></button>
                </div>
              </div>
              <div class="row mb-29">
                <div class="col">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember[]" id="remember">
                    <label class="form-check-label fs-14" for="remember-check">
                      Remember me
                    </label>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <button class="btn btn-primary color-white w-100 waves-effect waves-light fs-18 font-w500" name="login" type="submit">Sign in</button>
              </div>
            </form>
            <!-- <div class="mt-37 text-center">
                            <p class="text-muted mb-0 fs-14">Don't have an account ? <a href="?site=register" class="text-primary fw-semibold"> Create Account </a> </p>
                        </div> -->
          </div>
        </div>
      </div>
    </section>
  </div>
  <div class="overlay"></div>

  <!---------- Footer Include ---------->
  <?php
  include 'assets/templates/dashboard/footer.php';
  ?>

  <script>
    $('.pass-icon').click(function() {
      $(this).children().toggleClass('fa-eye-slash');
      $(this).children().toggleClass('fa-eye');
    });
  </script>
</body>

</html>