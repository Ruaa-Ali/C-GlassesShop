<?php
require '../assets/server_configuration/config.php';
if (!isset($_COOKIE['a_name'], $_COOKIE['a_id'])) {
    header("Location: ./index.php");
} else {
    $adminQuery = "SELECT a_active FROM `admin` WHERE a_id = $_COOKIE[a_id]";
    $adminResult = @mysqli_query($conn, $adminQuery);
    $activated = @mysqli_fetch_assoc($adminResult);
    if ($activated['a_active'] != 1) {
        header("Location: ./index.php");
    }
}
?>


<?php 
$revenueStatQuery = 'SELECT SUM(`o_price`) AS `revenue` FROM `order` WHERE `o_state` = 2';
$revenueStatResult = @mysqli_query($conn, $revenueStatQuery);
$revenueStatRow = mysqli_fetch_assoc($revenueStatResult);

$customerStatQuery = 'SELECT COUNT(`c_id`) AS `customers` FROM `customer`';
$customerStatResult = @mysqli_query($conn, $customerStatQuery);
$customerStatRow = mysqli_fetch_assoc($customerStatResult);

$canceledStatQuery = 'SELECT COUNT(`o_id`) AS `cenceledItems` FROM `order` WHERE `o_state` = 0';
$canceledStatResult = @mysqli_query($conn, $canceledStatQuery);
$canceledStatRow = mysqli_fetch_assoc($canceledStatResult);

$orderedStatQuery = 'SELECT COUNT(`o_id`) AS `orderedItems` FROM `order` WHERE `o_state` = 1';
$orderedStatResult = @mysqli_query($conn, $orderedStatQuery);
$orderedStatRow = mysqli_fetch_assoc($orderedStatResult);

$soldStatQuery = 'SELECT COUNT(`o_id`) AS `soldItems` FROM `order` WHERE `o_state` = 2';
$soldStatResult = @mysqli_query($conn, $soldStatQuery);
$soldStatRow = mysqli_fetch_assoc($soldStatResult);

$productStatQuery = 'SELECT COUNT(`p_id`) AS `products` FROM `product` WHERE `p_active` = 1';
$productStatResult = @mysqli_query($conn, $productStatQuery);
$productStatRow = mysqli_fetch_assoc($productStatResult);

?>

<?php
session_start();
if ((isset($_SESSION['adminDelFailed']) && $_SESSION['adminDelFailed'] != '') || (isset($_SESSION['productDelFailed']) && $_SESSION['productDelFailed'] != '') || (isset($_SESSION['customerChangeFailed']) && $_SESSION['customerChangeFailed'] != '')) {
    $sessionVars = 1;
    echo $_SESSION['adminDelFailed'];
} else {
    $sessionVars = 0;
}
?>




<?php

$adminQuery = "SELECT A.a_id, A.a_name, A.a_active as active , B.a_name as addedBy, A.a_phone, A.a_country, A.a_email FROM `admin` A, `admin` B WHERE B.a_id = A.a_added_by";
$adminResult = @mysqli_query($conn, $adminQuery);
?>

<?php
$productQuery = "SELECT `p_id`, `p_name`, `p_model`, `p_color`, `a_name`, `p_img_front`, `p_img_back`, `p_active` FROM `product` LEFT JOIN admin ON product.p_added_by = admin.a_id";
$productResult = @mysqli_query($conn, $productQuery);

?>



<?php
$canceledOrdersQuery = "SELECT `o_id`, `o_quantity`, `order`.`p_id`, `p_img_front`, `p_price` AS `unitPrice`, `o_price` AS `totalPrice`, `p_color`, `p_model`, `p_name`, `order`.`c_id`, `c_name`, `c_address`, `o_discount` FROM `order` INNER JOIN `product` USING (`p_id`) INNER JOIN `customer` USING (`c_id`) WHERE `o_state` = 0";
$canceledOrdersResult = @mysqli_query($conn, $canceledOrdersQuery);

$soldOrdersQuery = "SELECT `o_id`, `o_quantity`, `order`.`p_id`, `p_img_front`, `p_price` AS `unitPrice`, `o_price` AS `totalPrice`, `p_color`, `p_model`, `p_name`, `order`.`c_id`, `c_name`, `c_address`, `o_discount` FROM `order` INNER JOIN `product` USING (`p_id`) INNER JOIN `customer` USING (`c_id`) WHERE `o_state` = 2";
$soldOrdersResult = @mysqli_query($conn, $soldOrdersQuery);

