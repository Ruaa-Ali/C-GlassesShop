<?php
if (isset($_COOKIE['a_id'], $_COOKIE['a_name'])) {
    $logged = true;
    $name = $_COOKIE['a_name'];
} else {
    $logged = false;
}
?>


<div class="header d-flex justify-content-between px-4 align-items-center">
    <span id="burger" class="material-icons pointer d-block d-sm-none">
        menu
    </span>
    <div class="logo">
        <a href="./dashboard.php" class="header-link">C</a>
    </div>
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
            <!-- <a class="dropdown-item account-item py-2" href="./account.php">Your Account</a> 
            <hr class="my-1"> -->
            <a class="dropdown-item account-item d-flex align-items-start justify-content-between py-2" href="./logout.php">
                Logout
                <span class="material-icons pointer header-link">
                    logout
                </span>
            </a>
        </div>';
        } else {
            echo '<a href="./index.php">
            <span class="material-icons d-none d-sm-block pointer header-link">
                login
            </span>
        </a>';
        }

        ?>
    </div>
</div>