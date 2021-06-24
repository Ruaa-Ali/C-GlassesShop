<div id="side-nav" class="position-absolute d-none d-sm-none">
    <ul class="side-nav-list mx-auto w-100 d-flex flex-column align-items-center mt-2">

        <?php 
        if($logged){
            echo '<li>
            <p class="m-0 user">Hello <span class="font-weight-bold">Ruaa</span> </p>
            </li>
            <li><a class="account-item header-link" href="./account.php">Your Account</a></li>
            <li class="position-relative">
            <a href="./cart.php" class="header-link d-flex justify-content-center algin-items-end">
            Your Cart
            <div class="cart position-relative d-flex justify-content-end align-items-center ml-2">
            <span class="material-icons">shopping_bag</span>';

            if($ordersEmpty == 0){
                echo"
                <span id='cart-number' class='cart-number position-absolute'>$ordersCount</span>
                </div>
                </a>
                </li>
                ";
            }
            else{
                echo'
                </div>
                </a>
                </li>
                ';   
            }
        }
        ?>
        

        

        <li><a href="./about-us.php#story" class="header-link"> Our Story</a></li>
        <li><a href="./about-us.php#vision" class="header-link">Out Vision</a></li>


        <?php 
        if($logged){
            echo '<li><a class="account-item header-link d-flex align-items-start" href="./logout.php">
            Logout
            <span class="material-icons pointer header-link pl-2">
            logout
            </span>
            </a></li>';
        }
        else{
            echo '<li><a class="account-item header-link d-flex align-items-start" href="./login.php">
            login
            <span class="material-icons pointer header-link pl-2">
            login
            </span>
            </a></li>';
        }
        ?>

        


    </ul>
</div>