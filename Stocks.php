<?php
require_once "config/session.php";
require_once "config/database.php";
require_once "layout/header.php";
require_once "layout/footer.php";

html_header("Stocks");

$sql = "SELECT * FROM `products`";
$result = $conn->query($sql);
$product_options = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $value = "<option value='" . $row["pro_id"] . "'> #" . $row["pro_id"] . " - " . $row["pro_name"] . "</option>";
        $product_options = $product_options . $value;
    }
}

if (isset($_POST['insert'])) {

    $sql = "SELECT batch_no FROM stocks WHERE `pro_id` = '{$_POST['pro_id']}'";
    $result = $conn->query($sql);


    $uniqueId = uniqid();
    $numericPart = hexdec(substr($uniqueId, 0, 8));
    $b_no = $numericPart % 20001;

    while ($row = $result->fetch_assoc()) {
        if ($row['batch_no'] == $b_no) {
            $uniqueId = uniqid();
            $numericPart = hexdec(substr($uniqueId, 0, 8));
            $b_no = $numericPart % 20001;
        }
    }

    // $batch_no = ($row["max(batch_no)"] + 1);

    $sql = "INSERT INTO `stocks` (`pro_id`, `batch_no`, `quantity`, `buy_price`, `sale_price`, `s_alert`) VALUES
        ('{$_POST['pro_id']}', '$b_no', '{$_POST['quantity']}', '{$_POST['buy_price']}', '{$_POST['sale_price']}', '{$_POST['s_alert']}');";
    $conn->query($sql);
    echo "<script>window.location.href = 'Stocks.php';</script>";
}

if (isset($_POST['update'])) {

    $sql = "UPDATE `stocks` SET pro_id='{$_POST['pro_id']}' , quantity='{$_POST['quantity']}' , buy_price='{$_POST['buy_price']}' , sale_price='{$_POST['sale_price']}'  , s_alert='{$_POST['s_alert']}'  WHERE `s_id` = " . $_POST['stock_id'] . ";";
    $conn->query($sql);
    echo "<script>window.location.href = 'Stocks.php';</script>";
}

if (isset($_GET['delete'])) {
    $sql = "DELETE FROM stocks WHERE `stocks`.`s_id` = '{$_GET['delete']}'";
    $conn->query($sql);
    echo "<script>window.location.href = 'Stocks.php';</script>";
}

if (isset($_GET['update'])) {
    $sql = "SELECT * FROM `stocks` LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id` WHERE s_id='" . $_GET['update'] . "'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $stock_id = $_GET['update'];
    $pro_name = " ( " .  $row["pro_name"] . " ) ";
    $batch_no = " Batch : # " . $row["batch_no"];
    $quantity = $row["quantity"];
    $buy_price = $row["buy_price"];
    $sale_price = $row["sale_price"];
    $s_alert = $row["s_alert"];

    $btn_update = "";
    $btn_insert = "display: none;";
} else {

    $stock_id = "";
    $pro_id = "";
    $batch_no = "";
    $quantity = "";
    $buy_price = "";
    $sale_price = "";
    $s_alert = "";

    $btn_update = "display: none;";
    $btn_insert = "";
}

echo <<<EOT
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Manage Stocks</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="Dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Manage Stocks</li>
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
                            <h5 class="card-title">Manage Stocks $pro_name $batch_no</h5>
                            <form method="POST">
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Product Name: </label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="hidden" name="stock_id" value="$stock_id">
                                        <select class="form-control" name="pro_id" id="pro_id" required>
                                            <option disabled> Select Product </option>
                                            $product_options
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Quantity:</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="text" class="form-control "name="quantity" value="$quantity" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Buy Price :</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="text" class="form-control "name="buy_price" value="$buy_price" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Sale Price:</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="text" class="form-control "name="sale_price" value="$sale_price" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Stock Alert :</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="text" class="form-control "name="s_alert" value="$s_alert" required>
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
                <h5 class="card-title" style="text-align: center;">Categorie Stocks</h5>
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
                                <th scope="col">Buy Price</th>
                                <th scope="col">Sale Price</th>
                                <th scope="col">Stock Alert</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
EOT;
$sql = "SELECT * FROM `stocks` LEFT JOIN `products` ON `stocks`.`pro_id` = `products`.`pro_id`;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    $pval = "";
    while ($row = $result->fetch_assoc()) {

        $newval = $row["pro_name"];
        if ($pval == $newval) {
            $name_val = "";
        } else {
            $name_val = $row["pro_name"];
            $pval = $newval;
            # code...
        }

        echo "<tr><td>" . $row["s_id"] . "</td>
	<td>" . $name_val . "</td>
    <td>" . $row["batch_no"] . "</td>
    <td>" . $row["quantity"] . "</td>
    <td>" . $row["buy_price"] . "</td>
    <td>" . $row["sale_price"] . "</td>
    <td>" . $row["s_alert"] . "</td>
    <td> <a href='?delete={$row["s_id"]}' title='Click To Delete'><i class='bi bi-trash-fill'></i></a> | 
    <a href='?update={$row["s_id"]}' title='Click To Delete'><i class='bi bi-pencil-fill'></i> </a>" . "</td></tr>";
    }

    echo "</tbody></table></div></div></div></div>";
} else {
    echo "</tbody></table></div></div></div></div>";
}

echo "</main>";

html_footer();
