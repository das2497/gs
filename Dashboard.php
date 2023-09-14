<?php
require_once "config/session.php";
require_once "config/database.php";
require_once "layout/header.php";
require_once "layout/footer.php";

html_header("Dashboard");

$sql = "SELECT COUNT('pro_id') FROM `products`";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$products_C = $row["COUNT('pro_id')"];

$sql = "SELECT COUNT('cat_id') FROM `categories`";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$categories_c = $row["COUNT('cat_id')"];

$sql = "SELECT COUNT('sales_id') FROM `sales` WHERE YEAR(`date`) = " . date("Y") . " AND MONTH(`date`) =" . date("m") . ";";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$sales = $row["COUNT('sales_id')"];

echo <<<EOT

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="Dashboard.php">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </nav>
    </div>
    
    <section class="section dashboard">
      <div class="row">
		<div class="col-xxl-4 col-md-4">
			<div class="card info-card sales-card">
				<div class="card-body">
					<h5 class="card-title">Products <span>| Count</span></h5>
					<div class="d-flex align-items-center">
						<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
							<i class="bi bi-grid-3x3-gap"></i>
						</div>
						<div class="ps-3">
							<h6>$products_C</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-xxl-4 col-md-4">
			<div class="card info-card revenue-card">
				<div class="card-body">
					<h5 class="card-title">Categories <span>| Count</span></h5>
					<div class="d-flex align-items-center">
						<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
							<i class="bi bi-layers"></i>
						</div>
						<div class="ps-3">
							<h6>$categories_c</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xxl-4 col-md-4">
			<div class="card info-card revenue-card">
				<div class="card-body">
					<h5 class="card-title">Sales <span>| Month </span></h5>
					<div class="d-flex align-items-center">
						<div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
							<i class="bi bi-cart-dash"></i>
						</div>
						<div class="ps-3">
							<h6>$sales</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
      </div>
    </div> 

	<div class="row">
	<div class="col-md-3">
		<div class="card mt-0">
			<div class="row">
				<div class="col-md-12 border-left text-center pt-2">
				<span class="text-muted">Average Monthly Sales</span>
					<h3 class="mb-0 fw-bold">150</h3>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card mt-0 ">
			<div class="row">
				<div class="col-md-6">
					<div class="peity_bar_bad left text-center mt-2">
						<canvas width="50" height="24"></canvas>
						</span>
						<h6>+40%</h6>
					</div>
				</div>
				<div class="col-md-6 border-left text-center pt-2">
				<span class="text-muted">Average Monthly Profit</span>
					<h3 class="mb-0 fw-bold">Rs. 4560</h3>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card mt-0">
			<div class="row">
				<div class="col-md-6">
					<div class="peity_line_good left text-center mt-2">
						<canvas width="50" height="24"></canvas>
						</span>
						<h6>+60%</h6>
					</div>
				</div>
				<div class="col-md-6 border-left text-center pt-2">
				<span class="text-muted">Total Profit</span>
					<h3 class="mb-0 fw-bold">Rs. 5672</h3>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card mt-0">
			<div class="row">
				<div class="col-md-6">
					<div class="peity_bar_good left text-center mt-2">
					<canvas width="50" height="24"></canvas>
						<h6>+30%</h6>
					</div>
				</div>
				<div class="col-md-6 border-left text-center pt-2">
				<span class="text-muted">Average Monthly Cost</span>
					<h3 class="mb-0 fw-bold">Rs. 2560</h3>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card mt-0">
			<div class="row">
				<div class="col-md-6">
					<div class="peity_bar_good left text-center mt-2">
					<canvas width="50" height="24"></canvas>
						<h6>+30%</h6>
					</div>
				</div>
				<div class="col-md-6 border-left text-center pt-2">
				<span class="text-muted">Total Cost</span>
					<h3 class="mb-0 fw-bold">Rs. 2560</h3>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 offset-md-0">
		<h1 class="text-center">Products Selling Chart</h1>
		<canvas id="myPieChart"></canvas>		
	</div>
	<div class="col-md-3 offset-md-0">
		<div class="card mt-0">
			<div class="row">
				<div class="col-md-6">
					<div class="peity_bar_good left text-center mt-2">
					<canvas width="50" height="24"></canvas>
						<h6></h6>
					</div>
				</div>
				<div class="col-md-6 border-left text-center pt-2">
				<span class="text-muted">Best Selling Product</span>
					<h3 class="mb-0 fw-bold">Cement</h3>
				</div>
			</div>
		</div>
	</div>
</div>

    <script>
        // Data for the pie chart
        var data = {
            labels: ['Cement', 'Metal', 'Bricks'],
            datasets: [{
                data: [30, 40, 30], // Specify your data values here
                backgroundColor: ['#ff5d52', '#52ff74', '#52aeff'], 
            }]
        };

        // Get the canvas element
        var ctx = document.getElementById('myPieChart').getContext('2d');

        // Create the pie chart
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: data,
        });
    </script>

</section>


EOT;

echo <<<EOT
</main>
EOT;

html_footer();
