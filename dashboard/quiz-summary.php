<?php
//Define page name
$pageName = "Quizzes";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';

if (isset($_SESSION['companyID'])) {
  $companyID = $_SESSION['companyID'];
} else {
  $companyID = $_COOKIE['companyID'];
}
?>

<body class="sidebar-expand">
  <!---------- Sidebar / Navbar Include ---------->
  <?php
  include 'assets/templates/dashboard/sidebar.php';
  include 'assets/templates/dashboard/navbar.php';
  ?>

  <!---------- Main Content ---------->
  <div class="main">
    <div class="main-content dashboard">
      <div class="d-flex flex-row justify-content-end mt-5">
        <a href="<?php echo SITE_URL . 'quiz-add' ?>">
          <button type="button" class="btn btn-primary">
            <i class="fa-solid fa-plus pr-10"></i>Add Quiz
          </button>
        </a>
      </div>
      <div class="filter-search box mt-30">
        <div class="row filter-row">
          <div class="col-12">
            <!---------- Filter 1 : Category ---------->
            <label for="viewByCategory" class="labels">Category</label>
            <br>
            <select class="form-select form-select-option" name="viewByCategory" id="viewByCategory" placeholder="Select Category">
              <option disabled>Select Category: </option>
              <option value="all">All Categories</option>
              <?php
              $categoryNameQuery = DB::query("SELECT DISTINCT category.categoryName
              FROM `category`
              INNER JOIN quizCategory ON category.categoryID = quizCategory.categoryID
              INNER JOIN quiz ON quizCategory.quizID = quiz.quizID
              WHERE category.categoryStatus > %i
              AND category.companyID = %i", 0, $companyID);
              foreach ($categoryNameQuery as $categoryQuery) {
                $queryByCategory = $categoryQuery["categoryName"];
              ?>
                <option><?php echo $queryByCategory; ?></option>
              <?php
              }
              ?>
            </select>
          </div>
          <div class="table-responsive">
            <div class="mt-30 col-12">
              <table id="quizTable" class="table table-vcenter text-nowrap table-bordered border-bottom dt-responsive" width="100%">
                <thead>
                  <tr>
                    <th class="border-bottom-0 text-center">Title</th>
                    <th class="border-bottom-0 text-center">Qns</th>
                    <th class="border-bottom-0 text-center">Category</th>
                    <th class="border-bottom-0 text-center">Updated</th>
                    <th class="border-bottom-0 text-center">Status</th>
                    <th class="border-bottom-0 text-center">Actions</th>
                  </tr>
                </thead>
                <tbody id="quizTableBody">
                  <?php
                  //Query from DB all quizzes
                  $quiz = DB::query("SELECT quiz.quizID, quiz.quizTitle, quiz.quizDateUpdated, quiz.quizQuestionQuantity, quiz.quizStatus, `category`.categoryName
                  FROM quiz
                  INNER JOIN quizCategory ON quiz.quizID = quizCategory.quizID
                  INNER JOIN category ON quizCategory.categoryID = category.categoryID
                  WHERE quiz.quizStatus > %i AND category.categoryStatus > %i /* Q */
                  AND category.companyID = %i
                  ORDER BY quiz.quizDateUpdated DESC", 0, 1, $companyID);
                  foreach ($quiz as $quizQuery) {
                    $queryQuizID = $quizQuery["quizID"];
                    $queryQuizTitle = $quizQuery["quizTitle"];
                    $queryQuizDateUpdated = $quizQuery["quizDateUpdated"];
                    $queryQuizQuestionQuantity = $quizQuery["quizQuestionQuantity"];
                    $queryQuizStatus = $quizQuery["quizStatus"];
                    $queryCategoryName = $quizQuery["categoryName"];
                  ?>
                    <tr>
                      <td class="text-center" title="<?php if (strlen($queryQuizTitle) > 20) {
                                                        echo $queryQuizTitle;
                                                      } ?>"><?php echo shortenString20($queryQuizTitle); ?></td>
                      <td class="text-center"><?php echo $queryQuizQuestionQuantity; ?></td>
                      <td class="text-center"><?php echo $queryCategoryName; ?></td>
                      <td class="text-center" title="<?php if (strlen($queryQuizDateUpdated) > 10) {
                                                        echo $queryQuizDateUpdated;
                                                      } ?>"><?php echo mySQLDate($queryQuizDateUpdated); ?> </td>
                      <td class="text-center">
                        <?php
                        if ($queryQuizStatus == 2) {
                          echo '<span class="badge badge-success">Active</span>';
                        } else {
                          echo '<span class="badge badge-warning">Inactive</span>';
                        }
                        ?>
                      </td>
                      <td class="text-center actions">
                        <?php
                        if ($queryQuizStatus == 2) {
                          echo '<a title="Deactivate Quiz" href="' . SITE_URL . 'quiz-unpublish&quizID=' . $queryQuizID . '" onclick="return confirm(`' . 'Deactivate Quiz?' . '`);"><i class="fa-solid fa-xmark mx-3"></i></a>';
                        } else {
                          echo '<a title="Activate Quiz" href="' . SITE_URL . 'quiz-publish&quizID=' . $queryQuizID . '" onclick="return confirm(`' . 'Activate Quiz?' . '`);"><i class="fa-solid fa-check mx-3"></i></a>';
                        }
                        ?>
                        <a title="Edit" href="<?php echo SITE_URL . 'quiz-edit&quizID=' . $queryQuizID; ?>"><i class="fa-solid fa-pen mx-3"></i></a>
                        <a title="Delete" href="<?php echo SITE_URL . 'quiz-delete&quizID=' . $queryQuizID; ?>" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa-solid fa-trash-can mx-3"></i></a>
                      </td>
                    </tr>
                  <?php
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="overlay"></div>

  <!---------- Footer Include ---------->
  <?php
  include 'assets/templates/dashboard/footer.php';
  ?>

  <script>
    $(document).ready(function() {
      //Filter 1 - Category
      $('#viewByCategory').on('change', function() {
        if ((this).value == 'all') {
          table
            .columns(2)
            .search("")
            .draw();
        } else {
          table
            .columns(2)
            .search(this.value)
            .draw();
        }
      });

      //Datatable
      var table = $('#quizTable').DataTable({
        ordering: true,
        responsive: true,
        order: [
          [4, 'desc']
        ],
        //Define column widths
        columnDefs: [{
          "width": "15%",
          "targets": [1, 2, 3,4,5]
        }, {
          "width": "25%",
          "targets": [0]
        }, {
          "orderable": false,
          "targets": [5]
        }]
      });
    });
  </script>

</body>

</html>