<?php

session_start();

// echo '<pre>';
// print_r($_POST);

//Calculate total order price
$grandTotal = 0;

	foreach ($_SESSION['cart'] as $product) {
		$grandTotal += $product['quantity'] * $product['price'];
	}

//Prepare the order in a "pending" state

//Include PxPay library
require 'PxPay_Curl.inc.php';




