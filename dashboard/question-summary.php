<!---------- Header Include ---------->
<?php
//Define page name
$pageName = "questions";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';
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
        <a href="<?php echo SITE_URL . 'question-add' ?>">
          <button type="button" class="btn btn-primary">
            <i class="fa-solid fa-plus pr-10"></i>Add Question
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
              $categoryNameQuery = DB::query("SELECT categoryName FROM `category` WHERE categoryStatus>%i", 0);
              foreach ($categoryNameQuery as $categoryQuery) {
                $queryByCategory = $categoryQuery["categoryName"];
              ?>
                <option><?php echo $queryByCategory; ?></option>
              <?php
              }
              ?>
            </select>
          </div>
        </div>
        <div class="table-responsive">
          <div class="mt-30 col-12">
            <table id="questionTable" class="table table-vcenter text-nowrap table-bordered border-bottom dt-responsive" width="100%">
              <thead>
                <tr>
                  <th class="border-bottom-0 text-center">Title</th>
                  <th class="border-bottom-0 text-center">Time</th>
                  <th class="border-bottom-0 text-center">Type</th>
                  <th class="border-bottom-0 text-center">Category</th>
                  <th class="border-bottom-0 text-center">Updated</th>
                  <th class="border-bottom-0 text-center">Actions</th>
                </tr>
              </thead>
              <tbody id="questionTableBody">
                <?php
                $question = DB::query("SELECT `question`.*, `category`.categoryName FROM `question` INNER JOIN `category` ON `question`.categoryID = `category`.categoryID  WHERE `category`.categoryStatus>%i", 1);

                foreach ($question as $questionQuery) {
                  $queryQuestionID = $questionQuery["questionID"];
                  $queryQuestionTitle = $questionQuery["questionTitle"];
                  $queryQuestionDescription = $questionQuery["questionDescription"];
                  $queryQuestionTime = $questionQuery["questionTime"];
                  $queryQuestionType = $questionQuery["questionType"];
                  $queryQuestionDateUpdated = $questionQuery["questionDateUpdated"];
                  $queryQuestionScore = $questionQuery["questionScore"];
                  $queryCategoryName = $questionQuery["categoryName"];

                  if ($queryQuestionType == 1) {
                    $questionType = "MCQ(S)";
                  } elseif ($queryQuestionType == 2) {
                    $questionType = "MCQ(M)";
                  } elseif ($queryQuestionType == 3) {
                    $questionType = "T/F";
                  } elseif ($queryQuestionType == 4) {
                    $questionType = "IMG";
                  }
                ?>
                  <tr>
                    <td class="text-center" title="<?php if (strlen($queryQuestionTitle) > 20) {
                                                      echo $queryQuestionTitle;
                                                    } ?>">
                      <?php echo shortenString($queryQuestionTitle, 20); ?>
                    </td>

                    <td class="text-center">
                      <?php echo $queryQuestionTime; ?> s
                    </td>
                    <td class="text-center">
                      <?php echo $questionType; ?>
                    </td>
                    <td class="text-center">
                      <?php echo $queryCategoryName; ?>
                    </td>
                    <td class="text-center" title="<?php if (strlen($queryQuestionDateUpdated) > 10) {
                                                      echo $queryQuestionDateUpdated;
                                                    } ?>"><?php echo mySQLDate($queryQuestionDateUpdated); ?></td>
                    <td class="text-center actions">
                      <a title="Edit Question" href="
                                                <?php
                                                if ($queryQuestionType == 1) {
                                                  echo SITE_URL . 'question-edit-multiChoiceText&questionID=' . $queryQuestionID;
                                                } else if ($queryQuestionType == 2) {
                                                  echo SITE_URL . 'question-edit-multiAnswerText&questionID=' . $queryQuestionID;
                                                } else if ($queryQuestionType == 3) {
                                                  echo SITE_URL . 'question-edit-trueOrFalse&questionID=' . $queryQuestionID;
                                                } else if ($queryQuestionType == 4) {
                                                  echo SITE_URL . 'question-edit-multiChoiceImage&questionID=' . $queryQuestionID;
                                                }
                                                ?>">
                        <i class="fa-solid fa-pen mx-3"></i>
                      </a>
                      <a title="Delete Question" href="<?php echo SITE_URL . 'question-delete&questionID=' . $queryQuestionID; ?>" onclick="return confirm('Delete this question?');"><i class="fa-solid fa-trash-can mx-3"></i></a>
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
  <div class="overlay"></div>


  <!---------- Footer Include ---------->
  <?php
  include 'assets/templates/dashboard/footer.php';
  ?>
  <script>
    $(document).ready(function() {
      //Filter 1 - Category
      $('#viewByCategory').on('change', function() {
        if ($(this).value == 'all') {
          table
            .columns(3)
            .search("")
            .draw();
        } else {
          table
            .columns(3)
            .search(this.value)
            .draw();
        }
      });

      //Datatable
      var table = $('#questionTable').DataTable({
        ordering: true,
        responsive: true,
        order: [
          [5, 'desc']
        ],
        //Define column widths
        columnDefs: [{
          "width": "11%",
          "targets": [1, 2, 3, 4, 5]
        }, {
          "width": "30%",
          "targets": [0]
        }, {
          "orderable": false,
          "targets": [5]
        }],
        drawCallback: function(settings) {
          $('select[multiple]').multiselect(); // Reinitialize multiselect after every redraw
        }
      });
    });
  </script>

</body>

</html>