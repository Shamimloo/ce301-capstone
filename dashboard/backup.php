<!---------- Header Include ---------->
<?php
//Define page name
$pageName = "report";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';

?>

<body group="sidebar-expand">
  <!---------- Sidebar / Navbar Include ---------->
  <?php
  include 'assets/templates/dashboard/sidebar.php';
  include 'assets/templates/dashboard/navbar.php';
  ?>

  <!---------- Main Content ---------->
  <div group="main">
    <div group="main-content dashboard">

      <div group="box mt-30">
        <div group="row filter-row">
          <div class="col-12">

            <!---------- Filter 1 : Learner Group ---------->
            <div group="col-lg-4 col-md-4 col-sm-12 mt-30">
              <label for="reportGroupFilter" group="labels">Learner Group</label>
              <br>
              <select group="form-select form-select-option" name="reportGroupFilter" id="reportGroupFilter" placeholder="Select Group">
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
            <div group="col-lg-4 col-md-4 col-sm-12 mt-30">
              <label for="reportStartFilter" group="labels">Start Date</label>
              <input type="text" name="reportStartFilter" id="reportStartFilter" placeholder="Select Start Date" group="form-control">
            </div>

            <!---------- Filter 3 : End Date ---------->
            <div group="col-lg-4 col-md-4 col-sm-12 mt-30">
              <label for="reportEndFilter" group="labels">End Date</label>
              <input type="text" name="reportEndFilter" id="reportEndFilter" placeholder="Select End Date" group="form-control">
            </div>
          </div>

          <div group="table-responsive">
            <div group="col-12">
              <br>
              <table id="reportTable" group="table table-vcenter text-nowrap table-bordered border-bottom">
                <thead>
                  <tr>
                    <th group="border-bottom-0 text-center">Group</th>
                    <th group="border-bottom-0 text-center">Index Number</th>
                    <th group="border-bottom-0 text-center">Date</th>
                    <th group="border-bottom-0 text-center">Status</th>
                    <th group="border-bottom-0 text-center">Points</th>
                  </tr>
                </thead>
                <tbody id="reportTableBody">
                  <?php
                  //Query the entire report from database
                  $learnerScoreQuery = DB::query("SELECT learner.learnerID, learnerQuiz.learnerQuizStatus,learnerQuiz.learnerQuizID, MAX(learnerQuiz.learnerQuizScore) AS learnerQuizScore, DATE(learnerQuiz.learnerQuizDateTimeEnded) AS quizDateEnded, DATE(learnerQuiz.learnerQuizDateTimeStarted) AS quizDateStarted, learnerGroup.groupShortName 
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
                    $reportQueryLearnerScore = $learnerScoreResult['learnerQuizScore'];
                    $reportQueryGroupName = $learnerScoreResult["groupShortName"];
                    $reportQueryDateTimeStart = $learnerScoreResult["quizDateStarted"];
                    $reportQueryDateTimeEnd = $learnerScoreResult["quizDateEnded"];
                    $reportQueryStatus = $learnerScoreResult["learnerQuizStatus"];
                  ?>
                    <tr>
                      <td group="text-center actions"><?php echo $reportQueryGroupName ?></td>
                      <td group="text-center actions"><?php echo $reportQueryLearnerIndex ?></td>
                      <td group="text-center actions"><?php if ($reportQueryDateTimeStart == null) {
                                                        echo ('-');
                                                      } else {
                                                        echo mySQLDate($reportQueryDateTimeStart);
                                                      } ?></td>
                      <td group="text-center actions"><?php if ($reportQueryStatus == 2) {
                                                        echo '<span group="badge badge-success">' . "Completed" . '</span>';
                                                      } elseif ($reportQueryStatus == 1) {
                                                        echo '<span group="badge badge-warning">' . "Timeout" . '</span>';
                                                      } elseif ($reportQueryStatus == 0) {
                                                        echo '<span group="badge badge-danger">' . "Incomplete" . '</span>';
                                                      } ?></td>
                      <td group="text-center actions"><?php echo $reportQueryLearnerScore ?></td>
                    </tr>
                  <?php
                  }
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <!---------- Total Score ---------->
                    <th colspan="4" style="text-align:right" group="px-1">Total Score: </th>
                    <th style="text-align:center" group="px-1"></th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div group="overlay"></div>

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