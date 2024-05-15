<?php
//Define page name
$pageName = "Info Pages";
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
        <a href="<?php echo SITE_URL . 'infopage-add' ?>">
          <button type="button" class="btn btn-primary mr-5">
            <i class="fa-solid fa-plus pr-10"></i> Add Info
          </button>
        </a>
        <form method="POST" action="<?php echo SITE_URL . 'infopage-massdelete'; ?>">
          <input type="hidden" name="pageIDs" id="pageIDs" value="">
          <button type="button" class="btn btn-primary" id="deleteSelectedBtn" name="deleteSelectedBtn">
            <i class="fa-solid fa-minus pr-10"></i> Delete Selected Pages
          </button>
        </form>
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
              // Query categories based on companyID
              $queryDBCategory = DB::query("SELECT * FROM `category` WHERE categoryStatus = %i AND companyID = %i", 2, $companyID);
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
            <table id="infoTable" class="table table-vcenter text-nowrap table-bordered border-bottom">
              <thead>
                <tr>
                  <th class="border-bottom-0 text-center">Picture</th>
                  <th class="border-bottom-0 text-center">QR</th>
                  <th class="border-bottom-0 text-center">Name</th>
                  <th class="border-bottom-0 text-center">Category</th>
                  <th class="border-bottom-0 text-center">Date Updated</th>
                  <th class="border-bottom-0 text-center">Status</th>
                  <th class="border-bottom-0 text-center">Actions</th>
                  <th class="border-bottom-0 text-center">Select</th>
                </tr>
              </thead>
              <tbody id="result">
                <?php
                $i = 1;
                //only query the active pages with and without published 
                $pageDBQuery = DB::query("SELECT `page`.*, `category`.categoryID, `category`.categoryName FROM `page` INNER JOIN `category` ON `page`.categoryID = `category`.categoryID WHERE `page`.pageStatus > %i ORDER BY `page`.pageDateUpdated DESC", 0);
                $countTotal = count($pageDBQuery);
                foreach ($pageDBQuery as $pageDBQueryResult) {
                  $pageDBQueryID = $pageDBQueryResult["pageID"];
                  $pageCategoryID = $pageDBQueryResult["categoryID"];
                  $pageDBQueryName = $pageDBQueryResult["pageName"];
                  $pageCategoryName = $pageDBQueryResult["categoryName"];
                  $pageDBQueryImage = $pageDBQueryResult["pageImage"];
                  $pageDBQueryDateCreated = $pageDBQueryResult["pageDateCreated"];
                  $pageDBQueryDateUpdated = $pageDBQueryResult["pageDateUpdated"];
                  $pageDBQueryStatus = $pageDBQueryResult["pageStatus"];
                ?>
                  <tr>
                    <td class="text-center"><?php echo '<img src="assets/images/infopage/' . $pageDBQueryImage . '" class="infopage-picture">'; ?></td>
                    <td class="text-center">
                      <div id="qrcode<?php echo $i; ?>" class="qrcode w-25 p-3 img-fluid infopage-picture"></div>

                      <script type="text/javascript">
                        // Options
                        var options_object = {
                          text: "<?php echo SITE_URL . 'quizzes-login&pageID=' . $pageDBQueryID; ?>",
                          logo: "assets/images/qr-code-logo.png",
                          logoHeight: 120,
                          logoWidth: 120,
                          logoBackgroundTransparent: true,
                        };

                        // Create QRCode Object
                        var qrcode = new QRCode(document.getElementById("qrcode<?php echo $i; ?>"), options_object);
                        //new QRCode(document.getElementById("qrcode<?php echo $i; ?>"), options);
                      </script>

                    </td>
                    <td class="text-center" title="<?php if (strlen($pageDBQueryName) > 10) {
                                                      echo $pageDBQueryName;
                                                    } ?>"><?php echo shortenString($pageDBQueryName, 10); ?></td>
                    <td class="text-center"><?php echo shortenString($pageCategoryName, 10); ?></td>
                    <td class="text-center" title="<?php if (strlen($pageDBQueryDateUpdated) > 10) {
                                                      echo $pageDBQueryDateUpdated;
                                                    } ?>"><?php echo mySQLDate($pageDBQueryDateUpdated) ?></td>
                    <td class="text-center"><?php if ($pageDBQueryStatus == 2) {
                                              echo '<span class="badge badge-success">' . "Published" . '</span>';
                                            } elseif ($pageDBQueryStatus == 1) {
                                              echo '<span class="badge badge-danger">' . "Draft" . '</span>';
                                            } ?></td>
                    <td class="text-center actions">
                      <?php
                      if ($pageDBQueryStatus == 2) {
                        echo '<a title="Deactivate Page" href="' . SITE_URL . 'infopage-unpublish&pageID=' . $pageDBQueryID . '" onclick="return confirm(`' . 'Deactivate Page?' . '`);"><i class="fa-solid fa-xmark mx-3"></i></a>';
                      } elseif ($pageDBQueryStatus == 1) {
                        echo '<a title="Publish Page" href="' . SITE_URL . 'infopage-publish&pageID=' . $pageDBQueryID . '" onclick="return confirm(`' . 'Publish Page?' . '`);"><i class="fa-solid fa-check mx-3"></i></a>';
                      }
                      ?><a title="Edit Page" href="<?php echo SITE_URL . 'infopage-edit&pageID=' . $pageDBQueryID; ?>"><i class="fa-solid fa-pen mx-3"></i></a>
                      <!-- <a title="Delete Page" href="<?php echo SITE_URL . 'infopage-delete&pageID=' . $pageDBQueryID; ?>" onclick="return confirm('Are you sure you want to delete the page?');"><i class="fa-solid fa-trash-can mx-3"></i></a> -->
                    </td>

                    

                    <td class="text-center">
                      <div class="form-check">
                        <input class="form-check-input massDelete" type="checkbox" style="margin: 0 auto;" value="<?php echo $pageDBQueryID; ?>" id="page-<?php echo $pageDBQueryID; ?>">
                        <label class="form-check-label" for="page-<?php echo $pageDBQueryID; ?>"></label>
                      </div>
                    </td>
                  </tr>
                <?php
                  $i++;
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
      //Filter 1 - Category
      $('#viewByCategory').on('change', function() {
        if ((this).value == 'all') {
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


      var table = $('#infoTable').DataTable({
        ordering: true,
        scrollx: true,
        order: [
          [5, 'desc']
        ],
        columnDefs: [{
          width: "5%",
          targets: [7]
        }, {
          width: "15%",
          targets: [2, 4, 5]
        }, {
          width: "10%",
          targets: [0, 1, 3]
        }, {
          width: "20%",
          targets: [6]
        }, {
          orderable: true,
          targets: [7]
        }],
        drawCallback: function(settings) {
          $('select[multiple]').multiselect(); // Reinitialize multiselect after every redraw
        }
      });
    });
  </script>
  <script>
    $('.qrcode').children().click(function() {
      var id = $(this).parent().attr('id');
      printJS({
        printable: id,
        type: 'html',
      })
    });

    // Mass Delete Pages
    $('#deleteSelectedBtn').click(function() {
      // Get IDs of selected pages
      var pageIDs = [];
      $('.massDelete:checked').each(function() {
        pageIDs.push($(this).val());
      });

      // Set the value of the pageIDs input field
      $('#pageIDs').val(pageIDs.join(','));

      // Confirm deletion and submit the form
      if (pageIDs.length > 0) {
        if (confirm("Are you sure you want to delete the selected pages?")) {
          // Set the value of the pageIDs input field
          $('#pageIDs').val(pageIDs.join(','));
          // Submit the form
          $('form').submit();
        } else {
          return false;
        }
      } else {
        alert("Please select at least one page to delete.");
        return false;
      }
    });
  </script>
  <script>
    $('select[multiple]').multiselect();
    $('select[multiple]').siblings('.saveChanges').click(function(event) {
      event.preventDefault();
      var selectedLvlArray = $(this).siblings('select[multiple]').val();
      var pageID = $(this).data("id");
      console.log(selectedLvlArray);
      console.log(pageID);
      $.ajax({
        url: '<?php echo SITE_URL . 'level-edit'; ?>',
        type: 'POST',
        data: {
          selectedLvlArray: selectedLvlArray,
          pageID: pageID
        },
        // cache: false,
        success: function(data) {
          alert('Success')
        }
      });
      return false;
    });
  </script>
</body>

</html>