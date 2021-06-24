<?php
require './../assets/server_configuration/config.php';
if (!isset($_COOKIE['c_name'], $_COOKIE['c_id'])) {
    header("Location: ./index.php");
}
?>


<?php

if (isset($_GET['id'])) {
    require './../assets/server_configuration/config.php';
    $id = (int) mysqli_real_escape_string($conn, $_GET['id']);
    $id = is_numeric($id) ? $id : NULL;
    
    $query = "UPDATE `order` SET `o_state`= '0' WHERE `o_id` = $id";
    $result = @mysqli_query($conn, $query);
    if (mysqli_affected_rows($conn) == 1) {
        header("Location: ../cart.php");
    } else {
        // must be customized
        echo 'Something went wrong, please try again.';
        echo 'You\'ll be redirected back within 5 seconds &#128516';
        header("refresh:5; url=users.php");
    }

    @mysqli_free_result($result);
    @mysqli_close($conn);
} else {
    // must be customized
    echo 'Something went wrong, please try again.';
    echo 'You\'ll be redirected back within 5 seconds &#128516';
    header("refresh:5; url=users.php");
}
