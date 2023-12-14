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

$sql = "SELECT COUNT('sales_id') FROM `sales`;";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$sales = $row["COUNT('sales_id')"];

//====================================Average Monthly Sales Of This Month====================================================================================

$currentMonth = date('n');

$sql = "SELECT COUNT('sales_id') FROM `sales` WHERE MONTH(`date`) =" . $currentMonth . ";";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$this_month_sales = $row["COUNT('sales_id')"];

$this_month_sales_percentage = number_format(($this_month_sales / $sales) * 100, 2);

//====================================Average Monthly Profit Of This Month====================================================================================

// $currentMonth = date('n');
$sql = "SELECT * FROM sales
INNER JOIN stocks ON sales.stock_id=stocks.s_id WHERE MONTH(`date`) = '" . $currentMonth . "';";
$sql2 = "SELECT * FROM sales
INNER JOIN stocks ON sales.stock_id=stocks.s_id;";

$result = $conn->query($sql);
$result2 = $conn->query($sql2);

$full_profit = 0;
while ($row2 = $result2->fetch_assoc()) {
	$full_profit += ($row2['sale_price'] - $row2['buy_price']) * $row2['qty'];
}

$this_month_profit = 0;
while ($row = $result->fetch_assoc()) {
	$this_month_profit += ($row['sale_price'] - $row['buy_price']) * $row['qty'];
}

$this_month_profit_percentage = number_format(($this_month_profit / $full_profit) * 100, 2);

//=================================Monthly cost==========================================================================================

$sql = "SELECT * FROM stocks;";
$result = $conn->query($sql);
$full_cost = 0;
while ($row = $result->fetch_assoc()) {
	$full_cost += $row["buy_price"] * $row["quantity"];
}

$sql2 = "SELECT * FROM stocks WHERE MONTH(`add_date`) = '" . $currentMonth . "';";
$result2 = $conn->query($sql2);
$this_month_cost = 0;
while ($row2 = $result2->fetch_assoc()) {
	$this_month_cost += $row2["buy_price"] * $row2["quantity"];
}

//=====================================Best selling product============================================================================

$sql = "SELECT *,SUM(sales.qty) AS `total_sales` FROM products
INNER JOIN stocks ON products.pro_id=stocks.pro_id
INNER JOIN sales ON stocks.s_id=sales.stock_id
GROUP BY sales.stock_id
ORDER BY total_sales DESC
LIMIT 1;";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$best_selling_product = $row['pro_name'];



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
				<span class="text-muted">Average Monthly Sales Percentage Of This Month</span>
					<h3 class="mb-0 fw-bold">$this_month_sales_percentage%</h3>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card mt-0">
			<div class="row">
				<div class="col-md-12 border-left text-center pt-2">
				<span class="text-muted">Total Profit All the time</span>
					<h3 class="mb-0 fw-bold">Rs. $full_profit</h3>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card mt-0 ">
			<div class="row">
				<div class="col-md-12 border-left text-center pt-2">
				<span class="text-muted">This Month Average Profit Percentage</span>
					<h3 class="mb-0 fw-bold">$this_month_profit_percentage%</h3>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card mt-0 ">
			<div class="row">
				<div class="col-md-12 border-left text-center pt-2">
				<span class="text-muted">This Month Profit</span>
					<h3 class="mb-0 fw-bold">$this_month_profit</h3>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card mt-0">
			<div class="row">
				<div class="col-md-12 border-left text-center pt-2">
				<span class="text-muted">This Month Average Cost</span>
					<h3 class="mb-0 fw-bold">Rs. $this_month_cost</h3>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="card mt-0">
			<div class="row">
				<div class="col-md-12 border-left text-center pt-2">
				<span class="text-muted">Total Cost</span>
					<h3 class="mb-0 fw-bold">Rs. $full_cost</h3>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-3 offset-md-0">
		<div class="card mt-0">
			<div class="row">
				<div class="col-md-12 border-left text-center pt-2">
				<span class="text-muted">Best Selling Product</span>
					<h3 class="mb-0 fw-bold">$best_selling_product</h3>
				</div>
			</div>
		</div>
	</div>
</div>

 

</section>


EOT;

echo <<<EOT
</main>
EOT;

html_footer();
