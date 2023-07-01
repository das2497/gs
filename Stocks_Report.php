<?php
require_once "config/session.php";
require_once "config/database.php";
require_once "layout/header.php";
require_once "layout/footer.php";

html_header("Stocks_Report");


echo<<<EOT
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Manage Stocks Report</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="Dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Manage Stocks Report</li>
            </ol>
        </nav>
    </div>
    <br>
 
    <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

            <h5 class="card-title" style="text-align: center;">Stocks Report View</h5>

            <form method='GET'>
            <div class='row g-2'>                   
                <div class='col-12 col-lg-4'>
                  <lable class='form-label'>From</lable>
                  <input class='form-control' type='date' id='from' name='from'/>
                </div>
                <div class='col-12 col-lg-4'>
                  <lable class='form-label'>To</lable>
                  <input class='form-control' type='date' id='to' name='to'/>
                </div>
                <div class='col-12 col-lg-2 d-grid'>
                   <button class='btn btn-primary mt-4' action='Sales_Report.php' type='submit'>Search</button>                   
                </div>
                <div class='col-12 col-lg-2 d-grid'>                                      
                   <a  class="btn btn-primary mt-4" href='Stocks_Report_Export.php' >Export</a>
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

                            </tr>
                        </thead>
                        <tbody>
EOT;

$sql = "";
if (!empty($_GET['from']) && !empty($_GET['to'])) {
    $sql = "SELECT *, (SUM(stocks.quantity) - SUM(sales.qty)) AS 'avlqty'
  FROM sales
  LEFT JOIN stocks ON sales.stock_id = stocks.s_id
  LEFT JOIN products ON stocks.pro_id = products.pro_id
  WHERE DATE(stocks.add_date) BETWEEN '" . $_GET['from'] . "' AND '" . $_GET['to'] . "' GROUP BY stocks.s_id;";
} elseif (!empty($_GET['from'])) {
    $sql = "SELECT *, (SUM(quantity)- SUM(qty)) AS 'avlqty'
    FROM `sales`
    LEFT JOIN `stocks` ON sales.stock_id = stocks.s_id
    LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id`
    WHERE DATE(stocks.add_date) > '" . $_GET['from'] . "'
    GROUP BY stocks.s_id;";
} elseif (!empty($_GET['to'])) {
    $sql = "SELECT *, (SUM(stocks.quantity) - SUM(sales.qty)) AS 'avlqty'
   FROM sales
   LEFT JOIN stocks ON sales.stock_id = stocks.s_id
   LEFT JOIN products ON stocks.pro_id = products.pro_id
   WHERE DATE(stocks.add_date) < '" . $_GET['to'] . "' GROUP BY stocks.s_id;";
} else {
    $sql = "SELECT * , (SUM(quantity)-SUM(qty)) AS 'avlqty' FROM `sales` LEFT JOIN `stocks` ON `sales`.`stock_id` = `stocks`.`s_id` LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id` GROUP BY stocks.s_id;";
}

// $sql = "SELECT * ,  (SUM(quantity)-SUM(qty)) AS 'avlqty' FROM `sales` LEFT JOIN `stocks` ON sales.stock_id = stocks.s_id LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id` GROUP BY stocks.s_id;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
// output data of each row
while($row = $result->fetch_assoc()) {

	echo "<tr><td>".$row["s_id"]."</td>
	<td>".$row["pro_name"]."</td>
    <td>".$row["batch_no"]."</td>
    <td>".$row["quantity"]."</td>
    <td>".$row["avlqty"]."</td></tr>";
}

echo"</tbody></table></div></div></div></div>";
} else {
	echo "
    <tr>
      <td colspan='5'>No data to show <br/> Search again</td>
    </tr>";    
echo "</tbody></table></div></div></div></div>";
}

echo "</main>";

html_footer();
