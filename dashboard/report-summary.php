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
          <!-- <div class="col-12"> -->
            <!---------- Filter 1 : Learner Group ---------->
            <div class="col-lg-4 col-md-4 col-sm-12 mt-30">
              <label for="reportGroupFilter" class="labels">Learner Group</label>
              <br>
              <select class="form-select form-select-option" name="reportGroupFilter" id="reportGroupFilter" placeholder="Select Group">
                <option disabled>Select Group: </option>
                <option value="all">All Groups</option>
                <?php
                $reportQueryGroup = DB::query("SELECT * from learnerGroup WHERE groupStatus=%i", 2);
                foreach ($reportQueryGroup as $reportQueryGroupResults) {
                  $reportQueryGroupName = $reportQueryGroupResults["groupShortName"];
                ?>
                  <option value="<?php echo $reportQueryGroupName; ?>"><?php echo $reportQueryGroupName; ?>
                  </option>
                <?php
                }
                ?>
              </select>
            </div>

            <!---------- Filter 2 : Start Date ---------->
            <div class="col-lg-4 col-md-4 col-sm-12 mt-30">
              <label for="reportStartFilter" class="labels">Start Date</label>
              <input type="text" name="reportStartFilter" id="reportStartFilter" placeholder="Select Start Date" class="form-control">
            </div>

            <!---------- Filter 3 : End Date ---------->
            <div class="col-lg-4 col-md-4 col-sm-12 mt-30">
              <label for="reportEndFilter" class="labels">End Date</label>
              <input type="text" name="reportEndFilter" id="reportEndFilter" placeholder="Select End Date" class="form-control">
            </div>
          <!-- </div> -->
          <div class="table-responsive">
            <div class="col-12">
              <br>
              <table id="reportTable" class="table table-vcenter text-nowrap table-bordered border-bottom">
                <thead>
                  <tr>
                    <th class="border-bottom-0 text-center">Group</th>
                    <th class="border-bottom-0 text-center">Learner</th>
                    <th class="border-bottom-0 text-center">Date</th>
                    <th class="border-bottom-0 text-center">Status</th>
                    <th class="border-bottom-0 text-center">Points</th>
                  </tr>
                </thead>
                <tbody id="reportTableBody">
                  <?php
                  //Query the entire report from database
                  $learnerScoreQuery = DB::query("SELECT learner.learnerName,learner.learnerID, learnerQuiz.learnerQuizStatus,learnerQuiz.learnerQuizID, MAX(learnerQuiz.learnerQuizScore) AS learnerQuizScore, DATE(learnerQuiz.learnerQuizDateTimeEnded) AS quizDateEnded, DATE(learnerQuiz.learnerQuizDateTimeStarted) AS quizDateStarted, learnerGroup.groupShortName 
                                FROM learnerQuiz 
                                INNER JOIN learner ON learnerQuiz.learnerID = learner.learnerID 
                                INNER JOIN learnerGroup ON learner.groupID = learnerGroup.groupID 
                                WHERE learnerGroup.groupStatus=%i 
                                GROUP BY learnerQuiz.learnerID, quizDateStarted
                                ORDER BY quizDateStarted DESC
                                ", 2);
                  foreach ($learnerScoreQuery as $learnerScoreResult) {
                    $reportQueryID = $learnerScoreResult["learnerQuizID"];
                    $reportQueryLearnerIndex = $learnerScoreResult['learnerID'];
                    $reportQueryLearnerName = $learnerScoreResult["learnerName"];
                    $reportQueryLearnerScore = $learnerScoreResult['learnerQuizScore'];
                    $reportQueryGroupName = $learnerScoreResult["groupShortName"];
                    $reportQueryDateTimeStart = $learnerScoreResult["quizDateStarted"];
                    $reportQueryDateTimeEnd = $learnerScoreResult["quizDateEnded"];
                    $reportQueryStatus = $learnerScoreResult["learnerQuizStatus"];
                  ?>
                    <tr>
                      <td class="text-center actions"><?php echo $reportQueryGroupName ?></td>
                      <td class="text-center actions"><?php echo $reportQueryLearnerName ?></td>
                      <td class="text-center actions"><?php if ($reportQueryDateTimeStart == null) {
                                                        echo ('-');
                                                      } else {
                                                        echo mySQLDate($reportQueryDateTimeStart);
                                                      } ?></td>
                      <td class="text-center actions"><?php if ($reportQueryStatus == 2) {
                                                        echo '<span class="badge badge-success">' . "Completed" . '</span>';
                                                      } elseif ($reportQueryStatus == 1) {
                                                        echo '<span class="badge badge-warning">' . "Timeout" . '</span>';
                                                      } elseif ($reportQueryStatus == 0) {
                                                        echo '<span class="badge badge-danger">' . "Incomplete" . '</span>';
                                                      } ?></td>
                      <td class="text-center actions"><?php echo $reportQueryLearnerScore ?></td>
                    </tr>
                  <?php
                  }
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <!---------- Total Score ---------->
                    <th colspan="4" style="text-align:right" class="px-1">Total Score: </th>
                    <th style="text-align:center" class="px-1"></th>
                  </tr>
                </tfoot>
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
      //Initialize variables
      var minDate, maxDate;

      // Custom filtering function
      $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
          var min = minDate.val();
          var max = maxDate.val();
          var date = new Date(data[2]);

          if (
            (min === null && max === null) ||
            (min === null && date <= max) ||
            (min <= date && max === null) ||
            (min <= date && date <= max)
          ) {
            return true;
          }
          return false;
        }
      );

      $(document).ready(function() {

        // Create date inputs
        minDate = new DateTime($('#reportStartFilter'), {
          format: 'MMMM Do YYYY'
        });
        maxDate = new DateTime($('#reportEndFilter'), {
          format: 'MMMM Do YYYY'
        });

        //Filter 1 - Group
        $('#reportGroupFilter').on('change', function() {
          if ((this).value == 'all') {
            table
              .columns(0)
              .search("")
              .draw();

          } else {
            table
              .columns(0)
              .search(this.value)
              .draw();
          }

        });

        // Filter 4 / 5 Date Range Filter
        $('#reportStartFilter, #reportEndFilter').on('change', function() {
          table.draw();
        });

        //Datetable
        var table = $('#reportTable').DataTable({
          responsive: true,

          //Order
          order: [
            [0, 'desc']
          ],

          //Define Columns
          columnDefs: [{
              "width": "15%",
              "targets": [0, 1, 3, 4]
            },
            {
              "width": "40%",
              "targets": [2]
            },
            {
              targets: [2],
              type: 'date'
            }
          ],

          //Summation of column 
          footerCallback: function(row, data, start, end, display) {
            var api = this.api();

            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
              return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };

            // Total over all pages
            total = api
              .column(4)
              .data()
              .reduce(function(a, b) {
                return intVal(a) + intVal(b);
              }, 0);

            // Total over this page
            pageTotal = api
              .column(4, {
                page: 'current'
              })
              .data()
              .reduce(function(a, b) {
                return intVal(a) + intVal(b);
              }, 0);

            // Update footer
            $(api.column(4).footer()).html(pageTotal);
          },
        });
      });
    </script>

</body>

</html>