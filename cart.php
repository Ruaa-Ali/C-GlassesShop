<?php
if (!isset($_COOKIE['c_id'], $_COOKIE['c_name'])) {
	header("Location: ./login.php");
} else {
	require './assets/server_configuration/config.php';
	$id = $_COOKIE['c_id'];
	$query = "SELECT `o_id`, `o_quantity`, `order`.`p_id`, `p_img_front`, `p_price`, `p_color`, `p_model`, `p_name`, `c_id`, `o_discount` FROM `order` INNER JOIN `product` USING (`p_id`) WHERE c_id =  $id AND `o_state` = 1";
	$result = @mysqli_query($conn, $query);

	$addressQuery = "SELECT `c_address` FROM `customer` WHERE `c_id` = $id";
	$addressResult = @mysqli_query($conn, $addressQuery);
	$empty = 0;
	if (@mysqli_num_rows($addressResult) != 0) {
		$addressRow = @mysqli_fetch_assoc($addressResult);
		if (empty($addressRow['c_address'])) {
			$empty = 1;
		}
	}

	if (isset($_POST['submit'])) {
		if (@mysqli_num_rows($result) != 0) {
			$query = '';
			while ($row = @mysqli_fetch_assoc($result)) {
				$id = $row['o_id'];
				$productPrice = $row['p_price'];
				$q = $_POST["item$id-quantity"];
				$orderPrice = ($productPrice * $q) + 5;
				$query .= "UPDATE `order` SET `o_quantity`='$q', `o_state`='2', `o_price`='$orderPrice' WHERE `o_id` = $id;";
			}
			$result = @mysqli_multi_query($conn, $query);
			// echo $query;
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
	<title>C - Your Account</title>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
	<link rel="stylesheet" href="./assets/lib/fonts/poppins/poppins.css">
	<link rel='stylesheet' href='./css/forCommon.css'>
	<link rel='stylesheet' href='./css/forLogin.css'>
	<link rel="stylesheet" href="./css/forCart.css">

</head>


<body>

	<div class="wrapper">

		<?php

		require_once './header.php';
		?>
		


		<div class="main container-fluid py-5 position-relative">
			<?php require_once './sideNav.php'; ?>
			<div class='row'>

				<div class='col-12 col-lg-8 ml-lg-4'>
					<h2>YOUR CART</h2>

					<div class='row d-none d-lg-flex'>
						<hr class='col-12 p-0'>

						<div class='col-6'>
							Product Details
						</div>
						<div class='col-2'>
							Quantity
						</div>
						<div class='col-2'>
							Price
						</div>
						<div class='col-2'>
							Total
						</div>
					</div>

					<hr>
					<form method='post' class='col-12'>

						<?php
						if (@mysqli_num_rows($result) == 0) {
							echo '<p class="text-center pt-3">Your Cart is empty</p>';
						} else {
							$ids = array();
							while ($orders = @mysqli_fetch_assoc($result)) {
								array_push($ids, $orders['o_id']);
								echo "
								<div class='row'>
								<div class='col-12 col-md-6 d-flex flex-column flex-md-row w-100 h-100'>
								<img src='./assets/img/products/$orders[p_img_front]' class='img-fluid product-img'>
								<div class='d-flex flex-column justify-content-between justify-content-md-around pt-4 pt-md-0 px-md-2 pl-md-3'>
								<strong class='py-2  mx-auto mx-md-0'>$orders[p_name]</strong>
								<p class='text-muted py-2 m-0 mx-auto mx-md-0'>Color: <span class='text-dark'>$orders[p_color]</span></p>
								<p class='text-muted py-2 m-0 mx-auto mx-md-0'>Model: <span class='text-dark'>$orders[p_model]</span></p>
								</div>

								</div>

								<hr class='col-12 p-0 d-md-none'>
								<div class='col-12 col-md-2 d-flex flex-column  justify-content-center align-items-md-start align-items-center pt-2 pt-md-0'>
								<label class='text-muted m-0 pb-2 d-md-none' for='item$orders[o_id]-quantity'>Quantity: </label>
								<input type='number' name='item$orders[o_id]-quantity' id='item$orders[o_id]-quantity' class='qty' min='1' max='100' value='1' onclick='calcTotal($orders[o_id])'>

								<a href='./db/removeItem.php?id=$orders[o_id]' class='mt-3 text-muted remove-item'> Remove Item </a>
								</div>

								<div class='col-6 col-md-2 d-flex  justify-content-md-start justify-content-center algin-items-center pt-2'>
								<p class='text-muted m-0 d-md-none'>Price: </p>
								<span class='pl-3 text-dark pl-md-0 my-md-auto'>$<span class='product-price' id='item$orders[o_id]-price'>$orders[p_price]</span></span>
								</div>

								<div class='col-6  col-md-2 d-flex  justify-content-md-start justify-content-center algin-items-center pt-2'>
								<p class='text-muted m-0 d-md-none'>Total: </p>
								<span id='item$orders[o_id]-total' class='pl-3 text-dark pl-md-0 my-md-auto order-total'>$$orders[p_price]</span>
								</div>
								<hr class='col-12 p-0'>
								</div>";
							}
						}
						?>





						<?php
						if (isset($ids)) {
							foreach ($ids as $v) {
								echo '<input type="hidden" name="ids[]" value="' . $v . '">';
							}
							echo '<input type="submit" class="d-none" name="submit" value="Check Out">';
						}
						?>
					</form>

				</div>

				<div class="col-4 d-none d-lg-block position-fixed summary-wrapper">
					<div class="summary ml-5 p-4">
						<h5 class="">Order Summary</h5>
						<hr>
						<div class="d-flex justify-content-between">
							<p class="text-muted m-1">Items <span id="items-count-lg" class="text-dark px-3">2</span> </p>
							<p id="total-lg" class='items-total m-1'>$20</p>
						</div>
						<p class="item-shipping text-muted m-1">Shipping Location</p>
						<p class="m-1">
							<?php
							if ($empty) {
								echo 'Not Found';
							} else {
								echo $addressRow['c_address'];
							}
							?>
						</p>
						<hr>
						<p class="m-1 text-muted">Total Cost</p>
						<p id="cost-lg" class="m-1">$0</p>

						<?php
						if (@mysqli_num_rows($result) != 0) {
							echo '<input type="submit" value="checkout" id="submit-lg" onclick="checkOut(event, ' . $empty . ')" class="mx-0 mt-4 w-100">';
						} else {
							echo '<input type="submit" value="checkout" id="submit-lg" onclick="checkOut(event, ' . $empty . ')" disabled class="mx-0 mt-4 w-100">';
						}
						?>


					</div>
				</div>



			</div>

		</div>

		<div class='position-sticky d-flex d-lg-none justify-content-between px-4 align-items-end summary-sm'>
			<div class="d-flex flex-column justify-content-center my-2">
				<p class="text-muted font-weight-light m-0">Items: <span id="items-count-sm" class="m-0 pl-2 text-dark"></span></p>

				<p class="text-muted font-weight-light m-0">Cost: <span id="cost-sm" class="m-0 pl-2 text-dark">$0</span></p>
			</div>

			<?php
			if (@mysqli_num_rows($result) != 0) {
				echo '<input type="submit" id="submit-sm" value="Check Out" class="my-3" onclick="checkOut(event, ' . $empty . ')">';
			} else {
				echo '<input type="submit" id="submit-sm" value="Check Out" class="my-3" onclick="checkOut(event, ' . $empty . ')" disabled>';
			}
			?>
		</div>

		<?php
		include './footer.php';
		?>
	</div>



	<div id="confirm" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Confirm Checkout</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="d-flex justify-content-between">
						<p class="text-muted m-1">Items <span id="items-count-modal" class="text-dark px-3">2</span> </p>
						<p id="total-modal" class='items-total m-1'>$20</p>
					</div>
					<p class="item-shipping text-muted m-1">Shipping Location</p>
					<p class="m-1">
						<?php
						if ($empty) {
							echo 'Not Found';
						} else {
							echo $addressRow['c_address'];
						}
						?>
					</p>
					<p class="m-1 text-muted">Total Cost</p>
					<p id="cost-modal" class="m-1">$0</p>
				</div>
				<div class="modal-footer">

					<input id="confirm-checkout" type="submit" value="Confirm Checkout">
					<input type="submit" class="btn-secondary alert-btn-cancel" value="Cancel" data-dismiss="modal">
				</div>
			</div>
		</div>
	</div>



	<div id="address-alert" class="modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">No Address</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Please provide your address first from <a href="./account.php" class="address-alert-link">here</a> </p>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn-secondary alert-btn-cancel" value="Cancel" data-dismiss="modal">
				</div>
			</div>
		</div>
	</div>


	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src='./js/app.js'></script>
	<script src='./js/forCart.js'></script>
</body>

</html>