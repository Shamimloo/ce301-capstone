<?php
// Define page name
$pageName = "Learner";
// Include Header
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
        <a href="<?php echo SITE_URL . 'learner-add' ?>">
          <button type="button" class="btn btn-primary">
            <i class="fa-solid fa-plus pr-10"></i>Add Learner
          </button>
        </a>
      </div>
      <div class="box mt-30">
        <div class="row filter-row">
          <!---------- Filter 1 : Learner Group (Replace with your filter options) ---------->
          <div class="col-lg-6 col-md-6 col-sm-12 mt-30">
            <label for="learnerGroupFilter" class="labels">Learner Group</label>
            <br>
            <select class="form-select form-select-option" name="learnerGroupFilter" id="learnerGroupFilter" placeholder="Select Learner Group">
              <option disabled>Select Learner Group:</option>
              <option value="all">All Learner Groups</option>
              <?php
              // Replace with your database query to retrieve learner group data
              $learnerGroupQuery = DB::query("SELECT * from learnerGroup WHERE groupStatus=%i", 2);
              foreach ($learnerGroupQuery as $learnerGroupResult) {
                $learnerGroupName = $learnerGroupResult["groupName"];
              ?>
                <option value="<?php echo $learnerGroupName; ?>"><?php echo $learnerGroupName; ?></option>
              <?php
              }
              ?>
            </select>
          </div>
          <!-- Filter 2: Learner Status -->
          <div class="col-lg-6 col-md-6 col-sm-12 mt-30">
            <label for="learnerStatusFilter" class="labels">Learner Status</label>
            <br>
            <select class="form-select form-select-option" name="learnerStatusFilter" id="learnerStatusFilter" placeholder="Select Learner Status">
              <option disabled>Select Status:</option>
              <option value="all">All Statuses</option>
              <?php
              // Replace with your database query to retrieve learner status data
              $learnerStatusQuery = DB::query("SELECT DISTINCT learnerStatus FROM learner");
              foreach ($learnerStatusQuery as $statusResult) {
                $statusValue = $statusResult["learnerStatus"];
                $statusLabel = ($statusValue == 1) ? "Inactive" : "Active";
              ?>
                <option value="<?php echo $statusValue; ?>"><?php echo $statusLabel; ?></option>
              <?php
              }
              ?>
            </select>
          </div>


        </div>
        <div class="table-responsive">
          <div class="col-12">
            <br>
            <table id="learnerTable" class="table table-vcenter text-nowrap table-bordered border-bottom dt-responsive" width="100%">
              <thead>
                <tr>
                  <th class="border-bottom-0 text-center">Name</th>
                  <th class="border-bottom-0 text-center">ID</th>
                  <th class="border-bottom-0 text-center">Status</th>
                  <th class="border-bottom-0 text-center">Date Created</th>
                  <th class="border-bottom-0 text-center">Date Updated</th>
                  <th class="border-bottom-0 text-center">Learner Group</th>
                  <!-- Add more columns as needed -->
                  <th class="border-bottom-0 text-center">Actions</th>
                </tr>
              </thead>
              <tbody id="result">
                <?php
                // Replace with your database query to retrieve learner data
                $learnerQuery = DB::query("SELECT * FROM learner INNER JOIN learnerGroup ON learner.groupID = learnerGroup.groupID WHERE learner.learnerStatus>%i ORDER BY learner.learnerID", 0);
                foreach ($learnerQuery as $learnerResult) {
                  $learnerID = $learnerResult["learnerID"];
                  $learnerName = $learnerResult["learnerName"];
                  $learnerStatus = $learnerResult["learnerStatus"];
                  $learnerDateCreated = $learnerResult["learnerDateCreated"];
                  $learnerDateUpdated = $learnerResult["learnerDateUpdated"];
                  $learnerGroupName = $learnerResult["groupName"];
                ?>
                  <tr>
                    <td class="text-center actions"><?php echo $learnerName; ?></td>
                    <td class="text-center actions"><?php echo $learnerID; ?></td>
                    <td class="text-center actions">
                      <?php if ($learnerStatus == 2) {
                        echo '<span class="badge badge-success">' . "Active" . '</span>';
                      } elseif ($learnerStatus == 1) {
                        echo '<span class="badge badge-danger">' . "Inactive" . '</span>';
                      } ?>
                    </td>
                    <td class="text-center actions"><?php echo $learnerDateCreated; ?></td>
                    <td class="text-center actions"><?php echo $learnerDateUpdated; ?></td>
                    <td class="text-center actions"><?php echo $learnerGroupName; ?></td>
                    <!-- Add more columns as needed -->
                    <td class="text-center actions">
                      <?php
                      if ($learnerStatus == 2) {
                        echo '<a title="Deactivate Learner" href="' . SITE_URL . 'learner-deactivate&learnerID=' . $learnerID . '" onclick="return confirm(`' . 'Deactivate Learner?' . '`);"><i class="fa-solid fa-xmark mx-3"></i></a>';
                      } elseif ($learnerStatus == 1) {
                        echo '<a title="Activate Learner" href="' . SITE_URL . 'learner-activate&learnerID=' . $learnerID . '" onclick="return confirm(`' . 'Activate Learner?' . '`);"><i class="fa-solid fa-check mx-3"></i></a>';
                      }
                      ?>
                      <a title="Edit Learner" href="<?php echo SITE_URL . 'learner-edit&learnerID=' . $learnerID; ?>"><i class="fa-solid fa-pen mx-3"></i></a>
                      <a title="Delete Learner" href="<?php echo SITE_URL . 'learner-delete&learnerID=' . $learnerID; ?>" onclick="return confirm('Are you sure you want to delete the learner?');"><i class="fa-solid fa-trash-can mx-3"></i></a>
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
    $(document).ready(function() {
      // DataTable
      var table = $('#learnerTable').DataTable({
        responsive: true,
        // Define column widths
        columnDefs: [{
          "width": "20%",
          "targets": [0]
        }, {
          "width": "10%",
          "targets": [1]
        }, {
          "width": "15%",
          "targets": [3, 4]
        }, {
          "width": "15%",
          "targets": [5]
        }, {
          "orderable": true,
          "targets": [6]
        }],
        order: [
          [2, 'asc']
        ]
      });

      // Filter 1 - Learner Group
      $('#learnerGroupFilter').on('change', function() {
        if (this.value == 'all') {
          table
            .columns(5)
            .search("")
            .draw();
        } else {
          table
            .columns(5)
            .search(this.value)
            .draw();
        }
      });

      // Filter 2 - Learner Status
      $('#learnerStatusFilter').on('change', function() {
        if (this.value == 'all') {
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
    });
  </script>
</body>

</html>