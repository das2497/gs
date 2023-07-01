<?php
require_once "config/session.php";
require_once "config/database.php";
require_once "layout/header.php";
require_once "layout/footer.php";

html_header("Dashboard");

$sql = "SELECT COUNT('pro_id') FROM `products`";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$products_C= $row["COUNT('pro_id')"];

$sql = "SELECT COUNT('cat_id') FROM `categories`";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$categories_c= $row["COUNT('cat_id')"];

$sql = "SELECT COUNT('sales_id') FROM `sales` WHERE YEAR(`date`) = ".date("Y")." AND MONTH(`date`) =".date("m"). ";";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$sales= $row["COUNT('sales_id')"];

echo<<<EOT

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

    </section>

    <br>
	<div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title" style="text-align: center;">View Product Details</h5>
          <br>
		  <div style="overflow-x:auto;">
		  <table class="table table-striped" style="text-align:center; white-space:nowrap;font-size: 15px; " id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th scope="col">Product Image</th>
				<th scope="col">Product ID</th>
                <th scope="col">Product Name</th>
              	<th scope="col">Category name</th>
              </tr>
            </thead>
            <tbody>
EOT;
$sql = "SELECT * FROM `products` LEFT JOIN `categories` ON products.categorie_id = categories.cat_id ORDER BY `products`.`pro_id` ASC LIMIT 5;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
// output data of each row
while($row = $result->fetch_assoc()) {

	echo "<tr> <td>"."<img class='img-fluid' width='50' height='50' src='uploads/{$row["media_file"]}'></td>
	<td>".$row["pro_id"]."</td>
	<td>".$row["pro_name"]."</td>
	<td>".$row["cat_name"]."</td></tr>";
}

echo"</tbody></table></div></div></div></div>";
} else {
echo "</tbody></table></div></div></div></div>";
}

echo<<<EOT

<div class="col-lg-4">
<div class="card">
  <div class="card-body">
	<h5 class="card-title" style="text-align: center;">View Sales Details</h5>
	<br>
	<div style="overflow-x:auto;">
	<table class="table table-striped" style="text-align:center; white-space:nowrap;font-size: 15px; " id="dataTable" width="100%" cellspacing="0">
	  <thead>
		<tr>
		  <th scope="col">Batch No</th>
		  <th scope="col">Product Image</th>
		  <th scope="col">Product Name</th>
		  <th scope="col">Quentity</th>
		  <th scope="col">Price</th>
		</tr>
	  </thead>
	  <tbody>
EOT;
$sql = "SELECT * FROM `sales` LEFT JOIN stocks ON sales.stock_id = stocks.s_id LEFT JOIN products ON stocks.pro_id = products.pro_id ORDER BY `sales`.`sales_id` ASC LIMIT 5;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
// output data of each row
while($row = $result->fetch_assoc()) {

echo "<tr> <td>".$row["batch_no"]."</td>
<td>"."<img class='img-fluid' width='50' height='50' src='uploads/{$row["media_file"]}'></td>
<td>".$row["pro_name"]."</td>
<td>".$row["qty"]."</td>
<td>".$row["price"]."</td></tr>";

}

echo"</tbody></table></div></div></div></div></div>";
} else {
echo "</tbody></table></div></div></div></div></div>";
}

$sql = "SELECT pro_name , batch_no, (SUM(quantity) - SUM(qty)) AS 'avlqty' FROM `sales` LEFT JOIN `stocks` ON sales.stock_id = stocks.s_id LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id` GROUP BY stocks.batch_no , stocks.pro_id;";
$chart_arrayx='';
$chart_arrayy='';
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $chart_array='' ;
    while($row = $result->fetch_assoc()) {
		$chart_arrayx.= "'" . $row["pro_name"] ." #" .$row["batch_no"] ."',";
		$chart_arrayy.=$row["avlqty"] . "," ;
    }
} 

echo<<<EOT

<div class="row">
	<div class="col-xxl-12 col-md-4">

		<div class="card" >

		<h5 class="card-title" style="text-align: center;">Product Availability</h5>

			<canvas id="mychart" style="padding: 1rem;"></canvas>
			</div>
		</div>
	</div>
<script>
    new Chart("mychart", {
        type: "bar",
        data: {
			labels: [$chart_arrayx],
            datasets: [
				{
					data: [$chart_arrayy]
                }
			]
        }
    });
</script>

EOT;

echo <<<EOT
</main>
EOT;

html_footer();
?>