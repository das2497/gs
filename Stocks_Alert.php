<?php
require_once "config/session.php";
require_once "config/database.php";
require_once "layout/header.php";
require_once "layout/footer.php";

html_header("Stocks_Alert");

echo<<<EOT
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Manage Stocks Alert</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="Dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Manage Stocks Alert</li>
            </ol>
        </nav>
    </div>
    <br>
 
    <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title" style="text-align: center;">Stocks Alert View</h5>
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
                                <th scope="col">Stock Alert</th>
                            </tr>
                        </thead>
                        <tbody>
EOT;
$sql = "SELECT * , ((quantity) - SUM(qty)) AS 'avlqty' FROM `sales` 
LEFT JOIN `stocks` ON sales.stock_id = stocks.s_id 
LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id` 
GROUP BY stocks.s_id;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
    ($row["s_alert"] > $row["avlqty"])? ($alert_styl =" color: white;background-color: red;") : ($alert_styl = "");
	echo "<tr><td style='$alert_styl'>".$row["s_id"]."</td>
	<td style='$alert_styl'>".$row["pro_name"]."</td>
    <td style='$alert_styl'>".$row["batch_no"]."</td>
    <td style='$alert_styl'>".$row["quantity"]."</td>
    <td style='$alert_styl'>".$row["avlqty"]."</td>
    <td style='$alert_styl'>".$row["s_alert"]."</td></tr>";
}
    echo"</tbody></table></div></div></div></div>";
} else {
    echo "</tbody></table></div></div></div></div>";
}

echo "</main>";

html_footer();
