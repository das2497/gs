<?php
require_once "config/session.php";
require_once "config/database.php";
require_once "layout/header.php";
require_once "layout/footer.php";

html_header("Products");

$sql = "SELECT * FROM `categories`";
$result = $conn->query($sql);
$category_options = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $value = "<option value='" .  $row["cat_id"] . "'> " . $row["cat_name"] . "</option>";
        $category_options = $category_options . $value;
    }
}

if (isset($_GET['delete'])) {
    $sql = "DELETE FROM products WHERE `products`.`pro_id` = '{$_GET['delete']}'";
    $conn->query($sql);
    echo "<script>window.location.href = 'Products.php';</script>";
}

if (isset($_GET['update'])) {
    $sql = "SELECT * FROM products WHERE pro_id='" . $_GET['update'] . "'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $p_id = $_GET['update'];
    $pro_name = $row["pro_name"];
    $btn_update = "";
    $st_alert = $row["st_alert"];
    $btn_insert = "display: none;";
} else {
    $p_id = "";
    $pro_name = "";
    $btn_update = "display: none;";
    $st_alert = "";
    $btn_insert = "";
}

echo <<<EOT
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Manage Categorie</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="Dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Manage Categorie</li>
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
                            <h5 class="card-title">Manage Products</h5>
                            <form action="controller/Products.php" method="post" enctype="multipart/form-data">

                            
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Product Name:</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="hidden" name="p_id" value="$p_id">
                                        <input type="text" class="form-control " name="pro_name" value="$pro_name">
                                    </div>
                                </div>
                            
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Category Name:</label>
                                    <div class="col-md-8 col-lg-9">
                                        <select class="form-control" name="cat_id" id="cat_id" required>
                                            <option selected disabled> Select Catogery </option>
                                            $category_options
                                        </select>
                                    </div>
                                </div>
                            
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Product Image:</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="file" class="form-control" name="file">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                <label class="col-md-4 col-lg-3 col-form-label">Stock Alert :
                                    </label>
                                <div class="col-md-8 col-lg-9">
                                    <input type="hidden" name="sl_id" value="$sl_id">
                                    <input type="text" class="form-control " name="st_alert" value="$st_alert">
                                </div>

                            </div>
                            
                                <div class="col-sm-10">
                                    <button type="submit" style="$btn_insert" class="btn btn-primary" name="insert">Insert</button>
                                    <button type="submit" style="$btn_update" class="btn btn-primary" name="update">Update</button>
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
                <h5 class="card-title" style="text-align: center;">Products View</h5>
                <br>
                <div style="overflow-x:auto;">
                    <table class="table table-striped" style="text-align:center; white-space:nowrap;font-size: 15px; "
                        id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">Product Image</th>
                                <th scope="col">Product ID</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Category name</th>
                                <th scope="col">Stock Alert</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
EOT;
$sql = "SELECT * FROM `products` LEFT JOIN `categories` ON products.categorie_id = categories.cat_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {

        echo "<tr> <td>" . "<img class='img-fluid' width='50' height='50' src='uploads/{$row["media_file"]}'></td>
	<td>" . $row["pro_id"] . "</td>
	<td>" . $row["pro_name"] . "</td>
	<td>" . $row["cat_name"] . "</td>
	<td>" . $row["st_alert"] . "</td>
    <td> <a href='?delete={$row["pro_id"]}' title='Click To Delete'><i class='bi bi-trash-fill'></i></a> | 
    <a href='?update={$row["pro_id"]}' title='Click To Delete'><i class='bi bi-pencil-fill'></i> </a>" . "</td></tr>";
    }

    echo "</tbody></table></div></div></div></div>";
} else {
    echo "</tbody></table></div></div></div></div>";
}

echo "</main>";

html_footer();
