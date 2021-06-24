<?php
require '../assets/server_configuration/config.php';
if (!isset($_COOKIE['a_name'], $_COOKIE['a_id'])) {
    header("Location: ./index.php");
}
?>


<?php
if (isset($_POST['submit'])) {
    require '../assets/server_configuration/config.php';

    if (empty($_POST['p_name']) || empty($_POST['p_model']) || empty($_POST['p_color']) || empty($_POST['p_price'])) {
        $empty = true;
    } else {
        $currentAdminID = (int) mysqli_real_escape_string($conn, $_COOKIE['a_id']);

        $p_name = mysqli_real_escape_string($conn, trim($_POST['p_name']));
        $p_model = mysqli_real_escape_string($conn, trim($_POST['p_model']));
        $p_color = mysqli_real_escape_string($conn, trim($_POST['p_color']));
        $p_price = (float) mysqli_real_escape_string($conn, trim($_POST['p_price']));

        $query = "SELECT `p_id` FROM `product` WHERE `p_name` = '$p_name'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0) {
            $duplicate = 1;
        } else {
            require './db/Uploader.php';
            $uploader = new Uploader();
            $uploader->setDir('../assets/img/products/');
            $uploader->setExtensions(array('jpg', 'jpeg', 'png'));
            $uploader->setMaxSize(1);
            $uploader->sameName(true);
            if ($uploader->uploadFile('p_img_front')) {
                $front = $uploader->getUploadName();
                if ($uploader->uploadFile('p_img_back')) {
                    $back = $uploader->getUploadName();

                    $query = "INSERT INTO `product` (`p_name`, `p_model`,`p_color`,`p_added_by` ,p_img_front, p_active,p_img_back,p_price )
                    VALUES ('$p_name', '$p_model', '$p_color', '$currentAdminID','$front', '1', '$back', '$p_price')";
                    $result = mysqli_query($conn, $query);
                    if (mysqli_affected_rows($conn) == 1) {
                        header('Location: ./dashboard.php');
                    } else {
                        $failed = true;
                    }
                } else {
                    $backFailed = $uploader->getMessage();
                }
            } else {
                $frontFailed = $uploader->getMessage();
            }
        }
    }

    mysqli_free_result($result);
    mysqli_close($conn);
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


    <title>C - Add product</title>
</head>

<body>
    <?php
    require_once('./header.php');
    ?>



    <div class="p-5" style="margin-top: 80px;">
        <form method="POST" class="d-flex flex-column mx-auto" enctype="multipart/form-data">
            <h2 class="form-heading text-center">ADD NEW PRODUCT</h2>
            <?php

            if (isset($empty) && $empty) {
                echo '<div class="failed p-2 my-3">
                All fields are required, please fill them.
                </div>';
            } else if (isset($failed) && $failed) {
                echo '<div class="failed p-2 my-3">
                Something went wrong, Please try again later.
                </div>';
            } else if (isset($duplicate) && $duplicate  == 1) {
                echo '<div class="failed p-2 my-3">
                Please choose a unique name.
                </div>';
            }
            if (isset($frontFailed)) {
                echo '<div class="failed p-2 my-1">Front Image: 
                ' . $frontFailed . '
                </div>';
            }
            if (isset($backFailed)) {
                echo '<div class="failed p-2 my-1">Back Image: 
                ' . $backFailed . '
                </div>';
            }
            ?>
            <label for="p_name">NAME</label>
            <input type="text" name="p_name" id="p_name" class="font-weight-bold" required>

            <label for="p_model">MODEL</label>
            <input type="text" name="p_model" id="p_model" class="font-weight-bold" required>

            <label for="p_color">COLOR</label>
            <input type="text" name="p_color" id="p_color" class="font-weight-bold" required>

            <label for="p_img_front">FRONT IMAGE</label>
            <input type="file" name="p_img_front" id="p_img_front" class="font-weight-bold" required>

            <label for="p_img_back">BACK IMAGE</label>
            <input type="file" name="p_img_back" id="p_img_back" class="font-weight-bold" required>

            <label for="p_price">PRICE</label>
            <input type="number" name="p_price" id="p_price" class="font-weight-bold" min="0" required>

            <input type="submit" name="submit" value="ADD" class="my-3">
        </form>
    </div>




    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

</html>