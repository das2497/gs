<?php
require_once "config/session.php";
require_once "config/database.php";
require_once "layout/header.php";
require_once "layout/footer.php";

html_header("Manage_Users");

if (isset($_POST['adduser'])) { 
    $pass=md5($_POST['newpass']);
    $sql = "INSERT INTO `users`(`name`, `username`, `password`, `status`) VALUES
        ('{$_POST['Fullname']}','{$_POST['Username']}', '".$pass."', '1')";
        $conn->query($sql);
        echo"<script>window.location.href = 'Manage_Users.php';</script>";
    }

if (isset($_GET['delete'])) { 
    $sql = "DELETE FROM users WHERE `users`.`id` = '{$_GET['delete']}'";
    $conn->query($sql);
    echo"<script>window.location.href = 'Manage_Users.php';</script>";
}

echo<<<EOT
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Manage Users</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="Dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Manage Users</li>
            </ol>
        </nav>
    </div>
    <br>
    <section class="section">
        <div class="row">
            <div class="d-flex justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Adding a New User Account</h5>
                            <form method="POST">
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Username</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="text" class="form-control" id="Username" name="Username">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="text" class="form-control" id="Fullname" name="Fullname">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="password" class="form-control" id="newPassword" name="newpass">
                                    </div>
                                </div>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary" name="adduser">Submit</button>
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
                <h5 class="card-title" style="text-align: center;">View User Accounts</h5>
                <br>
                <div style="overflow-x:auto;">
                    <table class="table table-striped" style="text-align:center; white-space:nowrap;font-size: 15px; "
                        id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">User Name</th>
                                <th scope="col">Full Name</th>
                                <th scope="col">Last Login</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
EOT;
$sql = "SELECT * FROM `users`";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
// output data of each row
while($row = $result->fetch_assoc()) {

	echo "<tr><td>".$row["username"]."</td>
	<td>".$row["name"]."</td>
	<td>".$row["last_login"]."</td> 
    <td> <a href='?delete={$row["id"]}' title='Click To Delete'><i class='bi bi-person-dash-fill'></i> </a>"."</td></tr>";
}

echo"</tbody></table></div></div></div></div></main>";
} else {
echo "</tbody></table></div></div></div></div></main>";
}

html_footer();
?>