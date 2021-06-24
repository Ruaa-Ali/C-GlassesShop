<?php
if (isset($_COOKIE['c_name'], $_COOKIE['c_id'])) {
    header("Location: ./index.php");
} else {
    if (isset($_POST['submit'])) {
        if (empty($_POST['logEmail']) || empty($_POST['logPass'])) {
            $empty = true;
        } else {
            require './assets/server_configuration/config.php';
            $email = mysqli_real_escape_string($conn, $_POST['logEmail']);
            $password = mysqli_real_escape_string($conn, $_POST['logPass']);
            $pass = sha1($password);
            $query = "SELECT * FROM customer WHERE c_pass = '$pass' AND c_email = '$email'";
            $result = @mysqli_query($conn, $query);
            if (@mysqli_num_rows($result) == 1) {
                $row = @mysqli_fetch_assoc($result);
                if ($row['c_active'] == 0) {
                    $suspended = true;
                } else {
                    $name = $row['c_name'];
                    $id = $row['c_id'];
                    setcookie('c_id', $id, time() + (1 * 365 * 24 * 60 * 60));
                    setcookie('c_name', $name, time() + (1 * 365 * 24 * 60 * 60));
                    header('Location: ./account.php');
                }
            } else {
                $failed = true;
            }
            @mysqli_free_result($result);
            @mysqli_close($conn);
        }
    } else if (isset($_POST['createNew'])) {
        require './assets/server_configuration/config.php';
        if (empty($_POST['signName']) || empty($_POST['signEmail']) || empty($_POST['signPass']) || empty($_POST['signPassConfirm'])) {
            $signEmpty = true;
        } else if ($_POST['signPassConfirm'] != $_POST['signPass']) {
            $notMatched = true;
        } else {

            $query = "SELECT `c_email` FROM `customer` WHERE `c_email` = '$_POST[signEmail]'";
            $result = @mysqli_query($conn, $query);
            if (@mysqli_num_rows($result) != 0) {
                $duplicateEmail = true;
            } else {

                $num = @mysqli_num_rows($result);
                $row = @mysqli_fetch_assoc($result);


                $signName = mysqli_real_escape_string($conn, trim($_POST['signName']));
                $signEmail = mysqli_real_escape_string($conn, trim($_POST['signEmail']));
                $signCountry = mysqli_real_escape_string($conn, trim($_POST['signCountry']));
                $signPass = mysqli_real_escape_string($conn, trim($_POST['signPass']));
                $pass = SHA1($signPass);
                $query = "INSERT INTO `customer`(`c_name`, `c_pass`, `c_email`, `c_country`) VALUES ('$signName', '$pass', '$signEmail', '$signCountry')" or $signFailed = true;;
                $result = @mysqli_query($conn, $query);
                if (@mysqli_affected_rows($conn) == 1) {
                    $id = @mysqli_insert_id($conn);
                    setcookie('c_id', $id, time() + 1000);
                    setcookie('c_name', $signName, time() + 1000);
                    header('Location: ./account.php');
                } else {
                    $signFailed = true;
                }
                @mysqli_free_result($result);
                @mysqli_close($conn);
            }
        }
    }
}



?>


<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>C - Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <link rel="stylesheet" href="./assets/lib/fonts/poppins/poppins.css">
    <link rel='stylesheet' href='./css/forLogin.css'>
    <link rel='stylesheet' href='./css/forCommon.css'>
</head>

<body>


    <div class="wrapper">
        <?php
        require_once './header.php';
        ?>

        <div class="main container-fluid position-relative">
            <?php require_once './sideNav.php'; ?>
            <div class="row">
                <div class="col-lg-6 col-12 mb-5 mb-lg-0 login">

                    <form method="POST" id="log-form" class="d-flex flex-column mx-auto">
                        <h1 class="form-heading">LOGIN</h1>
                        <?php
                        if (isset($empty) && $empty) {
                            echo '<div class="failed p-2 my-3">
                                All fields are required, please fill them all.
                            </div>';
                        } else if (isset($failed) && $failed) {
                            echo '<div class="failed p-2 my-3">
                            Incorrect email or password.
                            </div>';
                            
                        } else if (isset($suspended) && $suspended) {
                        echo '<div class="failed p-2 my-3">
                        Your account is suspended.
                        </div>';
                    }
                        ?>

                        <label for="log-email">EMAIL</label>
                        <input type="text" name="logEmail" id="log-email">

                        <label for="log-pass">PASSWORD</label>
                        <input type="password" name="logPass" id="log-pass">

                        <input type="submit" name="submit" value="SIGN IN" class="my-3">

                        <a href="#" class="forgot-pass text-dark">Forgot password?</a>
                    </form>


                </div>
                <div class="col-lg-6 col-12 mt-5 mt-lg-0">
                    <form method="POST" id="sign-form" class="d-flex flex-column mx-auto">
                        <h1 class="form-heading">CREATE NEW ACCOUNT</h1>

                        <?php
                        if (isset($signEmpty) && $signEmpty) {
                            echo '<div class="failed p-2 my-3">
                                * fields are required, please fill them all.
                            </div>';
                        } else if (isset($notMatched) && $notMatched) {
                            echo '<div class="failed p-2 my-3">
                            Passwords do not match, please try again.
                            </div>';
                        } else if (isset($signFailed) && $signFailed) {
                            echo '<div class="failed p-2 my-3">
                            Something went wrong, please try again later.
                            </div>';
                        } else if (isset($duplicateEmail) && $duplicateEmail) {
                            echo '<div class="failed p-2 my-3">
                            This email is already registered.
                            </div>';
                        }
                        ?>

                        <label for="name">NAME*</label>
                        <input type="text" name="signName" id="name">

                        <label for="sign-email">EMAIL*</label>
                        <input type="email" name="signEmail" id="sign-email">

                        <label for="sign-country">COUNTRY</label>
                        <input type="text" name="signCountry" id="sign-country">

                        <label for="sign-pass">PASSWORD*</label>
                        <input type="password" name="signPass" id="sign-pass">

                        <label for="sign-passC">CONFIRMED PASSWORD*</label>
                        <input type="password" name="signPassConfirm" id="sign-passC">

                        <input type="submit" name="createNew" value="SIGN UP" class="my-3">

                    </form>
                </div>
            </div>
        </div>


        <?php
        require_once './footer.php';
        ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src='./js/app.js'></script>
</body>

</html>