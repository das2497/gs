<?php
require_once "config/session.php";
require_once "config/database.php";
require_once "layout/header.php";
require_once "layout/footer.php";

html_header("Sales_Report");


echo<<<EOT
<main id='main' class='main'>
    <div class='pagetitle'>
        <h1>Manage Sales Report</h1>
        <nav>
            <ol class='breadcrumb>
                <li class='breadcrumb-item'><a href='Dashboard.php'>Home</a></li>
                <li class='breadcrumb-item active'>Manage Sales Report</li>
            </ol>
        </nav>
    </div>
    <br>
 
    <div class='row'>
    <div class='col-lg-12'>
        <div class='card'>
            <div class='card-body'>

                <h5 class='card-title' style='text-align: center;'>Sales Report View</h5>

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
                     <a class='btn btn-primary mt-4' href='Sales_Report_Export.php'>Export</a>                                                     
                    </div>
                </div>
                </form>            
                
               
                <br>
                <div style='overflow-x:auto;'>
                    <table class='table table-striped' style='text-align:center; white-space:nowrap;font-size: 15px;' id='dataTable' width='100%' cellspacing='0'>
                        <thead>
                            <tr>
                                <th scope='col'>Stock ID</th>
                                <th scope='col'>Product Name</th>
                                <th scope='col'>Batch No</th>
                                <th scope='col'>Quantity</th>
                                <th scope='col'>Avl Quantity</th>
                                <th scope='col'>Buy Price</th>
                                <th scope='col'>Sale Price</th>
                                <th scope='col'>Profit</th>
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
  WHERE sales.date BETWEEN '" . $_GET['from'] . "' AND '" . $_GET['to'] . "' GROUP BY stocks.s_id;";
} elseif (!empty($_GET['from'])) {
    $sql = "SELECT *, (SUM(stocks.quantity) - SUM(sales.qty)) AS 'avlqty'
   FROM sales
   LEFT JOIN stocks ON sales.stock_id = stocks.s_id
   LEFT JOIN products ON stocks.pro_id = products.pro_id
   WHERE sales.date > '" . $_GET['from'] . "' GROUP BY stocks.s_id;";
} elseif (!empty($_GET['to'])) {
    $sql = "SELECT *, (SUM(stocks.quantity) - SUM(sales.qty)) AS 'avlqty'
   FROM sales
   LEFT JOIN stocks ON sales.stock_id = stocks.s_id
   LEFT JOIN products ON stocks.pro_id = products.pro_id
   WHERE sales.date < '" . $_GET['to'] . "' GROUP BY stocks.s_id;";
} else {
    $sql = "SELECT * , (SUM(quantity)-SUM(qty)) AS 'avlqty' FROM `sales` LEFT JOIN `stocks` ON `sales`.`stock_id` = `stocks`.`s_id` LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id` GROUP BY stocks.s_id;";
}


//$sql = "SELECT * , (SUM(quantity)-SUM(qty)) AS 'avlqty' FROM `sales` LEFT JOIN `stocks` ON sales.stock_id = stocks.s_id LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id` GROUP BY stocks.pro_id;";
$result = $conn->query($sql);
$total = 0;

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {

        echo "<tr><td>" . $row["s_id"] . "</td>
	<td>" . $row["pro_name"] . "</td>
    <td>" . $row["batch_no"] . "</td>
    <td>" . $row["quantity"] . "</td>
    <td>" . $row["avlqty"] . "</td>
    <td>" . $row["buy_price"] . "</td>
    <td>" . $row["sale_price"] . "</td>
    <td>" . $row["sale_price"] - $row["buy_price"] . "</td></tr>";

        $total = $total + ($row["sale_price"] - $row["buy_price"]);
    }

    $value =  strval($total);

    if ($value[0] === '0') {
        $hiddenValue = substr($value, 1); // Remove the first digit
    } else {
        $hiddenValue = $value; // Keep the original value
    }

    echo "
<tr class='table-info'>
<td class='text-end' colspan='6' scope='col'></td>
<th scope='col'>Total Profit</th>
<td scope='col'>" . $hiddenValue . "</td>
</tr>
";

    echo "</tbody></table></div></div></div></div>";
} else {
    echo "</tbody></table></div></div></div></div>";
}

echo "</main>";

html_footer();
