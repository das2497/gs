<?php


function html_header($active)
{

  $Dashboard = "collapsed";
  $Manage_Users = "collapsed";
  $Change_Password = "collapsed";
  $Categories = "collapsed";
  $Products = "collapsed";
  $Sales = "collapsed";
  $Sales_Report = "collapsed";
  $Sales_Report_Monthly  = "collapsed";
  $Stocks = "collapsed";
  $Stocks_Alert = "collapsed";
  $Stocks_Report = "collapsed";
  $Stocks_Report_Monthly  = "collapsed";

  switch ($active) {

    case "Dashboard":
      $Dashboard = "active";
      $active = "Dashboard";
      break;

    case "Manage_Users":
      $Manage_Users = "";
      $active = "Manage Users";
      break;

    case "Change_Password":
      $Change_Password = "active";
      $active = "Change Password";
      break;

    case "Categories":
      $Categories = "";
      $active = "Categories";
      break;

    case "Products":
      $Products = "";
      $active = "Products";
      break;

    case "Sales":
      $Sales = "";
      $active = "Sales";
      break;

    case "Sales_Report":
      $Sales_Report = "";
      $active = "Sales Report";
      break;

    case "Sales_Report_Monthly":
      $Sales_Report_Monthly = "";
      $active = "Sales Report Monthly";
      break;

    case "Stocks":
      $Stocks = "";
      $active = "Stocks";
      break;

    case "Stocks_Alert":
      $Stocks_Alert = "";
      $active = "Stocks_Alert";
      break;

    case "Stocks_Report":
      $Stocks_Report = "";
      $active = "Stocks_Report";
      break;

    case "Stocks_Report_Monthly":
      $Stocks_Report_Monthly = "";
      $active = "Stocks_Report_Monthly";
      break;
  }

  echo <<<EOT

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title> $active - Disara Trade Center</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
  <link href="assets/vendor/datatables/jquery.dataTables.min.css" rel="stylesheet">
  <link href="assets/vendor/datatables/jquery.dataTables.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- Vendor JS Files -->
<script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/chart.js/chart.umd.js"></script>
<script src="assets/vendor/echarts/echarts.min.js"></script>
<script src="assets/vendor/quill/quill.min.js"></script>
<script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="assets/vendor/tinymce/tinymce.min.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>

<script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>

</head>

<body>

  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="Dashboard.php" class="logo d-flex align-items-center">
        <img src="assets/img/logo.png" alt="">
        <span class="d-none d-lg-block">Disara Trade Center</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn">
      
      </i>
    </div>

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
      <div id="Notification"></div>
        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2">{$_SESSION['username']}</span>
          </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6>{$_SESSION['name']}</h6>
              <span>Store Admin</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <a class="dropdown-item d-flex align-items-center" href="Logout.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->
        </li><!-- End Profile Nav -->

      </ul>
    </nav><!-- End Icons Navigation -->

  </header><!-- End Header -->

  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link $Dashboard " href="Dashboard.php">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>
    
      <li class="nav-heading">User Management</li>

      <li class="nav-item">
        <a class="nav-link $Manage_Users" href="Manage_Users.php">
          <i class="bi bi-people"></i>
          <span>Manage Users</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link $Change_Password" href="Change_Password.php">
          <i class="bi bi-lock"></i>
          <span>Change Password</span>
        </a>
      </li>

      <li class="nav-heading">Category Management</li>

      <li class="nav-item">
        <a class="nav-link $Categories" href="Categorie.php">
          <i class="bi bi-list-task"></i>
          <span>Categories</spans>
        </a>
      </li>
      <li class="nav-heading">Product Management</li>
      <li class="nav-item">
        <a class="nav-link $Products" href="Products.php">
          <i class="bi bi-columns-gap"></i>
          <span>Products</span>
        </a>
      </li>
      <li class="nav-heading"> Sale Management</li>
      <li class="nav-item">
        <a class="nav-link $Sales" href="Sales.php">
          <i class="bi bi-cash-coin"></i>
          <span>Add Sales</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link $Sales_Report" href="Sales_Report.php">
          <i class="bi bi-file-earmark-text"></i>
          <span>Sales Report</span>
        </a>
      </li>
      <li class="nav-heading">Stock Management</li>
      <li class="nav-item">
        <a class="nav-link $Stocks" href="Stocks.php">
          <i class="bi bi-bricks"></i>
          <span>Add Stocks</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link $Stocks_Alert" href="Stocks_Alert.php">
          <i class="bi bi-exclamation-diamond"></i>
          <span>Stocks Alert</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link $Stocks_Report" href="Stocks_Report.php">
          <i class="bi bi-file-earmark-text"></i>
          <span>Stocks Report</span>
        </a>
      </li>
      <!-- End F.A.Q Page Nav -->
      <li class="nav-heading">Log Out</li>

      <li class="nav-item">
        <a class="nav-link collapsed" href="Logout.php">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Log Out</span>
        </a>
      </li><!-- End Login Page Nav --> 

    </ul>

  </aside><!-- End Sidebar-->

EOT;
}