$orderdOrdersQuery = "SELECT `o_id`, `o_quantity`, `order`.`p_id`, `p_img_front`, `p_price` AS `unitPrice`, `o_price` AS `totalPrice`, `p_color`, `p_model`, `p_name`, `order`.`c_id`, `c_name`, `c_address`, `o_discount` FROM `order` INNER JOIN `product` USING (`p_id`) INNER JOIN `customer` USING (`c_id`) WHERE `o_state` = 1";
$orderdOrdersResult = @mysqli_query($conn, $orderdOrdersQuery);
?>

<?php
$customerQuery = 'SELECT `c_id`, `c_name`, `c_email`, `c_address`, `c_country`, `c_active` FROM `customer`';
$customerResult = @mysqli_query($conn, $customerQuery);
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
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/forCommon.css">


    <title>C - Admin Dashboard</title>
</head>

<body>
    <?php
    require_once('./header.php');
    require_once('./sideNav.php');
    ?>


    <!-- main tabs -->
    <div class="main p-4 tab-content">

        <!-- dashboard tab -->
        <div class="tab-pane fade active show" id="dashboard-tab" role="tabpanel">


            <div class="row">

                <!-- Customer Card Example -->
                <div class="col-12 col-md-6 col-lg-4 my-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">
                                        Customers</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $customerStatRow['customers']?></div>
                                </div>
                                <div class="col-auto">
                                    <span class="material-icons">people</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Revenue Card Example -->
                <div class="col-12 col-md-6 col-lg-4 my-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">
                                        Revenue</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo $revenueStatRow['revenue']?></div>
                                </div>
                                <div class="col-auto">
                                    <span class="material-icons">paid</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- product Card Example -->
                <div class="col-12 col-md-6 col-lg-4 my-3">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">
                                        Products</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $productStatRow['products']?></div>
                                </div>
                                <div class="col-auto">
                                    <span class="material-icons">inventory_2</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Sold product Card Example -->
                <div class="col-12 col-md-6 col-lg-4 my-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">
                                        Sold Product</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $soldStatRow['soldItems']?></div>
                                </div>
                                <div class="col-auto">
                                    <span class="material-icons">check_circle</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Ordered product Card Example -->
                <div class="col-12 col-md-6 col-lg-4 my-3">
                    <div class="card border-left-sucess shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">
                                        Ordered Product</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $orderedStatRow['orderedItems']?></div>
                                </div>
                                <div class="col-auto">
                                    <span class="material-icons">shopping_bag</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cancele pro Card Example -->
                <div class="col-12 col-md-6 col-lg-4 my-3">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-muted text-uppercase mb-1">
                                        Canceled Product</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $canceledStatRow['cenceledItems']?></div>
                                </div>
                                <div class="col-auto">
                                    <span class="material-icons">cancel</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- dashboard tab -->



        <!-- orders tab -->
        <div class="tab-pane fade" id="orders-tab" role="tabpanel">

            <ul class="nav nav-tabs" id="orders-types" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active header-link font-weight-bold" id="home-tab" data-toggle="tab" href="#sold" role="tab" aria-controls="home" aria-selected="true">Sold</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link header-link font-weight-bold" id="profile-tab" data-toggle="tab" href="#canceled" role="tab" aria-controls="profile" aria-selected="false">Canceled</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link header-link font-weight-bold" id="contact-tab" data-toggle="tab" href="#ordered" role="tab" aria-controls="contact" aria-selected="false">Ordered</a>
                </li>
            </ul>

            <div class="tab-content" id="orders-types-content">

                <!-- sold orders tab -->
                <div class="tab-pane fade show active mt-3" id="sold" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-lg-responsive w-100">
                            <thead class="table-head thead-dark">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer ID</th>
                                    <th>Customer Name</th>
                                    <th>Product</th>
                                    <th>Model</th>
                                    <th>Color</th>
                                    <th>Image</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Address</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                while ($soldRow = @mysqli_fetch_assoc($soldOrdersResult)) {

                                    echo "<tr>
                                        <td>$soldRow[o_id]</td>
                                        <td>$soldRow[c_id]</td>
                                        <td>$soldRow[c_name]</td>
                                        <td>$soldRow[p_name]</td>
                                        <td>$soldRow[p_model]</td>
                                        <td>$soldRow[p_color]</td>
                                        <td><img src='../assets/img/products/$soldRow[p_img_front]' class='product-img' style='width: 50px; height: 50px;'></td>
                                        <td>$soldRow[unitPrice]</td>
                                        <td>$soldRow[o_quantity]</td>
                                        <td>$soldRow[c_address]</td>
                                        <td>$soldRow[totalPrice]</td>
                                        </tr>";
                                }

                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- sold orders tab -->

                <!-- canceled orders tab -->
                <div class="tab-pane fade mt-3" id="canceled" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-lg-responsive w-100">
                            <thead class="table-head thead-dark">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer ID</th>
                                    <th>Customer Name</th>
                                    <th>Product</th>
                                    <th>Model</th>
                                    <th>Color</th>
                                    <th>Image</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Address</th>
                                    <th>Total Price</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($canceledRow = @mysqli_fetch_assoc($canceledOrdersResult)) {

                                    echo "<tr>
                                    <td>$canceledRow[o_id]</td>
                                    <td>$canceledRow[c_id]</td>
                                    <td>$canceledRow[c_name]</td>
                                    <td>$canceledRow[p_name]</td>
                                    <td>$canceledRow[p_model]</td>
                                    <td>$canceledRow[p_color]</td>
                                    <td><img src='../assets/img/products/$canceledRow[p_img_front]' class='product-img' style='width: 50px; height: 50px;'></td>
                                    <td>$canceledRow[unitPrice]</td>
                                    <td>$canceledRow[o_quantity]</td>
                                    <td>$canceledRow[c_address]</td>
                                    <td>$canceledRow[totalPrice]</td>
                                    </tr>";
                                }

                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- canceled orders tab -->


                <!-- ordered orders tab -->
                <div class="tab-pane fade mt-3" id="ordered" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped table-lg-responsive w-100">
                            <thead class="table-head thead-dark">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer ID</th>
                                    <th>Customer Name</th>
                                    <th>Product</th>
                                    <th>Model</th>
                                    <th>Color</th>
                                    <th>Image</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Address</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                while ($orderdRow = @mysqli_fetch_assoc($orderdOrdersResult)) {

                                    echo "<tr>
                                        <td>$orderdRow[o_id]</td>
                                        <td>$orderdRow[c_id]</td>
                                        <td>$orderdRow[c_name]</td>
                                        <td>$orderdRow[p_name]</td>
                                        <td>$orderdRow[p_model]</td>
                                        <td>$orderdRow[p_color]</td>
                                        <td><img src='../assets/img/products/$orderdRow[p_img_front]' class='product-img' style='width: 50px; height: 50px;'></td>
                                        <td>$orderdRow[unitPrice]</td>
                                        <td>$orderdRow[o_quantity]</td>
                                        <td>$orderdRow[c_address]</td>
                                        <td>$orderdRow[totalPrice]</td>
                                        </tr>";
                                }

                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- ordered orders tab -->


            </div>
        </div>
        <!-- orders tab -->



        <!-- products tab -->
        <div class="tab-pane fade" id="products-tab" role="tabpanel">
            <div class="mb-4 d-flex align-items-center justify-content-end">
                <a href="./addProduct.php" class="add-link d-flex align-items-center">
                    <span class="material-icons add-icon">add</span>Add New Product
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-lg-responsive">
                    <thead class="table-head thead-dark">
                        <tr class="">
                            <th>ID</th>
                            <th>Name</th>
                            <th>Model</th>
                            <th>Color</th>
                            <th>Front Image</th>
                            <th>Back Image</th>
                            <th>Status</th>
                            <th>Added By</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($productRow = @mysqli_fetch_assoc($productResult)) {
                            if ($productRow['p_active'] == 1) {
                                $active = 'Active';
                            } else {
                                $active = 'Suspended';
                            }
                            echo "<tr>
                                <td>$productRow[p_id]</td>
                                <td>$productRow[p_name]</td>
                                <td>$productRow[p_model]</td>
                                <td>$productRow[p_color]</td>
                                <td><img src='../assets/img/products/$productRow[p_img_front]' class='product-img'></td>
                                <td><img src='../assets/img/products/$productRow[p_img_back]' class='product-img'></td>
                                <td>$active</td>
                                <td>$productRow[a_name]</td>
                                <td>
                                <a href='./updateProduct.php?id=$productRow[p_id]' class='action-icon'>
                                <span class='material-icons'>edit</span>
                                </a>
                                </td>
                                <td> 
                                <a onclick='delCustomer($productRow[p_id])' class='action-icon'>
                                <span class='material-icons'>clear</span>
                                </a></td>
                                </tr>";
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- products tab -->



        <!-- customers tab -->
        <div class="tab-pane fade" id="customers-tab" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-lg-responsive w-100">
                    <thead class="table-head thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Country</th>
                            <th>Address</th>
                            <th>Active</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        while ($customerRow = @mysqli_fetch_assoc($customerResult)) {

                            echo "<tr>
                                <td>$customerRow[c_id]</td>
                                <td>$customerRow[c_name]</td>
                                <td>$customerRow[c_email]</td>
                                <td>$customerRow[c_country]</td>
                                <td>$customerRow[c_address]</td>";
                            if ($customerRow['c_active'] == 1) {
                                echo "<td>Active</td>
                                    <td><a href='./db/changeCustomer.php?id=$customerRow[c_id]'
                                    <span class='material-icons toggle-on'>
                                    toggle_on
                                    </span></a>
                                    </td></tr>
                                    ";
                            } else {
                                echo "<td>Suspended</td>
                                <td><a href='./db/changeCustomer.php?id=$customerRow[c_id]'
                                    <span class='material-icons toggle-off'>
                                    toggle_off
                                    </span></a>
                                    </td></tr>
                                ";
                            }
                        }

                        ?>

                    </tbody>
                </table>
            </div>
        </div>
        <!-- customers tab -->



        <!-- admins tab -->
        <div class="tab-pane fade" id="admin-tab" role="tabpanel">
            <div class="mb-4 d-flex align-items-center justify-content-end">
                <a href="./addAdmin.php" class="add-link d-flex align-items-center">
                    <span class="material-icons add-icon">add</span>Add New Admin
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-striped w-100">
                    <thead class="table-head thead-dark">
                        <tr class="">
                            <th>ID</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Country</th>
                            <th>Added By</th>
                            <th>Status</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($adminRow = @mysqli_fetch_assoc($adminResult)) {
                            if ($adminRow['active'] == 1) {
                                $active = 'Active';
                            } else {
                                $active = 'Suspended';
                            }
                            if ($adminRow['a_id'] == $_COOKIE['a_id']) {
                                echo '<tr class="table-success">';
                            } else {
                                echo '<tr>';
                            }
                            echo "
                            <td>$adminRow[a_id]</td>
                            <td>$adminRow[a_name]</td>
                            <td>$adminRow[a_phone]</td>
                            <td>$adminRow[a_email]</td>
                            <td>$adminRow[a_country]</td>
                            <td>$adminRow[addedBy]</td>

                            <td>$active</td>
                            <td>
                            <a href='./updateAdmin.php?id=$adminRow[a_id]' class='action-icon'>
                            <span class='material-icons'>edit</span>
                            </a>
                            </td>";

                            if ($adminRow['a_id'] == $_COOKIE['a_id']) {
                                echo '<td></td></tr>';
                            } else {
                                echo "<td>
                                <a onclick='delAdmin($adminRow[a_id])' class='action-icon'>
                                <span class='material-icons'>clear</span>
                                </a>
                                </td>

                                </tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- customers tab -->


    </div>



    <!-- modals -->
    <div id="alert" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Attention!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    if (isset($_SESSION['adminDelFailed']) && $_SESSION['adminDelFailed'] != '') {
                        echo "<p>$_SESSION[adminDelFailed]</p>";
                        $_SESSION['adminDelFailed'] = '';
                    } else if (isset($_SESSION['productDelFailed']) && $_SESSION['productDelFailed'] != '') {
                        echo "<p>$_SESSION[productDelFailed]</p>";
                        $_SESSION['productDelFailed'] = '';
                    } else if (isset($_SESSION['customerChangeFailed']) && $_SESSION['customerChangeFailed'] != '') {
                        echo "<p>$_SESSION[customerChangeFailed]</p>";
                        $_SESSION['customerChangeFailed'] = '';
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="alert-btn" value="OK" data-dismiss="modal">
                </div>
            </div>
        </div>
    </div>
    <!-- modals -->


    <?php
    @mysqli_free_result($productResult);
    @mysqli_free_result($adminResult);
    @mysqli_free_result($canceledOrdersResult);
    @mysqli_free_result($soldOrdersResult);
    @mysqli_free_result($orderdOrdersResult);
    @mysqli_free_result($customerResult);
    @mysqli_free_result($revenueStatResult);
    @mysqli_free_result($customerStatResult);
    @mysqli_free_result($canceledStatResult);
    @mysqli_free_result($orderedStatResult);
    @mysqli_free_result($soldStatResult);
    @mysqli_free_result($productStatResult);

    @mysqli_close($conn);
    ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
        var sessionVars = <?= $sessionVars ?>
    </script>
    <script src='./js/app.js'></script>

</body>

</html>