<?php
require_once "config/session.php";
require_once "config/database.php";
require_once "layout/header.php";
require_once "layout/footer.php";

html_header("Change_Password");

if (isset($_POST['adduser'])) { 
    $pass=md5($_POST['newpass']);
    $sql = "INSERT INTO `users`(`name`, `username`, `password`, `status`) VALUES
        ('{$_POST['Fullname']}','{$_POST['Username']}', '".$pass."', '1')";
        $conn->query($sql);
        echo"<script>window.location.href = 'Manage_Users.php';</script>";
    }

echo<<<EOT
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Change Password</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="Dashboard.php">Home</a></li>
                <li class="breadcrumb-item active">Change Password</li>
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
                            <h5 class="card-title">Change Password</h5>
                            <form method="POST">
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Old Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="hidden" name="user_id">
                                        <input type="text" class="form-control" name="Old" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="text" class="form-control" name="newpass" id="password" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-lg-3 col-form-label">Verify Password</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="text" class="form-control" id="confirm_password" required>
                                    </div>
                                </div>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary" name="updatepass">Change Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>


    <script>
        var password = document.getElementById("password"), confirm_password = document.getElementById("confirm_password");
        function validatePassword() {
            if (password.value != confirm_password.value) {
                confirm_password.setCustomValidity("Passwords Don't Match");
            } else {
                confirm_password.setCustomValidity('');
            }
        }
        password.onchange = validatePassword;
        confirm_password.onkeyup = validatePassword;
    </script>

EOT;

html_footer();
?>