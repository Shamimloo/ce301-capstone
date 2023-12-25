<!---------- Header Include ---------->
<?php
$pageName = "Info Page";
include 'assets/templates/quiz/header.php';

if(!isLearnerLoggedIn()){ 
    jsRedirect(SITE_URL . "quizzes-login");
}

jsRedirect(SITE_URL . "quizzes-login");

?>

<!---------- Main Content ---------->

<body id="body">

  <!---------- Information ---------->
  <section id="info-list">
    <div class="container">
      <div class="row d-flex justify-content-center">
        <?php
        $pageQuery = DB::query("SELECT * FROM `page` WHERE pageID=%i", $_GET["pageID"]);
        foreach ($pageQuery as $pageResult) {
          $pageID = $pageResult["pageID"];
          $pageName = $pageResult["pageName"];
          $pageDescription = $pageResult["pageDescription"];
          $pageImage = $pageResult["pageImage"];
        ?>
          <div class="info-container col-lg-7 col-md-12 col-sm-12 col-xs-12">
            <div class="card mx-4">
              <div class="row">
                <div class="col-12 card-body text-center mx-5 mt-4">
                  <img src="assets/images/infopage/<?php echo $pageImage ?>" style='width:300px; height:300px;' class='rounded'>
                </div>
                <div class="col-12 card-body mx-5 mb-4">
                  <h1><?php echo $pageName; ?></h1>
                  <p><?php echo $pageDescription; ?></p>
                  </a>
                </div>
                <div class="col-12 card-body mx-5 mb-4 text-center">
                  <a href="<?php echo SITE_URL; ?>quizzes-info&categoryID=<?php echo $_GET["categoryID"]; ?>" class="btn btn-primary">Back</a>
                </div>
              </div>
            </div>
          </div>
        <?php
        }
        ?>
      </div>
    </div>
  </section>

  <!---------- Footer Include ---------->
  <?php
  include 'assets/templates/quiz/footer.php';
  ?>

</body>

</html>