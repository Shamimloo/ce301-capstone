<?php
//Define page name
$pageName = "Learner Groups";
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
        <a href="<?php echo SITE_URL . 'learnergroup-add' ?>">
          <button type="button" class="btn btn-primary">
            <i class="fa-solid fa-plus pr-10"></i>Add Learner Group
          </button>
        </a>
      </div>
      <div class="box mt-30">
        <div class="row filter-row">
          <?php
          $facilitatorOptions = array();
          $facilitatorQuery = DB::query("SELECT DISTINCT f.facilitatorName
                                         FROM learnerGroup lg
                                         INNER JOIN facilitator f ON lg.facilitatorID = f.facilitatorID");
          foreach ($facilitatorQuery as $facilitatorResult) {
            $facilitatorName = $facilitatorResult["facilitatorName"];
            $facilitatorOptions[] = $facilitatorName;
          }


          // PHP script to populate Learner Group Status dropdown
          $learnerGroupStatusOptions = array(
            "all" => "All Statuses",
            "2" => "Active",
            "1" => "Inactive"
          );
          ?>

          <!-- HTML form with dynamically populated dropdowns -->
          <div class="row filter-row">
            <!-- Filter 1: Facilitator -->
            <div class="col-lg-6 col-md-6 col-sm-12 mt-30">
              <label for="facilitatorFilter" class="labels">Facilitator</label>
              <br>
              <select class="form-select form-select-option" name="facilitatorFilter" id="facilitatorFilter" placeholder="Select Facilitator">
                <option disabled>Select Facilitator:</option>
                <option value="all">All Facilitators</option>
                <?php
                foreach ($facilitatorOptions as $facilitator) {
                  echo '<option value="' . $facilitator . '">' . $facilitator . '</option>';
                }
                ?>
              </select>
            </div>
            <!-- Filter 2: Learner Group Status -->
            <div class="col-lg-6 col-md-6 col-sm-12 mt-30">
              <label for="learnerGroupStatusFilter" class="labels">Learner Group Status</label>
              <br>
              <select class="form-select form-select-option" name="learnerGroupStatusFilter" id="learnerGroupStatusFilter" placeholder="Select Learner Group Status">
                <option value="">All Statuses</option>
                <option value="1">Inactive</option>
                <option value="2">Active</option>
              </select>
            </div>
          </div>

        </div>
        <div class="table-responsive">
          <div class="mt-30 col-12">
            <table id="learnerGroupTable" class="table table-vcenter text-nowrap table-bordered border-bottom dt-responsive" width="100%">
              <thead>
                <tr>
                  <th class="border-bottom-0 text-center">ID</th>
                  <th class="border-bottom-0 text-center">Name</th>
                  <th class="border-bottom-0 text-center">Short Name</th>
                  <th class="border-bottom-0 text-center">Capacity</th>
                  <th class="border-bottom-0 text-center">Facilitator</th>
                  <th class="border-bottom-0 text-center">Status</th>
                  <th class="border-bottom-0 text-center">Actions</th>
                </tr>
              </thead>
              <tbody id="result">
                <?php
                // Query learner groups
                $learnerGroupDBQuery = DB::query("SELECT * FROM learnerGroup WHERE groupStatus > %i ORDER BY groupName", 0);
                foreach ($learnerGroupDBQuery as $learnerGroupDBQueryResult) {
                  $learnerGroupID = $learnerGroupDBQueryResult["groupID"];
                  $learnerGroupName = $learnerGroupDBQueryResult["groupName"];
                  $learnerGroupShortName = $learnerGroupDBQueryResult["groupShortName"];
                  $learnerGroupCapacity = $learnerGroupDBQueryResult["groupCapacity"]; // Add this line
                  $learnerGroupFacilitatorID = $learnerGroupDBQueryResult["facilitatorID"]; // Add this line
                  $learnerGroupStatus = $learnerGroupDBQueryResult["groupStatus"];
                ?>
                  <tr>
                    <td class="text-center actions"><?php echo $learnerGroupID ?></td>
                    <td class="text-center actions"><?php echo $learnerGroupName ?></td>
                    <td class="text-center actions"><?php echo $learnerGroupShortName ?></td>
                    <td class="text-center actions"><?php echo $learnerGroupCapacity ?></td> <!-- Add this line -->
                    <td class="text-center actions">
                      <?php
                      // Query the facilitator's name based on facilitatorID
                      $facilitatorDBQuery = DB::query("SELECT facilitatorName FROM facilitator WHERE facilitatorID=%i", $learnerGroupFacilitatorID);
                      $facilitatorName = $facilitatorDBQuery[0]["facilitatorName"];
                      echo $facilitatorName;
                      ?>
                    </td>
                    <td class="text-center actions">
                      <?php
                      // Define status labels
                      $statusLabels = array(
                        1 => '<span class="badge badge-danger">Inactive</span>',
                        2 => '<span class="badge badge-success">Active</span>'
                      );

                      // Get the status label based on the status code
                      $statusLabel = $statusLabels[$learnerGroupStatus];

                      echo $statusLabel;
                      ?>
                    </td>

                    <td class="text-center actions">
                      <?php
                      if ($learnerGroupStatus == 1) {
                        echo '<a title="Activate Group" href="' . SITE_URL . 'learnergroup-activate&groupID=' . $learnerGroupID . '" onclick="return confirm(`Activate Group?`);"><i class="fa-solid fa-check mx-3"></i></a>';
                      } else {
                        echo '<a title="Deactivate Group" href="' . SITE_URL . 'learnergroup-deactivate&groupID=' . $learnerGroupID . '" onclick="return confirm(`Deactivate Group?`);"><i class="fa-solid fa-xmark mx-3"></i></a>';
                      }
                      ?>
                      <a href="<?php echo SITE_URL . 'learnergroup-edit&groupID=' . $learnerGroupID; ?>"><i class="fa-solid fa-pen mx-3"></i></a>
                      <a href="<?php echo SITE_URL . 'learnergroup-delete&groupID=' . $learnerGroupID; ?>" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa-solid fa-trash-can mx-3"></i></a>
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

  <script type="text/javascript">
    // Datatable
    $(document).ready(function() {
      var learnerGroupTable = $('#learnerGroupTable').DataTable({
        responsive: true,
        columnDefs: [{
          "orderable": false,
          "targets": [6] // Adjust the target column index as needed
        }]
      });

      // Event listener for Facilitator dropdown change
      $('#facilitatorFilter').on('change', function() {
        var facilitatorValue = $(this).val();
        learnerGroupTable.column(4).search(facilitatorValue).draw();
      });

      // Event listener for Learner Group Status dropdown change
      $('#learnerGroupStatusFilter').on('change', function() {
        const selectedValue = $(this).val();
        // Use DataTable's column().search() to filter the data
        learnerGroupTable.column(5).search(selectedValue).draw();
      });
    });
  </script>

</body>

</html>