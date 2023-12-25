<?php
// include "../assets/lib/db.class.php";
// include "../assets/lib/config.php";
// include "../assets/lib/functions.php";
// include "../assets/lib/validation.php";
// include "../assets/lib/alerts.php";
// include "../assets/lib/database.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!---------- Page Title ---------->
  <title>
    <?php echo SITE_TITLE; ?> | <?php echo ucwords($pageName); ?>
  </title>

  <!---------- Page Icon  ---------->
  <link rel="shortcut icon" type="image/x-icon" href="assets/images/favicon.ico">

  <!---------- Google Font  ---------->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

  <!---------- Bootstrap CSS ---------->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.2/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/css/bootstrap-multiselect.css" integrity="sha512-5EhW8Jv2zXGGt7yHMK/zYJwibhdfg/EiEMeLZBkpeHtAaGt/vYj1LgQJEmL9LvFdeH1dxsgczwOa8p5oJcDXpw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.js" integrity="sha512-cJiPd6wKoz6hzxR6+zsq82yLStJYsTzI9D9XvyGGoZ+bBbvZgBQ7xuEsFy+7VGVuppgJClPN7VbTE9XZdy7Vaw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <!---------- Jquery ---------->
  <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>

  <!---------- Multi Select ---------->
  <link rel="stylesheet" href="assets/css/dashboard/jquery-multiselect.css" />

  <!---------- SweetAlert ---------->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.8/sweetalert2.css" rel="stylesheet">

  <!---------- Font Awesome ---------->
  <script src="https://kit.fontawesome.com/21a4901ceb.js" crossorigin="anonymous"></script>

  <!---------- Box Icons ---------->
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>

  <!---------- DataTables CSS ---------->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/datetime/1.2.0/css/dataTables.dateTime.min.css" rel="stylesheet">

  <!---------- CK Editor ---------->
  <script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>

  <!---------- Application CSS ---------->
  <link rel="stylesheet" href="assets/css/dashboard/grid.css">
  <link rel="stylesheet" href="assets/css/dashboard/style.css">
  <link rel="stylesheet" href="assets/css/dashboard/responsive.css">

  <!---------- QR Code ---------->
  <script src="assets/js/dashboard/easy.qrcode.min.js" type="text/javascript" charset="utf-8"></script>


</head>