<?php

session_start();

require 'PxPay_Curl.inc.php';
require '../secret.php';

//Create instance
$pxpay = new PxPay_Curl('https://sec.paymentexpress.com/pxpay/pxaccess.aspx', PXPAY_USER, PXPAY_KEY);

//Convert the response into something we can use
$response = $pxpay->getResponse($_GET['result']);

//Was the transaction successful?
if ($response->getSuccess() == 1) {
	
	//Update the DB order to say it has been paid
	$dbc = new mysqli('localhost', 'root', '', 'shopping_cart');

	//Extract the order ID from the session
	$orderID = $_SESSION['orderID'];

	//Run the query
	$dbc->query("UPDATE orders SET state = 'approved' WHERE id = $orderID");

	//Email the client

	//Email the sales manager

	//Clear the cart
	$_SESSION['cart'] = [];
}

