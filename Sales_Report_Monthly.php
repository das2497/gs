<?php
require_once "config/session.php";
require_once "config/database.php";
require_once "layout/header.php";
require_once "layout/footer.php";

html_header("Sales_Report_Monthly");


echo<<<EOT
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Manage Sales Report Monthly</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="Dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Manage Sales Report Monthly</li>
            </ol>
        </nav>
    </div>
    <br>
 
    <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title" style="text-align: center;">Monthly Sales Report View</h5>
                <form method="get">
                    <div class="row mb-3">
                        <div class="col-md-2 col-lg-2">
                            <input type="month" class="form-control "name="date"required>
                        </div>
                        <div class="col-md-2 col-lg-2">
                            <button type="submit" class="btn btn-primary" name="check" value="check">Check</button>
                        </div>
                    </div>
                </form>
                <br>
                <div style="overflow-x:auto;">
                    <table class="table table-striped" style="text-align:center; white-space:nowrap;font-size: 15px; "
                        id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">Stock ID</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Batch No</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Avl Quantity</th>
                                <th scope="col">Buy Price</th>
                                <th scope="col">Sale Price</th>
                            </tr>
                        </thead>
                        <tbody>
EOT;

if (isset($_GET['check'])) { 
    $sql = "SELECT * , (SUM(qty) - SUM(quantity)) AS 'avlqty' FROM `sales` LEFT JOIN `stocks` ON sales.stock_id = stocks.s_id LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id` WHERE DATE_FORMAT(date, '%Y-%m') ='".$_GET['date']."' GROUP BY stocks.pro_id;";
}else{
    $sql = "SELECT * , (SUM(qty) - SUM(quantity)) AS 'avlqty' FROM `sales` LEFT JOIN `stocks` ON sales.stock_id = stocks.s_id LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id` GROUP BY `stocks`.`pro_id`;";
}

$result = $conn->query($sql);
if ($result->num_rows > 0) {
// output data of each row
while($row = $result->fetch_assoc()) {

	echo "<tr><td>".$row["s_id"]."</td>
	<td>".$row["pro_name"]."</td>
    <td>".$row["batch_no"]."</td>
    <td>".$row["quantity"]."</td>
    <td>".$row["avlqty"]."</td>
    <td>".$row["buy_price"]."</td>
    <td>".$row["sale_price"]."</td></tr>";
}

echo"</tbody></table></div></div></div></div>";
} else {
echo "</tbody></table></div></div></div></div>";
}

echo "</main>";

html_footer();
?>