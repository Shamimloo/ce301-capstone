<?php
//Define page name
$pageName = "Register";
//Include Header
include 'assets/templates/dashboard/header.php';

//Initialize variables
$name = $email = '';
?>

<body class="vh-100 login-bg">
  <!---------- Main Content ---------->
  <div class="container vh-100">
    <section class="login row h-100 d-flex align-items-center justify-content-center">
      <div class="col-6 col-md-7 col-sm-10 box my-auto">
        <a href="?site=admin" class="form-header text-center">
          <img src="assets/images/site-logo.png" alt="site-logo" class="w-65">
        </a>
        <div class="box-header d-flex justify-content-between">

          <!---------- Box Header ---------->
          <div class="action-reg">
            <h4 class="fs-30">Register</h4>
          </div>

        </div>
        <div class="line"></div>

        <!---------- Box Body ---------->
        <div class="box-body">
          <div class="auth-content py-3">

            <!---------- Register Form ---------->
            <form class="" method="POST">

            <?php
// ISSET post with validation
if (isset($_POST['register'])) {
    // Filter Inputs
    $name = filterInput($_POST['name']);
    $email = filterInput($_POST['email']);
    $password = filterInput($_POST['password']);
    $cfmPassword = filterInput($_POST['cfmPassword']);

    // Check if required fields are empty
    if ($name == '' || $email == '' || $password == '' || $cfmPassword == '') {
        authErrorMsg("Please fill up all the required fields.");
    } else {
        // Check if email is valid
        if (!isValidEmail($email)) {
            authErrorMsg("Please fill up with a valid email.");
        } else {
            // Check if passwords are matching
            if ($password != $cfmPassword) {
                authErrorMsg("Passwords mismatched");
            } else {
                // Check if password meets minimum requirements
                if (!isValidPassword($password)) {
                    authErrorMsg("Password requirements not met. Please input 8 characters with 1 uppercase, 1 number, and 1 special character.");
                } else {
                    // Check if facilitator exists in the DB
                    $checkDBFacilitator = DB::query('SELECT * FROM facilitator WHERE facilitatorEmail = %s', $email);
                    $facilitatorDBExist = DB::count();
                    if ($facilitatorDBExist) {
                        authErrorMsg("Facilitator Exists. Please login.");
                    } else {
                        // Insert facilitator into DB
                        DB::startTransaction();
                        DB::insert('facilitator', [
                            'facilitatorName' => $name,
                            'facilitatorEmail' => $email,
                            'facilitatorPassword' => password_hash($password, PASSWORD_DEFAULT)
                        ]);

                        // Facilitator successfully added into DB
                        $success = DB::affectedRows();
                        if ($success) {
                            DB::commit();
                            sweetAlertTimerRedirect('Register Facilitator', 'Facilitator successfully added!', 'success', (SITE_URL . "login"));
                        } else {
                            DB::rollback();
                            sweetAlertTimerRedirect('Error occurred', 'Please try again!', 'error', (SITE_URL . "register"));
                        }
                    }
                }
            }
        }
    }
}
?>


              <!---------Form ---------->
              <div class="mb-3">
                <label class="form-label mb-14">User Name*</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" value="<?php echo $name ?>">
              </div>
              <div class="mb-3 mt-24">
                <label for="email" class="form-label mb-14">Email*</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" value="<?php echo $email ?>">
              </div>
              <div class="mb-3 mt-24">
                <div class="d-flex align-items-start">
                  <div class="flex-grow-1">
                    <label class="form-label mb-14">Password*</label>
                  </div>
                </div>

                <div class="input-group auth-pass-inputgroup">
                  <input type="password" class="form-control" placeholder="Enter password" id="password" name="password" aria-label="Password" aria-describedby="password-addon">
                  <button tabindex=-1 class="btn shadow-none ms-0 pass-icon" type="button" id="password-addon"><i class="far fa-eye-slash"></i></button>
                </div>
              </div>

              <div class="mb-3 mt-24">
                <div class="d-flex align-items-start">
                  <div class="flex-grow-1">
                    <label class="form-label mb-14">Confirm Password*</label>
                  </div>
                </div>

                <div class="input-group auth-pass-inputgroup">
                  <input type="password" class="form-control" placeholder="Enter password" name="cfmPassword" aria-label="Password" aria-describedby="password-addon">
                  <button tabindex=-1 class="btn shadow-none ms-0 pass-icon" type="button" id="password-addon2"><i class="far fa-eye-slash"></i></button>
                </div>
              </div>

              <div class="mb-3 mt-29">
                <button class="btn bg-primary color-white w-100 waves-effect waves-light fs-18 font-w500" name="register" type="submit">Create Account</button>
              </div>
            </form>

            <!---------- Login Redirect ---------->
            <div class="mt-29 text-center">
              <p class="text-muted mb-0 fs-14">Already have an account ? <a href="?site=login" class="text-primary fw-semibold"> Sign in </a> </p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</body>

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