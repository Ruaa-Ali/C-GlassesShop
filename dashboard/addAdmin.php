<?php
require './../assets/server_configuration/config.php';
if (!isset($_COOKIE['a_name'], $_COOKIE['a_id'])) {
    header("Location: ./index.php");
}
?>



<?php
if (isset($_POST['submit'])) {
    require './../assets/server_configuration/config.php';

    if (empty($_POST['adminName']) || empty($_POST['adminPass']) || empty($_POST['adminPhone']) || empty($_POST['adminEmail'])) {
        $empty = true;
    } else {
        $name = mysqli_real_escape_string($conn, trim($_POST['adminName']));
        $phone = mysqli_real_escape_string($conn, trim($_POST['adminPhone']));
        $email = mysqli_real_escape_string($conn, trim($_POST['adminEmail']));
        $country = mysqli_real_escape_string($conn, trim($_POST['adminCountry']));

        $query = "SELECT `a_name` FROM `admin` WHERE `a_name` = '$name' OR `a_email` = '$email' OR `a_phone` = '$phone'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) != 0) {
            $duplicate = true;
        } else {
            $currentAdminID = $_COOKIE['a_id'];
            $password = mysqli_real_escape_string($conn, trim($_POST['adminPass']));
            $pass = sha1($password);
            $query = "INSERT INTO `admin`(`a_name`, `a_active`, `a_added_by`, `a_pass`, `a_phone`, `a_country`, `a_email`) VALUES ('$name', '1', '$currentAdminID', '$pass', '$phone', '$country', '$email')" or $failed = true;;
            $result = mysqli_query($conn, $query);
            if (mysqli_affected_rows($conn) == 1) {
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


    <title>C - Add Admin</title>
</head>

<body>
    <?php
    require_once('./header.php');
    ?>



    <div class="p-5 ">
        <form method="POST" class="d-flex flex-column mx-auto">
            <h2 class="form-heading text-center">ADD NEW ADMIN</h2>
            <?php
            if (isset($empty) && $empty) {
                echo '<div class="failed p-2 my-3">
                                * fields are required, please fill them.
                            </div>';
            } else if (isset($failed) && $failed) {
                echo '<div class="failed p-2 my-3">
                            Something went wrong, Please try again later.
                            </div>';
            } else if (isset($duplicate) && $duplicate) {
                echo '<div class="failed p-2 my-3">
                            Name, phone, or email has been added before.
                            </div>';
            }
            ?>

            <label for="adminName">NAME*</label>
            <input type="text" name="adminName" id="adminName" class="font-weight-bold" required>

            <label for="adminPhone">PHONE*</label>
            <input type="tel" name="adminPhone" id="adminPhone" class="font-weight-bold" required>

            <label for="adminEmail">EMAIL*</label>
            <input type="email" name="adminEmail" id="adminEmail" class="font-weight-bold" required>

            <label for="adminCountry">COUNTRY</label>
            <input type="text" name="adminCountry" id="adminCountry" class="font-weight-bold">

            <label for="adminPass">PASSWORD*</label>
            <input type="password" name="adminPass" id="adminPass" class="font-weight-bold" required>

            <input type="submit" name="submit" value="ADD" class="my-3">
        </form>
    </div>




    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

</html>