<!---------- Header Include ---------->
<?php
$pageName = "Info List";
include 'assets/templates/quiz/header.php';

//Check if student is logged in 
if (!isLearnerLoggedIn()) {
  jsRedirect(SITE_URL . "quizzes-login");
}
?>

<!---------- Main Content ---------->

<body id="body">

  <!---------- Information List ---------->
  <section id="info-list">
    <div class="container-md">
      <h1 class="mb-3">Info List</h1>
      <div class="row d-flex justify-content-center">
        <?php
        $pageQuery = DB::query("SELECT * FROM `page` WHERE categoryID=%i", $_GET["categoryID"]);
        foreach ($pageQuery as $pageResult) {
          $pageID = $pageResult["pageID"];
          $pageName = $pageResult["pageName"];
          $pageImage = $pageResult["pageImage"];
        ?>
          <div class="col-lg-3 col-md-5 col-sm-9 col-xs-9 col-9 mx-auto mb-3 d-flex justify-content-center">
            <div class="card card-quiz align-text-bottom" style="background-image: url('assets/images/infopage/<?php echo $pageImage ?>')">
              <div class="card-body bottom">
                <a href="<?php echo SITE_URL; ?>quizzes-info-page&categoryID=<?php echo $_GET["categoryID"]; ?>&pageID=<?php echo $pageID; ?>" class="stretched-link card-link">
                  <h5 class="card-title"><?php echo $pageName . " " ?><i class="fa-sharp fa-solid fa-chevron-right"></i></h5>
                </a>
              </div>
            </div>
          </div>
        <?php
        }
        ?>
        <div class="col-10 card-body mx-5 mb-4">
          <a href="<?php echo SITE_URL . "quizzes" ?>" class="btn btn-primary">Back</a>
        </div>
      </div>
    </div>
  </section>

  <!---------- Footer Include ---------->
  <?php
  include 'assets/templates/quiz/footer.php';
  ?>

</body>

</html>