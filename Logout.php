<?php
session_start();
unset($_SESSION["Session_Id"]);
unset($_SESSION["username"]);
unset($_SESSION["name"]);
session_destroy();
header("Location: login.php");

?>