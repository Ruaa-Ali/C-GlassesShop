<?php
require '../assets/server_configuration/config.php';
if (!isset($_COOKIE['a_name'], $_COOKIE['a_id'])) {
    header("Location: ./index.php");
}
?>


<?php

if (isset($_GET['id'])) {
    require '../assets/server_configuration/config.php';
    $id = (int) mysqli_real_escape_string($conn, $_GET['id']);
    $id = is_numeric($id) ? $id : NULL;


    $query = 'SELECT `p_id`, `p_name`, `p_model`, `p_color`, `p_img_front`, `p_img_back`, `p_price`, `p_active` FROM `product` WHERE `p_id` =  ' . $id;
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
    } else {
        header('Location: ./dashboard.php');
    }
    mysqli_free_result($result);
    mysqli_close($conn);
}

if (isset($_GET['id']) && isset($_POST['submit'])) {
    require '../assets/server_configuration/config.php';
    $id = (int) mysqli_real_escape_string($conn, $_GET['id']);
    $id = is_numeric($id) ? $id : NULL;

    if (empty($_POST['p_name']) || empty($_POST['p_model']) || empty($_POST['p_color']) || empty($_POST['p_price'])) {
        $empty = true;
    } else {
        $p_name = mysqli_real_escape_string($conn, trim($_POST['p_name']));
        $p_model = mysqli_real_escape_string($conn, trim($_POST['p_model']));
        $p_color = mysqli_real_escape_string($conn, trim($_POST['p_color']));
        $p_price = (float) mysqli_real_escape_string($conn, trim($_POST['p_price']));


        $query = "SELECT `p_id`, `p_active` FROM `product` WHERE `p_name` = '$p_name'";
        $result = @mysqli_query($conn, $query);
        if (@mysqli_num_rows($result) > 1) {
            $duplicate = true;
        } else {
            if (isset($_POST['p_active'])) {
                $active = 1;
            } else {
                $active = 0;
            }

            $query = "UPDATE `product` SET `p_name`='$p_name', `p_model`='$p_model', `p_color`='$p_color', `p_active`='$active', `p_price`='$p_price' WHERE `p_id` =  '$id'";
            $result = @mysqli_query($conn, $query);
            if (@mysqli_affected_rows($conn) == 1) {
                header('Location: ./dashboard.php');
            } else {
                $failed = true;
            }
            @mysqli_free_result($result);
            @mysqli_close($conn);
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="./../assets/lib/fonts/poppins/poppins.css">
    <link rel="stylesheet" href="./../css/forLogin.css">
    <link rel="stylesheet" href="./css/forCommon.css">


    <title>C - Edit Admin</title>
</head>

<body>
    <?php
    require_once('./header.php');
    ?>



    <div class="p-5 ">
        <form method="POST" class="d-flex flex-column mx-auto">
            <h2 class="form-heading text-center">EDIT ADMIN</h2>
            <?php
            if (isset($empty) && $empty) {
                echo '<div class="failed p-2 my-3">
                                All fields are required, please fill them.
                            </div>';
            } else if (isset($failed) && $failed) {
                echo '<div class="failed p-2 my-3">
                            Something went wrong, Please try again later. <br>
                            That could also happen if you did not change anything
                            </div>';
            } else if (isset($duplicate) && $duplicate) {
                echo '<div class="failed p-2 my-3">
                Please choose a unique name.
                </div>';
            }
            ?>

            <label for="p_name">NAME</label>
            <input type="text" name="p_name" id="p_name" class="font-weight-bold" value="<?= $row['p_name'] ?>" required>

            <label for="p_model">MODEL</label>
            <input type="text" name="p_model" id="p_model" class="font-weight-bold" value="<?= $row['p_model'] ?>" required>

            <label for="p_color">COLOR</label>
            <input type="text" name="p_color" id="p_color" class="font-weight-bold" value="<?= $row['p_color'] ?>" required>

            <!-- <label for="p_img_front">FRONT IMAGE</label>
            <input type="file" name="p_img_front" id="p_img_front" class="font-weight-bold">

            <label for="p_img_back">BACK IMAGE</label>
            <input type="file" name="p_img_back" id="p_img_back" class="font-weight-bold"> -->

            <label for="p_price">PRICE</label>
            <input type="number" name="p_price" id="p_price" class="font-weight-bold" min="0" value="<?= $row['p_price'] ?>" required>

            <?php
            if ($row['p_active'] == 1) {

                echo '
                <div class="form-check">
                <label for="p_active" class="form-check-label">
                    <input type="checkbox" class="form-check-input" name="p_active" id="p_active" value="0" checked>Active
                </label>
                </div>
                ';
            } else {

                echo '
                <div class="form-check">
                <label for="p_active" class="form-check-label">
                    <input type="checkbox" class="form-check-input" name="p_active" id="p_active" value="1">Active
                </label>
                </div>
                ';
            }


            ?>


            <input type="submit" name="submit" value="UPDATE" class="my-3">
        </form>
    </div>




    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

</html>