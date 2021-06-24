<?php
if (isset($_COOKIE['c_id'], $_COOKIE['c_name'])) {
    require('./assets/server_configuration/config.php');
    $logged = 1;
    $name = $_COOKIE['c_name'];
    $id = (int) mysqli_real_escape_string($conn, $_COOKIE['c_id']);

    $ordersEmpty = 0;
    $ordersCountQuery = "SELECT COUNT(`o_id`) AS `count` FROM `order` WHERE `o_state` = 1 AND c_id = $id";
    $ordersCountResult = @mysqli_query($conn, $ordersCountQuery);
    if (@mysqli_num_rows($ordersCountResult) != 0) {
        $ordersCountRow = @mysqli_fetch_assoc($ordersCountResult);
        $ordersCount = $ordersCountRow['count'];
    }
    else{
        $ordersEmpty = 1;
    }


} else {
    $ordersEmpty = 1;
    $logged = 0;
}

?>





<div class="header d-flex justify-content-between px-4 align-items-center position-relative">
    <div class="menu">
        <?php
        if ($logged) {
            echo '<div id="account-menu-toggle" class="d-none d-sm-flex pointer " data-toggle="dropdown">
            <p class="m-0">Hello <span class="font-weight-bold">' . $name . '</span> </p>
            <span class="material-icons">
            arrow_drop_down
            </span>
            </div>

            <div class="dropdown-menu account-menu m-0 py-1" aria-labelledby="account-menu-toggle">
            <a class="dropdown-item account-item py-2" href="./account.php">Your Account</a>
            <hr class="my-1">
            <a class="dropdown-item account-item d-flex align-items-start justify-content-between py-2" href="./logout.php">
            Logout
            <span class="material-icons pointer header-link">
            logout
            </span>
            </a>
            </div>';
        } else {
            echo '<a href="./login.php">
            <span class="material-icons d-none d-sm-block pointer header-link">
            login
            </span>
            </a>';
        }

        ?>



        <span id="burger" class="material-icons pointer d-block d-sm-none">
            menu
        </span>
    </div>
    <div class="logo">
        <a href="./index.php" class="header-link">C</a>
    </div>
    <div class="list ">
        <ul class="list-unstyled d-none d-sm-flex justify-content-between align-items-center m-0">
            <li class="px-lg-4 pl-md-4 d-none d-md-block"><a href="./about-us.php#story" class="header-link">OUR STORY</a></li>
            <li class="px-lg-4 pl-md-4 d-none d-md-block"><a href="./about-us.php#vision" class="header-link">OUR VISION</a></li>
            <li class="px-lg-4 pl-md-4 position-relative">

                <a href="./cart.php" class="header-link cart position-relative d-flex justify-content-center align-items-center">
                    <span class="material-icons">shopping_bag</span>
                    <?php 
                    if($ordersEmpty == 0){
                        echo "<span class='cart-number position-absolute'>$ordersCount</span>";
                    }
                    ?>
                    
                </a>

            </li>
        </ul>
    </div>
</div>