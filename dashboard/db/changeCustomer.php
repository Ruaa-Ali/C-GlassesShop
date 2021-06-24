<?php
require '../../assets/server_configuration/config.php';
if (!isset($_COOKIE['a_name'], $_COOKIE['a_id'])) {
    header("Location: ./index.php");
}
?>

<?php

if (isset($_GET['id'])) {
    session_start();
    $_SESSION['customerChangeFailed'] = '';
    require '../../assets/server_configuration/config.php';
    $id = (int) mysqli_real_escape_string($conn, $_GET['id']);
    $id = is_numeric($id) ? $id : NULL;
    $query = 'SELECT c_active from `customer` WHERE c_id  = ' . $id;
    $result = @mysqli_query($conn, $query);

    if (@mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if($row['c_active'] == 1){
            $active = 0;
        }
        else{
            $active = 1;
        }

        $query = "UPDATE `customer` SET `c_active`='$active' WHERE `c_id` =  $id";
        $result = mysqli_query($conn, $query);
        if (mysqli_affected_rows($conn) == 1) {
            header("Location: ../dashboard.php");
        } else {
            $_SESSION['customerChangeFailed'] = 'Something went wrong, please try again.';
            header("Location: ../dashboard.php");
        }
    } else {
        $_SESSION['customerChangeFailed'] = 'There is no customer with that ID!';
        header("Location: ../dashboard.php");
    }
    @mysqli_free_result($result);
    @mysqli_close($conn);
} else {
    $_SESSION['customerChangeFailed'] = 'Can\'t send the ID, please try again';
    header("Location: ../dashboard.php");
}
