<?php

require_once "config/session.php";
require_once "config/database.php";

echo<<<EOT

<!doctype html>
<html lang="en-US">
 <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <title>Default Page Title</title>
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
           <h1>Inventory Management System - Stocks Report </h1> 
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
                  <h1><button  action='Sales_Report_Export.php' type='submit'>Search</button></h1>
               </div>
               <div class='col-12 col-lg-2 d-grid'>                                      
                  <h1><button id="printPageButton" onClick="window.print();">Print</button></h1>
               </div>
           </div>
           </form>     

       </div>
      <table class="table table-border">
        <thead>
          <tr>
              <th>Stock ID</th>
              <th>Product Name</th>
              <th>Batch No</th>
              <th>Quantity</th>
              <th>Avl Quantity</th>
              <th>Buy Price</th>
              <th>Sale Price</th>
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


// $sql = "SELECT * , (SUM(quantity) - SUM(qty) ) AS 'avlqty' FROM `sales` LEFT JOIN `stocks` ON sales.stock_id = stocks.s_id LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id` GROUP BY stocks.s_id;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
// output data of each row
while($row = $result->fetch_assoc()) {

echo<<<EOT

    <tr>
       <td class=""> {$row["s_id"]} </td>
        <td class="desc"> <h6>  {$row["pro_name"]} </h6></td>
        <td class="text-right">{$row["batch_no"]}</td>
        <td class="text-right">{$row["quantity"]}</td>
        <td class="text-right">{$row["avlqty"]}</td>
        <td class="text-right">{$row["buy_price"]}</td>
        <td class="text-right">{$row["sale_price"]}</td>
   </tr>

EOT;

echo "</tbody></table>";

}
}else {
	echo "
    <tr>
      <td class='text-center' colspan='7'>No data to show <br/> Search again</td>
    </tr>";    

}

echo<<<EOT
</body>
</html>
EOT;







?>