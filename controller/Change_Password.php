<?php
require_once "../../config/database.php";

if (isset($_POST['updatepass'])) { 
    $pass = md5($_POST['newpass']);
    $sql = "UPDATE `users` SET `password`= '$pass' WHERE id ='".$_POST['user_id']."'";
    $conn->query($sql);
    header('Location: ../Dashboard.php');
}
?>