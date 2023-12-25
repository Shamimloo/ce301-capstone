<?php
//Define page name
$pageName = "Facilitator";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';

//Check if HOD, if not then redirect to dashboard
// if (!isHOD()) {
//   jsRedirect(SITE_URL . 'admin');
// }
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
        <a href="<?php echo SITE_URL . 'facilitator-add' ?>">
          <button type="button" class="btn btn-primary">
            <i class="fa-solid fa-plus pr-10"></i>Add Facilitator
          </button>
        </a>
      </div>
      <div class="box mt-30">
        <div class="table-responsive">
          <div class="mt-30 col-12">
            <table id="facilitatorTable" class="table table-vcenter text-nowrap table-bordered border-bottom dt-responsive" width="100%">
              <thead>
                <tr>
                  <th class="border-bottom-0 text-center">Name</th>
                  <th class="border-bottom-0 text-center">Designation</th>
                  <th class="border-bottom-0 text-center">Admin</th>
                  <th class="border-bottom-0 text-center">Status</th>
                  <th class="border-bottom-0 text-center">Actions</th>
                </tr>
              </thead>
              <tbody id="result">
                <?php
                // Query active and inactive facilitators
                $facilitatorDBQuery = DB::query("SELECT * FROM facilitator WHERE facilitatorStatus>%i ORDER BY facilitatorName", 0);
                foreach ($facilitatorDBQuery as $facilitatorDBQueryResult) {
                    $facilitatorDBQueryID = $facilitatorDBQueryResult["facilitatorID"];
                    $facilitatorDBQueryName = $facilitatorDBQueryResult["facilitatorName"];
                    $facilitatorDBQueryPhone = $facilitatorDBQueryResult["facilitatorPhone"];
                    $facilitatorDBQueryPermission = $facilitatorDBQueryResult["facilitatorPermission"];
                    $facilitatorDBQueryStatus = $facilitatorDBQueryResult["facilitatorStatus"];
                    $facilitatorDBQueryDesignation = $facilitatorDBQueryResult["facilitatorDesignation"];
                ?>
                  <tr>
                    <td class="text-center actions"><?php echo $facilitatorDBQueryName ?></td>
                    <td class="text-center actions">
                      <?php if (!$facilitatorDBQueryDesignation) {
                        echo '<span class="badge badge-warning">' . "Not Available" . '</span>';
                      } elseif ($facilitatorDBQueryDesignation == 1) {
                        echo '<span class="badge badge-primary-light">' . "Facilitator" . '</span>';
                      } elseif ($facilitatorDBQueryDesignation == 2) {
                        echo '<span class="badge badge-success-light">' . "Head of Department" . '</span>';
                      }
                      ?>
                    </td>
                    <td class="text-center actions">
                      <?php if ($facilitatorDBQueryPermission == 1) {
                        echo '<span class="badge badge-success">' . "Admin" . '</span>';
                      } elseif ($facilitatorDBQueryPermission == 0) {
                        echo '<span class="badge badge-danger">' . "Not Admin" . '</span>';
                      }
                      ?>
                    </td>
                    <td class="text-center actions">
                      <?php if ($facilitatorDBQueryStatus == 2) {
                        echo '<span class="badge badge-success">' . "Active" . '</span>';
                      } elseif ($facilitatorDBQueryStatus == 1) {
                        echo '<span class="badge badge-danger">' . "Inactive" . '</span>';
                      } ?>
                    </td>
                    <td class="text-center actions">
                      <?php
                      if ($facilitatorDBQueryStatus == 2) {
                        echo '<a title="Deactivate Facilitator" href="' . SITE_URL . 'facilitator-deactivate&facilitatorID=' . $facilitatorDBQueryID . '" onclick="return confirm(`' . 'Deactivate Facilitator?' . '`);"><i class="fa-solid fa-xmark mx-3"></i></a>';
                      } elseif ($facilitatorDBQueryStatus == 1) {
                        echo '<a title="Activate Facilitator" href="' . SITE_URL . 'facilitator-activate&facilitatorID=' . $facilitatorDBQueryID . '" onclick="return confirm(`' . 'Activate Facilitator?' . '`);"><i class="fa-solid fa-check mx-3"></i></a>';
                      }
                      ?>
                      <a title="Edit Facilitator" href="<?php echo SITE_URL . 'facilitator-edit&facilitatorID=' . $facilitatorDBQueryID; ?>"><i class="fa-solid fa-pen mx-2"></i></a>
                      <a title="Delete Facilitator" href="<?php echo SITE_URL . 'facilitator-delete&facilitatorID=' . $facilitatorDBQueryID; ?>" onclick="return confirm('Are you sure you want to delete the facilitator?');"><i class="fa-solid fa-trash-can mx-2"></i></a>
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
    //Datatable 
    $(document).ready(function() {
      $('#studentLevelTable').DataTable({
        responsive: true,
        //Define column widths
        columnDefs: [{
          "width": "20%",
          "targets": [1, 2, 5, 0],
          "orderable": false,
          // "targets": [5]
          "targets": 'no-sort'
        }, {
          "width": "10%",
          "targets": [3, 4]
        }],
        order: [
          [4, 'asc']
        ]
      });
    });
  </script>
</body>

</html>