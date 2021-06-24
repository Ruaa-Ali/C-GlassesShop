<?php
if (isset($_COOKIE['a_name'], $_COOKIE['a_id'])) {
    header("Location: ./dashboard.php");
} else {
    if (isset($_POST['submit'])) {
        if (empty($_POST['name']) || empty($_POST['pass'])) {
            $empty = true;
        } else {
            require '../assets/server_configuration/config.php';
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $password = mysqli_real_escape_string($conn, $_POST['pass']);
            $pass = sha1($password);
            $query = "SELECT * FROM `admin` WHERE a_pass = '$pass' AND a_name = '$name'" or $failed = true;
            $result = @mysqli_query($conn, $query);
            if (@mysqli_num_rows($result) == 1) {
                $row = @mysqli_fetch_assoc($result);
                $name = $row['a_name'];
                $id = $row['a_id'];
                setcookie('a_id', $id, time() + (1 * 365 * 24 * 60 * 60), '/');
                setcookie('a_name', $name, time() + (1 * 365 * 24 * 60 * 60), '/');
                @mysqli_free_result($result);
                @mysqli_close($conn);
                header('Location: ./dashboard.php');
            } else {
                $wrong = true;
            }
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
    <link rel="stylesheet" href="../assets/lib/fonts/poppins/poppins.css">
    <link rel="stylesheet" href="../css/forLogin.css">
    <link rel="stylesheet" href="./css/forCommon.css">
    <link rel="stylesheet" href="./css/style.css">

    <title>C - Admin Dashboard</title>
</head>

<body>

    <?php
    require_once('./header.php');
    ?>

    <div class="p-5 login-main">
        <form method="POST" class="d-flex flex-column mx-auto">
            <h2 class="form-heading text-center">WELCOME BACK, BOSS</h2>
            <?php
            if (isset($empty) && $empty) {
                echo '<div class="failed p-2 my-3">
                                Name field is required, please fill it.
                            </div>';
            } else if (isset($failed) && $failed) {
                echo '<div class="failed p-2 my-3">
                            Something went wrong, Please try again later.
                            </div>';
            } else if (isset($wrong) && $wrong) {
                echo '<div class="failed p-2 my-3">
                            Incorrect name or password.
                            </div>';
            }
            ?>

            <label for="name">NAME</label>
            <input type="text" name="name" id="name" class="font-weight-bold" required>

            <label for="pass">PASSWORD</label>
            <input type="password" name="pass" id="pass" class="font-weight-bold" required>

            <input type="submit" name="submit" value="LOG IN" class="my-3">
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <!-- <script src='./js/app.js'></script> -->
</body>

</html>