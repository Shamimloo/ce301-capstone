<!---------- Header Include ---------->
<?php
//Define page name
$pageName = "Categories";
//Include Header
include 'assets/templates/dashboard/header.php';
include 'assets/templates/dashboard/auth/header.php';

//Check if admin, if not then redirect to dashboard
// if (!isAdmin()) {
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
        <a href="<?php echo SITE_URL . 'category-add' ?>">
          <button type="button" class="btn btn-primary">
            <i class="fa-solid fa-plus pr-10"></i>Add Category
          </button>
        </a>
      </div>
      <div class="box mt-30">
        <div class="table-responsive">
          <div class="mt-30 col-12">
            <!-- Create category Table -->
            <table id="categoryTable" class="table table-vcenter text-nowrap table-bordered border-bottom dt-responsive" width="100%">
              <thead>
                <tr>
                  <th class="border-bottom-0 text-center">Category</th> <!-- Change "Subject" to "Category" -->
                  <th class="border-bottom-0 text-center">No. of questions</th>
                  <th class="border-bottom-0 text-center">Qns Updated</th>
                  <th class="border-bottom-0 text-center">Status</th>
                  <th class="border-bottom-0 text-center">Actions</th>
                </tr>
              </thead>
              <tbody id="quizTableBody">
                <?php
                // Query all categories active and inactive
                $category = DB::query("SELECT * FROM `category` WHERE categoryStatus>%i ORDER BY categoryDateCreated DESC", 0); // Change "subject" to "category"
                foreach ($category as $categoryQuery) {
                  $queryCategoryID = $categoryQuery["categoryID"]; // Change "subjectID" to "categoryID"
                  $queryCategoryName = $categoryQuery["categoryName"]; // Change "subjectName" to "categoryName"
                  $queryCategoryDateCreated = $categoryQuery["categoryDateCreated"]; // Change "subjectDateCreated" to "categoryDateCreated"
                  $queryCategoryStatus = $categoryQuery["categoryStatus"]; // Change "subjectStatus" to "categoryStatus"
                ?>
                  <tr>
                    <td class="text-center">
                      <?php echo '<a href="' . SITE_URL . 'category-edit&categoryID=' . $queryCategoryID . '">' . $queryCategoryName . '</a>' ?>
                    </td>
                    <td class="text-center">
                      <?php
                      DB::query("SELECT questionID FROM question WHERE categoryID=%i", $queryCategoryID); // Change "subjectID" to "categoryID"
                      echo DB::count();
                      ?>
                    </td>
                    <?php $lastDateUpdated = DB::queryFirstField("SELECT questionDateUpdated FROM question WHERE categoryID=%i ORDER BY questionDateUpdated DESC", $queryCategoryID); // Change "subjectID" to "categoryID"
                    ?>
                    <td class="text-center" title="<?php if (strlen($lastDateUpdated) > 10) {
                                                      echo $lastDateUpdated;
                                                    } ?>">
                      <?php
                      if (!$lastDateUpdated) {
                        echo "-";
                      } else {
                        echo mySQLDate($lastDateUpdated);
                      }
                      ?>
                    </td>
                    <td class="text-center">
                      <?php
                      if ($queryCategoryStatus == 2) { // Change "subjectStatus" to "categoryStatus"
                        echo '<span class="badge badge-success">Active</span>';
                      } elseif ($queryCategoryStatus == 1) { // Change "subjectStatus" to "categoryStatus"
                        echo '<span class="badge badge-warning">Inactive</span>';
                      }
                      ?>
                    </td>
                    <td class="text-center actions">
                      <?php
                      if ($queryCategoryStatus == 2) { // Change "subjectStatus" to "categoryStatus"
                        echo '<a title="Deactivate Category" href="' . SITE_URL . 'category-deactivate&categoryID=' . $queryCategoryID . '" onclick="return confirm(`' . 'Deactivate Category?' . '`);"><i class="fa-solid fa-xmark mx-3"></i></a>';
                      } else {
                        echo '<a title="Activate Category" href="' . SITE_URL . 'category-activate&categoryID=' . $queryCategoryID . '" onclick="return confirm(`' . 'Activate Category?' . '`);"><i class="fa-solid fa-check mx-3"></i></a>';
                      }
                      ?>
                      <a title="Edit Category" href="<?php echo SITE_URL . 'category-edit&categoryID=' . $queryCategoryID; ?>"><i class="fa-solid fa-pen mx-3"></i></a>
                      <a title="Delete Category" href="<?php echo SITE_URL . 'category-delete&categoryID=' . $queryCategoryID; ?>" onclick="return confirm('Are you sure you want to delete this item?');"><i class="fa-solid fa-trash-can mx-3"></i></a>
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
    <div class="overlay"></div>

    <!---------- Footer Include ---------->
    <?php
    include 'assets/templates/dashboard/footer.php';
    ?>

    <script>
      //Datatable
      $(document).ready(function() {
        $('#categoryTable').DataTable({
          ordering: true,
          responsive: true,
          order: [
            [2, 'desc']
          ],
          //Define column widths
          columnDefs: [{
            "width": "15%",
            "targets": [1, 3, 4]
          }, {
            "width": "20%",
            "targets": [0, 2]
          }, {
            "orderable": false,
            "targets": [4]
          }]
        });
      });
    </script>
</body>

</html>