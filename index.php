<?php
require './assets/server_configuration/config.php';
$productQuery = "SELECT `p_id`, `p_price`, `p_name`, `p_model`, `p_img_front`, `p_img_back` FROM `product` WHERE `p_active` = 1";
$productResult = @mysqli_query($conn, $productQuery);
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>C - Glasses Shop</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="./assets/lib/fonts/poppins/poppins.css">
    <link rel='stylesheet' href='./css/style.css'>
    <link rel='stylesheet' href='./css/forCommon.css'>
</head>




<!-- a simple clone of this website -->
<!-- https://barnerbrand.com/ -->


<div class="wrapper">


    <?php
    require './header.php';
    ?>
    <div class="main position-relative">
        <?php require_once './sideNav.php'; ?>
        <div id="hero-slider" class="hero carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <!-- <div class="aa"></div> -->
                    <img src="./assets/img/banner/BANNERS-WEB-DESKTOP.jpg" class="w-100 d-none d-sm-block">
                    <img src="./assets/img/banner/BANNERS-WEB-MOBILE.jpg" class="w-100 d-block d-sm-none">
                </div>
                <div class="carousel-item">
                    <img src="./assets/img/banner/BANNERS-WEB-DESKTOP2.jpg" class="w-100 d-none d-sm-block">
                    <img src="./assets/img/banner/BANNERS-WEB-MOBILE2.jpg" class="w-100 d-block d-sm-none">
                </div>
                <div class="carousel-item">
                    <img src="./assets/img/banner/BANNERS-WEB-DESKTOP3.jpg" class="w-100 d-none d-sm-block">
                    <img src="./assets/img/banner/BANNERS-WEB-MOBILE3.jpg" class="w-100 d-block d-sm-none">
                </div>
            </div>
            <a class="carousel-control-prev" href="#hero-slider" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#hero-slider" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>

        <div class="benefits container-fluid text-center p-5">
            <h2 class="my-5">BENEFITS OF USING C BLUE LIGHT GLASSES</h2>

            <div class="row my-5">
                <div class="col-12 col-sm-6 col-lg-3">
                    <img src="./assets/img/benefits/icons1.png" alt="">
                    <p class="benefit-text font-weight-light">Look cool<br>wherever you go</p>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <img src="./assets/img/benefits/icons2.png" alt="">
                    <p class="benefit-text font-weight-light">Reduce eye<br>strain</p>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <img src="./assets/img/benefits/icons3.png" alt="">
                    <p class="benefit-text font-weight-light">Improve your<br>sleep</p>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <img src="./assets/img/benefits/icons4.png" alt="">
                    <p class="benefit-text font-weight-light">Enhance<br>your wellbeing</p>
                </div>
            </div>
            <div class="row my-5">
                <div class="col-12 col-sm-6 col-lg-3">
                    <img src="./assets/img/benefits/icons5.png" alt="">
                    <p class="benefit-text font-weight-light"></p>Super<br>comfortable</p>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <img src="./assets/img/benefits/icons6.png" alt="">
                    <p class="benefit-text font-weight-light">So light you won't<br>realize you're wearing them</p>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <img src="./assets/img/benefits/icons7.png" alt="">
                    <p class="benefit-text font-weight-light">Cool<br>Packaging</p>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <img src="./assets/img/benefits/icons8.png" alt="">
                    <p class="benefit-text font-weight-light">Free<br>Accessories</p>
                </div>
            </div>
        </div>


        <div id="products" class="products container-fluid py-5">
            <h2 class="text-center">METAL COLLECTION MAN</h2>
            <div class="row px-5">

                <?php
                while ($productRow = @mysqli_fetch_assoc($productResult)) {
                    echo "
                    <div class='product-card col-12 col-md-6 col-lg-4 col-xl-3 text-center my-3'>
                    <div class='product-inner pb-5'>
                    <div class='product-img pb-3'>
                    <img src='./assets/img/products/$productRow[p_img_front]' class='w-100 front-img p-2'>
                    <img src='./assets/img/products/$productRow[p_img_back]' class='w-100 back-img p-2'>
                    </div>
                    <div class='product-details d-flex justify-content-lg-center justify-content-between px-3'>
                    <div class='details-text text-left text-lg-center'>
                    <strong class='product-name'>$productRow[p_name]</strong>
                    <p class='product-price'>$$productRow[p_price] $productRow[p_model]</p>
                    </div>
                    <div class='details-icons d-lg-none d-block'>
                    <img src='./assets/img/products/icon-glasses.png' width='40px'>
                    <img src='./assets/img/products/icon-face.png' width='40px'>
                    </div>

                    </div>
                    <div class='pt-3 px-3'>
                    <button onclick='cart($productRow[p_id], $logged)' class='product-btn mx-lg-auto w-100'>ADD TO CART</button>
                    </div>
                    </div>
                    </div>
                    
                    ";
                }
                ?>

            </div>
        </div>

    </div>
    <?php
    include './footer.php';
    ?>
</div>

<div id="login-alert" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alert</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>please <a href="./login.php">log in</a> to place your order</p>
            </div>
            <div class="modal-footer">
                <input type="submit" class="alert-btn" value="OK" data-dismiss="modal">
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src='./js/app.js'></script>

</body>

</html>