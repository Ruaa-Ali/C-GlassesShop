<?php

if(isset($_GET['id'])){
    require './../assets/server_configuration/config.php';
    $id = (int) mysqli_real_escape_string($conn, $_GET['id']);
    $id = is_numeric($id) ? $id : NULL;
    $cid = (int) mysqli_real_escape_string($conn, $_COOKIE['c_id']);
    $query = "INSERT INTO `order`(`o_quantity`, `p_id`, `c_id`) VALUES ('1','$id','$cid')";
    $result = @mysqli_query($conn, $query);
    if (mysqli_affected_rows($conn) == 1) {
        header("Location: ../index.php");
    }
    else{
        session_start();
        $_SESSION['errors'] = 'Enable to add product to your cart';
        header("Location: ../index.php");
    }
}