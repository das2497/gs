<?php
require_once "config/session.php";
require_once "config/database.php";
require_once "layout/header.php";
require_once "layout/footer.php";

html_header("Sales_Report");


echo <<<EOT
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
                                <th scope='col'>Date</th>
                                <th scope='col'>Sold Quantity</th>
                                <th scope='col'>Cost of Sales</th>
                                <th scope='col'>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
EOT;

$sql = "";
if (!empty($_GET['from']) && !empty($_GET['to'])) {
    $sql = "SELECT *
    FROM `sales`
    INNER JOIN `stocks` ON `sales`.`stock_id` = `stocks`.`s_id`
    INNER JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id`   
  WHERE sales.date BETWEEN '" . $_GET['from'] . "' AND '" . $_GET['to'] . "' GROUP BY sales.sales_id;";
} elseif (!empty($_GET['from'])) {
    $sql = "SELECT *
    FROM `sales`
    INNER JOIN `stocks` ON `sales`.`stock_id` = `stocks`.`s_id`
    INNER JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id`
   WHERE sales.date > '" . $_GET['from'] . "' GROUP BY sales.sales_id;";
} elseif (!empty($_GET['to'])) {
    $sql = "SELECT *
    FROM `sales`
    INNER JOIN `stocks` ON `sales`.`stock_id` = `stocks`.`s_id`
    INNER JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id`
   WHERE sales.date < '" . $_GET['to'] . "' GROUP BY sales.sales_id;";
} else {
    // $sql = "SELECT * , ((quantity)-SUM(qty)) AS 'avlqty' FROM `sales` 
    // LEFT JOIN `stocks` ON `sales`.`stock_id` = `stocks`.`s_id` 
    // LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id` 
    // GROUP BY stocks.s_id;";

    $sql = "SELECT *
    FROM `sales`
    INNER JOIN `stocks` ON `sales`.`stock_id` = `stocks`.`s_id`
    INNER JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id`
    GROUP BY sales.sales_id;";
}


//$sql = "SELECT * , (SUM(quantity)-SUM(qty)) AS 'avlqty' FROM `sales` LEFT JOIN `stocks` ON sales.stock_id = stocks.s_id LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id` GROUP BY stocks.pro_id;";
$result = $conn->query($sql);
$total = 0;
$revenue = 0;
$total_cost_of_salese = 0;

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {

        echo "<tr><td>" . $row["s_id"] . "</td>
	<td>" . $row["pro_name"] . "</td>
    <td>" . $row["batch_no"] . "</td>
    <td>" . $row['date'] . "</td>
    <td>" . $row["qty"] . "</td>
    <td>" . $row["buy_price"] *  $row['qty'] . "</td>
    <td>" . $row["sale_price"]  *  $row["qty"] . "</td></tr>";

        // <td>" . ($row["sale_price"] - $row["buy_price"]) * ($row["quantity"] - $row["qty"]) . "</td></tr>";

        $revenue = $revenue + $row["sale_price"]  * $row["qty"];
        $total_cost_of_salese = $total_cost_of_salese + $row["buy_price"] * $row['qty'];
        $total = $total + ($row["sale_price"] - $row["buy_price"]) * $row["qty"];
    }

    $value =  strval($total);

    if ($value[0] === '0') {
        $hiddenValue = substr($value, 1); // Remove the first digit
    } else {
        $hiddenValue = $value; // Keep the original value
    }

    echo "
    <tr class='table-info'>
    <td class='text-end' colspan='5' scope='col'></td>
    <th scope='col'>Total Revenue</th>
    <td scope='col'>" . $revenue . "</td>
    </tr>
    <tr class='table-info'>
    <td class='text-end' colspan='5' scope='col'></td>
    <th scope='col'>Total Cost Of Sales</th>
    <td scope='col'>" . $total_cost_of_salese . "</td>
    </tr>
<tr class='table-info'>
<td class='text-end' colspan='5' scope='col'></td>
<th scope='col'>Gross profit</th>
<td scope='col'>" . $hiddenValue . "</td>
</tr>";

    echo "</tbody></table></div></div></div></div>";
} else {
    echo "</tbody></table></div></div></div></div>";
}

echo "</main>";

html_footer();
