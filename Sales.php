<?php
require_once "config/session.php";
require_once "config/database.php";
require_once "layout/header.php";
require_once "layout/footer.php";

html_header("Sales");


$sql = "SELECT * FROM `stocks` LEFT JOIN products ON stocks.pro_id =products.pro_id";
$result = $conn->query($sql);
$stock_options="";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $value="<option value='".$row["s_id"]."'> #".$row["s_id"] . " - " . $row["pro_id"]. " - " . $row["pro_name"]. " - B" . $row["batch_no"]."</option>";
        $stock_options=$stock_options . $value;
    }
}



if (isset($_POST['insert'])) { 

    $sql = "INSERT INTO `sales` (`stock_id`, `qty`, `price`, `date`) VALUES
        ('{$_POST['stock_id']}', '{$_POST['qty']}', '{$_POST['price']}', '{$_POST['date']}');";
        $conn->query($sql);
        echo"<script>window.location.href = 'Sales.php';</script>";
    }

if (isset($_POST['update'])) { 
    
    $sql = "UPDATE `sales` SET stock_id='{$_POST['stock_id']}' , qty='{$_POST['qty']}' , price='{$_POST['price']}' , date='{$_POST['date']}'  WHERE `sales_id` = ". $_POST['sales_id'].";";
    $conn->query($sql);
   echo"<script>window.location.href = 'Sales.php';</script>";
}

if (isset($_GET['delete'])) { 
    $sql = "DELETE FROM sales WHERE `sales`.`sales_id` = '{$_GET['delete']}'";
    $conn->query($sql);
    echo"<script>window.location.href = 'Sales.php';</script>";
}

if (isset($_GET['update'])){
    $sql = "SELECT * FROM `sales` WHERE sales_id='".$_GET['update']."'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $sales_id =$_GET['update'];
    $stock_id = $row["stock_id"];
    $price=$row["qty"];
    $qty =$row["price"];
    $date =$row["date"];

    $btn_update="";
    $btn_insert="display: none;";
}else{

    $sales_id = "";
    $stock_id = "";
    $price = "";
    $qty = "";
    $date = "";

    $btn_update="display: none;";
    $btn_insert="";
}

echo<<<EOT
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Manage Sales</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="Dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Manage Sales</li>
            </ol>
        </nav>
    </div>
    <br>
    <section class="section">
        <div class="row" >
            <div class="d-flex justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Manage Stocks</h5>
                            <form method="POST">
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Stock ID: </label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="hidden" name="sales_id" value="$sales_id">                               
                                        <select class="form-control" name="stock_id" id="stock_id" required>
                                            <option disabled selected value="$stock_id"> Select Stock </option>
                                            $stock_options
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Quantity :</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="text" class="form-control "name="qty" value="$qty" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Sell Price :</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="text" class="form-control "name="price" value="$price" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Date:</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="date" class="form-control "name="date" value="$date" required>
                                    </div>
                                </div>

                                <div class="col-sm-10">
                                    <button type="submit"  style="$btn_insert" class="btn btn-primary" name="insert">Insert</button>
                                    <button type="submit"  style="$btn_update" class="btn btn-primary" name="update">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title" style="text-align: center;">Sales View</h5>
                <br>
                <div style="overflow-x:auto;">
                    <table class="table table-striped" style="text-align:center; white-space:nowrap;font-size: 15px; "
                        id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">Sales ID</th>
                                <th scope="col">Stock ID</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Batch No</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Sale Price</th>
                                <th scope="col">Date</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
EOT;
$sql = "SELECT * FROM `sales` LEFT JOIN `stocks` ON `sales`.`stock_id` = `stocks`.`s_id` LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id` ";
//$sql = "SELECT * , (SUM(qty) - SUM(quantity)) AS 'avlqty' , (SUM(qty) * (SUM(sale_price)-SUM(buy_price))) AS 'Profit' FROM `sales` LEFT JOIN `stocks` ON sales.stock_id = stocks.s_id LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id` GROUP BY stocks.pro_id;";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
// output data of each row
$pval='';
while($row = $result->fetch_assoc()) {

$newval = $row["pro_name"];
if ($pval==$newval){
  $name_val = "";

}else {
  $name_val = $row["pro_name"];
  $pval = $newval ;
  # code...
}

	echo "<tr><td>".$row["sales_id"]."</td>
	<td>".$row["stock_id"]."</td>
    <td>".$name_val."</td>
    <td>".$row["batch_no"]."</td>
    <td>".$row["qty"]."</td>
    <td>".$row["price"]."</td>
    <td>".$row["date"]."</td>
    <td> <a href='?delete={$row["sales_id"]}' title='Click To Delete'><i class='bi bi-trash-fill'></i></a> | 
    <a href='?update={$row["sales_id"]}' title='Click To Delete'><i class='bi bi-pencil-fill'></i> </a>"."</td></tr>";
}

echo"</tbody></table></div></div></div></div>";
} else {
echo "</tbody></table></div></div></div></div>";
}

echo "</main>";

html_footer();
?>