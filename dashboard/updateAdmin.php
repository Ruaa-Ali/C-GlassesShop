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

    if ($id == $_COOKIE['a_id']) {
        $query = 'SELECT A.a_id, A.a_name, A.a_active as active , B.a_name as addedBy, A.a_phone, A.a_country, A.a_email FROM `admin` A, `admin` B WHERE B.a_id = A.a_added_by AND A.a_id = ' . $id;
    } else {
        $query = 'SELECT A.a_id, A.a_name, A.a_active as active , B.a_name as addedBy, A.a_phone, A.a_country, A.a_email FROM `admin` A, `admin` B WHERE B.a_id = A.a_added_by AND A.a_id = ' . $id;
    }


    $result = @mysqli_query($conn, $query);

    if (@mysqli_num_rows($result) == 1) {
        $row = @mysqli_fetch_array($result, MYSQLI_BOTH);
    } else {
        header('Location: ./dashboard.php');
    }
    @mysqli_free_result($result);
    @mysqli_close($conn);
}

if (isset($_GET['id']) && isset($_POST['submit'])) {
    require '../assets/server_configuration/config.php';
    $id = (int) mysqli_real_escape_string($conn, $_GET['id']);
    $id = is_numeric($id) ? $id : NULL;

    if (empty($_POST['adminName']) || empty($_POST['adminPhone']) || empty($_POST['adminEmail'])) {
        $empty = true;
    } else {
        $name = mysqli_real_escape_string($conn, trim($_POST['adminName']));
        $phone = mysqli_real_escape_string($conn, trim($_POST['adminPhone']));
        $email = mysqli_real_escape_string($conn, trim($_POST['adminEmail']));
        $country = mysqli_real_escape_string($conn, trim($_POST['adminCountry']));
        
        $query = "SELECT `a_id`, `a_active` FROM `admin` WHERE `a_email` = '$email' OR `a_phone` = '$phone' OR `a_name` = '$name'";
        $result = @mysqli_query($conn, $query);
        if (@mysqli_num_rows($result) == 2) {
            $duplicate = true;
        } else {
            if($id != $_COOKIE['a_id']){
                if (isset($_POST['active'])) {
                    $active = 1;
                }
                else{
                    $active = 0;
                }
            }
            else{
                $active = 1;
            }
            

            $query = "UPDATE `admin` SET `a_name` = '$name', `a_active`= '$active', `a_phone` = '$phone' , `a_country` = '$country', `a_email`= '$email' WHERE `a_id` = '$id'"; 
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
                                * fields are required, please fill them.
                </div>';
            } else if (isset($failed) && $failed) {
                echo '<div class="failed p-2 my-3">
                Something went wrong, Please try again later. <br>
                That could also happen if you did not change anything
                </div>';
            } else if (isset($duplicate) && $duplicate) {
                echo '<div class="failed p-2 my-3">
                Email of phone number is already added, please choose another one.
                </div>';
            }
            ?>

            <label for="adminName">NAME*</label>
            <input type="text" name="adminName" id="adminName" class="font-weight-bold" value="<?= $row['a_name'] ?>" required>

            <label for="adminPhone">PHONE*</label>
            <input type="tel" name="adminPhone" id="adminPhone" class="font-weight-bold" value="<?= $row['a_phone'] ?>" required>

            <label for="adminEmail">EMAIL*</label>
            <input type="email" name="adminEmail" id="adminEmail" class="font-weight-bold" value="<?= $row['a_email'] ?>" required>

            <label for="adminCountry">COUNTRY</label>
            <input type="text" name="adminCountry" id="adminCountry" class="font-weight-bold" value="<?= $row['a_country'] ?>">


            <label for="addedBy">ADDED BY</label>
            <input type="text" id="addedBy" class="font-weight-bold" value="<?= $row['addedBy'] ?>" disabled>


            <?php
            if ($row['a_id'] != $_COOKIE['a_id']) {

                if ($row['active'] == 1) {

                    echo '
                    <div class="form-check">
                    <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" name="active" value="1" checked>Active
                    </label>
                    </div>
                    ';
                } else {

                    echo '
                    <div class="form-check">
                    <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" name="active" value="1">Active
                    </label>
                    </div>
                    ';
                }
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