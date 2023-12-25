<!---------- Header Include ---------->
<?php
//Define page name
$pageName = "report";
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
      <div class="box mt-30">
        <div class="row filter-row">
          <!---------- Filter 1 : House ---------->
          <div class="col-lg-2 col-md-4 col-sm-12 mt-30">
            <label for="reportHouseFilter" class="labels">House</label>
            <br>
            <select class="form-select form-select-option" name="reportHouseFilter" id="reportHouseFilter" placeholder="Select House">
              <option disabled>Select House: </option>
              <option value="all">All Houses</option>
              <?php
              $reportQueryHouse = DB::query("SELECT * from house WHERE houseStatus=%i", 2);
              foreach ($reportQueryHouse as $reportQueryHouseResults) {
                $reportQueryHouseName = $reportQueryHouseResults["houseShortName"];
              ?>
                <option value="<?php echo $reportQueryHouseName; ?>"><?php echo $reportQueryHouseName; ?>
                </option>
              <?php
              }
              ?>
            </select>
          </div>

          <!---------- Filter 2 : Level ---------->
          <div class="col-lg-2 col-md-4 col-sm-12 mt-30">
            <label for="reportLevelFilter" class="labels">Level</label>
            <br>
            <select class="form-select form-select-option" name="reportLevelFilter" id="reportLevelFilter" placeholder="Select Level">
              <option disabled>Select Level: </option>
              <option value="all">All Levels</option>
              <?php
              $reportQueryLevel = DB::query("SELECT * from `level` WHERE levelStatus=%i", 2);
              foreach ($reportQueryLevel as $reportQueryLevelResults) {
                $reportQueryLevelName = $reportQueryLevelResults["levelShortName"];
              ?>
                <option value="<?php echo $reportQueryLevelName; ?>"><?php echo $reportQueryLevelName; ?>
                </option>
              <?php
              }
              ?>
            </select>
          </div>


          <!---------- Filter 3 : Class ---------->
          <div class="col-lg-2 col-md-4 col-sm-12 mt-30">
            <label for="reportClassFilter" class="labels">Class</label>
            <br>
            <select class="form-select form-select-option" name="reportClassFilter" id="reportClassFilter" placeholder="Select Class">
              <option disabled>Select Class: </option>
              <option value="all">All Classes</option>
              <?php
              $reportQueryClass = DB::query("SELECT * from class WHERE classStatus=%i", 2);
              foreach ($reportQueryClass as $reportQueryClassResults) {
                $reportQueryClassName = $reportQueryClassResults["classShortName"];
              ?>
                <option value="<?php echo $reportQueryClassName; ?>"><?php echo $reportQueryClassName; ?>
                </option>
              <?php
              }
              ?>
            </select>
          </div>

          <!---------- Filter 4 : Start Date ---------->
          <div class="col-lg-3 col-md-6 col-sm-12 mt-30">
            <label for="reportStartFilter" class="labels">Start Date</label>
            <input type="text" name="reportStartFilter" id="reportStartFilter" placeholder="Select Start Date" class="form-control">
          </div>

          <!---------- Filter 5 : End Date ---------->
          <div class="col-lg-3 col-md-6 col-sm-12 mt-30">
            <label for="reportEndFilter" class="labels">End Date</label>
            <input type="text" name="reportEndFilter" id="reportEndFilter" placeholder="Select End Date" class="form-control">
          </div>
        </div>
        <div class="table-responsive">
          <div class="col-12">
            <br>
            <table id="reportTable" class="table table-vcenter text-nowrap table-bordered border-bottom">
              <thead>
                <tr>
                  <th class="border-bottom-0 text-center">House</th>
                  <th class="border-bottom-0 text-center">Level</th>
                  <th class="border-bottom-0 text-center">Class</th>
                  <th class="border-bottom-0 text-center">Index Number</th>
                  <th class="border-bottom-0 text-center">Date</th>
                  <th class="border-bottom-0 text-center">Status</th>
                  <th class="border-bottom-0 text-center">Points</th>
                </tr>
              </thead>
              <tbody id="reportTableBody">
                <?php
                //Query the entire report from database
                $studentScoreQuery = DB::query("SELECT student.studentID, studentQuiz.studentQuizStatus, student.studentIndex, studentQuiz.studentQuizID, MAX(studentQuiz.studentQuizScore) AS studentQuizScore, DATE(studentQuiz.studentQuizDateTimeEnded) AS quizDateEnded, DATE(studentQuiz.studentQuizDateTimeStarted) AS quizDateStarted, class.classShortName, `level`.levelShortName, house.houseShortName 
                                FROM studentQuiz 
                                INNER JOIN student ON studentQuiz.studentID = student.studentID 
                                INNER JOIN class ON student.classID = class.classID 
                                INNER JOIN house ON student.houseID = house.houseID 
                                INNER JOIN `level` ON class.levelID = `level`.levelID 
                                WHERE house.houseStatus=%i AND class.classStatus=%i 
                                GROUP BY studentQuiz.studentID, quizDateStarted
                                ORDER BY quizDateStarted DESC
                                ", 2, 2);
                foreach ($studentScoreQuery as $studentScoreResult) {
                  $reportQueryID = $studentScoreResult["studentQuizID"];
                  $reportQueryStudentIndex = $studentScoreResult['studentIndex'];
                  $reportQueryStudentScore = $studentScoreResult['studentQuizScore'];
                  $reportQueryClassName = $studentScoreResult["classShortName"];
                  $reportQueryHouseShortName = $studentScoreResult["houseShortName"];
                  $reportQueryDateTimeStart = $studentScoreResult["quizDateStarted"];
                  $reportQueryDateTimeEnd = $studentScoreResult["quizDateEnded"];
                  $reportQueryLevelShortName = $studentScoreResult["levelShortName"];
                  $reportQueryStatus = $studentScoreResult["studentQuizStatus"];
                  $studentIndex = $studentScoreResult["studentIndex"];
                ?>
                  <tr>
                    <td class="text-center actions"><?php echo $reportQueryHouseShortName ?></td>
                    <td class="text-center actions"><?php echo $reportQueryLevelShortName ?></td>
                    <td class="text-center actions"><?php echo $reportQueryClassName ?></td>
                    <td class="text-center actions"><?php echo $reportQueryStudentIndex ?></td>
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
                    <td class="text-center actions"><?php echo $reportQueryStudentScore ?></td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <!---------- Total Score ---------->
                  <th colspan="6" style="text-align:right" class="px-1">Total Score: </th>
                  <th style="text-align:center" class="px-1"></th>
                </tr>
              </tfoot>
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
    //Initialize variables
    var minDate, maxDate;

    // Custom filtering function
    $.fn.dataTable.ext.search.push(
      function(settings, data, dataIndex) {
        var min = minDate.val();
        var max = maxDate.val();
        var date = new Date(data[4]);

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

      //Filter 1 - Class
      $('#reportClassFilter').on('change', function() {
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

      //Filter 2 - House
      $('#reportHouseFilter').on('change', function() {
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

      //Filter 3 - Level
      $('#reportLevelFilter').on('change', function() {
        if ((this).value == 'all') {
          table
            .columns(1)
            .search("")
            .draw();

        } else {
          table
            .columns(1)
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
            "width": "14%",
            "targets": [0, 1, 2, 3, 4]
          },
          {
            "width": "15%",
            "targets": [5, 6]
          },
          {
            targets: [0],
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
            .column(6)
            .data()
            .reduce(function(a, b) {
              return intVal(a) + intVal(b);
            }, 0);

          // Total over this page
          pageTotal = api
            .column(6, {
              page: 'current'
            })
            .data()
            .reduce(function(a, b) {
              return intVal(a) + intVal(b);
            }, 0);

          // Update footer
          $(api.column(6).footer()).html(pageTotal);
        },
      });
    });
  </script>
</body>

</html>