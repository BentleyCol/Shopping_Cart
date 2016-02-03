<?php

session_start();

//Include the secret PxPay file
require '../secret.php';

// echo '<pre>';
// print_r($_POST);

//Calculate total order price
$grandTotal = 0;

	foreach ($_SESSION['cart'] as $product) {
		$grandTotal += $product['quantity'] * $product['price'];
	}

//Prepare the order in a "pending" state
//Connect to the DB
$dbc = new mysqli('localhost', 'root', '', 'shopping_cart');

//Prepare SQL
$name = $dbc->real_escape_string($_POST['full-name']);
$email = $dbc->real_escape_string($_POST['email']);
$suburb = $dbc->real_escape_string($_POST['suburb']);
$phone = $dbc->real_escape_string($_POST['phone']);
$address = $dbc->real_escape_string($_POST['address']);

$sql = "INSERT INTO orders VALUES(NULL, '$name', $suburb, '$address', '$phone', '$email', 'pending')";

//Run the query
$dbc->query($sql);

//Get the ID of this order
$orderID = $dbc->insert_id;

//Save the order ID in the session
$_SESSION['orderID'] = $orderID;

//Loop over the cart contents and add them to the ordered products table
foreach ($_SESSION['cart'] as $product) {

	$productID = $product['id'];
	$quantity = $product['quantity'];
	$price = $product['price'];

	$sql = "INSERT INTO ordered_products VALUES (NULL, $productID, $orderID, $quantity, $price)";

	$dbc->query($sql);
}

//Include PxPay library
require 'PxPay_Curl.inc.php';

//Create an instance of the PxPay class
$pxpay = new PxPay_Curl('https://sec.paymentexpress.com/pxpay/pxaccess.aspx', PXPAY_USER, PXPAY_KEY);

//Create instance of request object
$request = new PxPayRequest();

//Get the text values of the City and Suburb for the transaction

//Populate the request with the transaction details
$request->setAmountInput($grandTotal);
$request->setTxnType('Purchase');
$request->setCurrencyInput('NZD');
$request->setUrlSuccess('http://localhost/~benjamin.cole/Shopping_Cart/transaction-success.php');
$request->setUrlFail('http://localhost/~benjamin.cole/Shopping_Cart/transaction-fail.php');
$request->setTxnData1($_POST['full-name']);
$request->setTxnData2($_POST['phone']);
$request->setTxnData3($_POST['email']);

//Convert the $request obj into XML
$requestString = $pxpay->makeRequest($request);

//Send the request and wait for response
$response = new MifMessage($requestString);

//Extract the URL from the response and redirect the user
$url = $response->get_element_text('URI');

//Redirect user
header('Location: '.$url);








































