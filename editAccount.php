<?php
if (!isset($_COOKIE['c_id'], $_COOKIE['c_name'])) {
    header("Location: ./index.php");
} else {
    $id = $_COOKIE['c_id'];
    require './assets/server_configuration/config.php';
    $query = "SELECT c_name,c_email, c_address, c_country FROM customer WHERE c_id = '$id'";
    $result = @mysqli_query($conn, $query);
    if (@mysqli_num_rows($result) == 1) {
        $row = @mysqli_fetch_assoc($result);
        @mysqli_free_result($result);
    } else {
        @mysqli_free_result($result);
        header('Location: ./account.php');
    }
}

if (isset($_POST['updateAccount'])) {
    if (empty($_POST['updateName']) || empty($_POST['updateEmail']) || empty($_POST['updateName'])) {
        $empty = true;
    } else {
        $id = $_COOKIE['c_id'];
        $name = mysqli_real_escape_string($conn, $_POST['updateName']);
        $email = mysqli_real_escape_string($conn, $_POST['updateEmail']);
        $country = mysqli_real_escape_string($conn, $_POST['updateCountry']);
        $address = mysqli_real_escape_string($conn, $_POST['updateAddress']);
        $query = "UPDATE customer SET c_name = '$name', c_email = '$email', c_address='$address', c_country='$country' WHERE c_id = '$id'";
        $result = @mysqli_query($conn, $query) or $failed = true;
        if ($result) {
            setcookie('c_name', $name, time() + (1 * 365 * 24 * 60 * 60));
            header('Location: ./account.php');
        } else {
            $failed = true;
        }
        @mysqli_close($conn);
    }
}



?>


<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>C - Edit Account</title>
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
            <form method="POST" id="updateForm" class="d-flex flex-column mx-auto">
                <h2 class="form-heading">UPDATE YOUR ACCOUNT</h2>
                <?php
                if (isset($empty) && $empty) {
                    echo '<div class="failed p-2 my-3">
                                Name and Email fields are required, please fill them both.
                            </div>';
                } else if (isset($failed) && $failed) {
                    echo '<div class="failed p-2 my-3">
                            We couldn\'t update your data, Please try again later.
                            </div>';
                }
                ?>
                <label for="updateName">NAME</label>
                <input type="text" name="updateName" id="updateName" class="font-weight-bold" value="<?= $row['c_name'] ?>">

                <label for="updateEmail">EMAIL</label>
                <input type="email" name="updateEmail" id="updateEmail" class="font-weight-bold" value="<?= $row['c_email'] ?>">

                <label for="updateCountry">COUNTRY</label>
                <input type="text" name="updateCountry" id="updateCountry" class="font-weight-bold" value="<?= $row['c_country'] ?>">

                <label for="updateAddress">ADDRESS</label>
                <input type="text" name="updateAddress" id="updateAddress" class="font-weight-bold" value="<?= $row['c_address'] ?>">

                <input type="submit" name="updateAccount" value="UPDATE YOUR ACCOUNT" class="my-3">
            </form>

        </div>
        <?php
        require_once './footer.php';
        ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>