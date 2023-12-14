<?php

require_once "config/session.php";
require_once "config/database.php";




echo <<<EOT



<!doctype html>
<html lang="en-US">
 <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <title> Sales Report</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>     
   <style>
   @media print {
     html,body{
        font-size: 9.5pt;
        margin: 0;
        padding: 0;
     }.page-break {
       page-break-before:always;
       width: auto;
       margin: auto;
      }
      #printPageButton {
        display: none;
        }
    }
    .page-break{
      width: 980px;
      margin: 0 auto;
    }
     .sale-head{
       margin: 40px 0;
       text-align: center;
     }.sale-head h1,.sale-head strong{
       padding: 10px 20px;
       display: block;
     }.sale-head h1{
       margin: 0;
       border-bottom: 1px solid #212121;
     }.table>thead:first-child>tr:first-child>th{
       border-top: 1px solid #000;
      }
      table thead tr th {
       text-align: center;
       border: 1px solid #ededed;
     }table tbody tr td{
       vertical-align: middle;
     }.sale-head,table.table thead tr th,table tbody tr td,table tfoot tr td{
       border: 1px solid #212121;
       white-space: nowrap;
     }.sale-head h1,table thead tr th,table tfoot tr td{
       background-color: #f8f8f8;
     }tfoot{
       color:#000;
       text-transform: uppercase;
       font-weight: 500;
     }
   </style>
</head>
<body>
    <div class="page-break">
       <div class="sale-head"> 
           <h1>Inventory Management System - Sales Report</h1> 
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
                       <h1 class='d-inline' ><button action='Sales_Report_Export.php' type='submit'>Search</button></h1>
                    </div>
                    <div class='col-12 col-lg-2  '>
                      <h1 class='d-inline' ><button id="printPageButton" onClick="window.print();">Print</button></h1> 
                    </div>  
                </div>
            </form>                       
       </div>
    
      <table class="table table-border">
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
}



echo <<<EOT

       
      </table>
    </div>


</body>
</html>
EOT;
