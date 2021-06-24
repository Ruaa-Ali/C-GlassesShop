<?php
require '../../assets/server_configuration/config.php';
if (!isset($_COOKIE['a_name'], $_COOKIE['a_id'])) {
    header("Location: ./index.php");
}
?>


<?php

if (isset($_GET['id'])) {
    session_start();
    $_SESSION['productDelFailed'] = '';
    require '../../assets/server_configuration/config.php';
    $id = (int) mysqli_real_escape_string($conn, $_GET['id']);
    $id = is_numeric($id) ? $id : NULL;

    $query = 'SELECT p_id from `product` WHERE p_id  = ' . $id;
    $result = @mysqli_query($conn, $query);

    if (@mysqli_num_rows($result) == 1) {
        $query = "DELETE FROM `product` WHERE p_id = $id";
        $result = @mysqli_query($conn, $query);
        if (@mysqli_affected_rows($conn) == 1) {
            header("Location: ../dashboard.php");
        } else {
            $_SESSION['productDelFailed'] = 'Something went wrong, please try again.';
            header("Location: ../dashboard.php");
        }
    } else {
        $_SESSION['productDelFailed'] = 'There is no product with that ID!';
        header("Location: ../dashboard.php");
    }
    @mysqli_free_result($result);
    @mysqli_close($conn);
} else {
    $_SESSION['productDelFailed'] = 'Can\'t send the ID, please try again';
    header("Location: ../dashboard.php");
}
