<?php
require_once "config/session.php";
require_once "config/database.php";
require_once "layout/header.php";
require_once "layout/footer.php";

html_header("Categories");

if (isset($_POST['insert'])) {
    $sql = "INSERT INTO `categories`(`cat_name`) VALUES
        ('{$_POST['cat_name']}')";
    $conn->query($sql);
    echo "<script>window.location.href = 'Categorie.php';</script>";
}

if (isset($_POST['update'])) {
    $sql = "UPDATE `categories` SET cat_name='{$_POST['cat_name']}' WHERE `cat_id` = " . $_POST['c_id'] . ";";
    $conn->query($sql);
    echo "<script>window.location.href = 'Categorie.php';</script>";
}

if (isset($_GET['delete'])) {
    $sql = "DELETE FROM `categories` WHERE cat_id = '" . $_GET['delete'] . "';";
    $conn->query($sql);
     echo "<script>
    // window.location.href = 'Categorie.php';
     </script>";
  
}

if (isset($_GET['update'])) {
    $sql = "SELECT * FROM categories WHERE cat_id='" . $_GET['update'] . "'";
    $id = $_GET['update'];
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $c_id = $row["cat_id"];
    $cat_name = $row["cat_name"];
    $btn_update = "";
    $btn_insert = "display: none;";
} else {
    $cat_name = "";
    $c_id = "";
    $btn_update = "display: none;";
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
                            <h5 class="card-title">Manage Categorie</h5>
                            <form method="POST">
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Categorie Name:</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="hidden" name="c_id" value="$c_id">
                                        <input type="text" class="form-control "name="cat_name" value="$cat_name">
                                        
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
                <h5 class="card-title" style="text-align: center;">Categorie View</h5>
                <br>
                <div style="overflow-x:auto;">
                    <table class="table table-striped" style="text-align:center; white-space:nowrap;font-size: 15px; "
                        id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">Category ID</th>
                                <th scope="col">Category Name</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
EOT;
$sql = "SELECT * FROM `categories` ORDER BY `categories`.`cat_id` ASC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {

        echo "<tr><td>" . $row["cat_id"] . "</td>
	<td>" . $row["cat_name"] . "</td>
    <td> <a href='?delete={$row["cat_id"]}' title='Click To Delete'><i class='bi bi-trash-fill'></i></a> |  
    <a href='?update={$row["cat_id"]}' title='Click To Delete'><i class='bi bi-pencil-fill'></i> </a></td></tr>";
    }

    echo "</tbody></table></div></div></div></div></main>";
} else {
    echo "</tbody></table></div></div></div></div></main>";
}

html_footer();
