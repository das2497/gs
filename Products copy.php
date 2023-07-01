<?php

//Maximum allowed size for uploaded files.
//http://php.net/upload-max-filesize
//upload_max_filesize = 2M

require_once "../config/database.php";


if(isset($_POST['insert'])){
    $filename=$_FILES["file"]["name"];
    $fileext=strstr($filename,'.');

    //upload
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;

    if (file_exists($target_file)) {
      echo "Sorry, file already exists.";
      $uploadOk = 0;
    }

    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";
    } else {
      if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {

        $sql = "INSERT INTO `products` (`pro_name`, `categorie_id`, `media_file`) 
        VALUES ('{$_POST['pro_name']}', '{$_POST['cat_id']}','$filename');";
        $conn->query($sql);

      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    }
   header('Location: ../Products.php');
}elseif(isset($_POST['update'])){

  if (isset($_FILES['file'])) {
    $filename=$_FILES["file"]["name"];
    $fileext=strstr($filename,'.');
  
    //upload
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    
      if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        $sql = "UPDATE `products` SET pro_name='{$_POST['pro_name']}' , categorie_id='{$_POST['cat_id']}' , media_file='$filename' WHERE `pro_id` ='{$_POST['p_id']}';";
        $conn->query($sql);
  
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
  }else{
    $sql = "UPDATE `products` SET pro_name='{$_POST['pro_name']}' , categorie_id='{$_POST['cat_id']}' WHERE `pro_id` ='{$_POST['p_id']}';";
    $conn->query($sql);
  }

 header('Location: ../Products.php');
}else{

}
?>