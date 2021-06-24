<?php
if (!isset($_COOKIE['c_id'], $_COOKIE['c_name'])) {
    header("Location: ./index.php");
} else {
    require './assets/server_configuration/config.php';
    $name = $_COOKIE['c_name'];
    $id = $_COOKIE['c_id'];
    $query = "SELECT c_address FROM customer WHERE c_id = $id";
    $result = @mysqli_query($conn, $query);
    $row = @mysqli_fetch_assoc($result);
    if (!empty($row['c_address'])) {
        $address = $row['c_address'];
    }
}
?>


<?php
$ordersQuery = "SELECT `o_id`, `o_quantity`, `order`.`p_id`, `p_img_front`, `p_price`, `p_color`, `p_model`, `p_name`, `o_price` FROM `order` INNER JOIN `product` USING (`p_id`) WHERE c_id =  $id AND `o_state` = 2";
$ordersResult = @mysqli_query($conn, $ordersQuery);
if (@mysqli_num_rows($ordersResult) == 0) {
    $empty = 1;
} else {
    $empty = 0;
}

?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>C - Your Account</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <link rel="stylesheet" href="./assets/lib/fonts/poppins/poppins.css">
    <link rel='stylesheet' href='./css/forAccount.css'>
    <link rel='stylesheet' href='./css/forCommon.css'>
</head>


<body>

    <div class="wrapper">

        <?php

        require_once './header.php';
        ?>




        <div class="main account-main position-relative">
        <?php require_once './sideNav.php'; ?>

            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <h2>MY ACCOUNT</h2>
                        <p class="font-weight-light">Welcome Back,
                            <?php
                            echo "$name!";
                            ?>
                        </p>
                        <a href="./editAccount.php" class="account-link">Edit Account</a>
                        <br>
                        <a href="./logout.php" class="account-link">logout</a>
                    </div>
                    <div class="col-12 col-md-8 d-flex justify-content-between align-items-center mt-2 p-4 addresses-sections">
                        <?php
                        if (isset($address) && !empty($address)) {
                            echo '<p class="m-0 font-weight-bold">PRIMARY ADDRESS</p>
                            <p class="m-0 font-weight-bold address">' . $address . '</p>
                            <a href="./editAccount.php" class="account-link">Change address</a>
                            ';
                        } else {
                            echo '<p class="m-0 font-weight-bold">NO ADDRESS</p>
                                <p class="m-0 font-weight-bold">No address is currently saved</p>
                                <a href="./editAccount.php" class="account-link">Add address</a>
                                ';
                        }

                        ?>
                    </div>
                </div>
                <div class="row orders-section">
                    <div class="col-12">
                        <p class="font-weight-bold m-0">
                            MY ORDERS
                        </p>
                        <hr class="mt-2 mb-4">
                        <?php
                        if ($empty == 0) {


                        ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="table-head thead-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>name</th>
                                            <th>model</th>
                                            <th>color</th>
                                            <th>Image</th>
                                            <th>Quantity</th>
                                            <th>price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php

                                    while ($ordersRow = @mysqli_fetch_assoc($ordersResult)) {
                                        echo "<tr>
                                            <td>$ordersRow[o_id]</td>
                                            <td>$ordersRow[p_name]</td>
                                            <td>$ordersRow[p_model]</td>
                                            <td>$ordersRow[p_color]</td>
                                            <td><img src='./assets/img/products/$ordersRow[p_img_front]' class='product-img'></td>
                                            <td>$ordersRow[o_quantity]</td>
                                            <td>$$ordersRow[o_price]</td>
                                        </tr>";
                                    }
                                } else {
                                    echo '<p>You haven\'t placed any orders yet</p>';
                                }


                                    ?>
                                    </tbody>
                                </table>
                            </div>
                    </div>
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