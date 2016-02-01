<?php

	//Start session
	session_start();

	//Connect to the DB
	$dbc = new mysqli('localhost', 'root', '', 'shopping_cart');

	//Create a cart if one doesn't exist
	if ( !isset($_SESSION['cart']) ) {
		
		//Create the cart
		$_SESSION['cart'] = [];

	}

	//If clearcart is in the GET array
	if ( isset($_GET['clearcart']) ) {

		//Clear the cart
		$_SESSION['cart'] = [];

		//Refresh the page
		header('Location: index.php');
	}

	//Did the user submit a form?
	if ( isset($_POST['add-to-cart']) ) {

		//Find the price of the product
		$id = $dbc->real_escape_string($_POST['product-id']);

		//Prepare SQL to get the price of the product
		$sql = "SELECT price FROM products WHERE id = $id";

		//Run the query
		$result = $dbc->query($sql);

		//Validation goes here

		//Extract the data
		$result = $result->fetch_assoc();

		$productFound = false;

		//Loop over the cart and see if the product is added already
		for ($i=0; $i < count($_SESSION['cart']); $i++) { 
			
			//Get the ID of the product in the cart
			$cartItemID = $_SESSION['cart'][$i]['id'];

			//Get the ID of the product being added to the cart
			$addItemID = $_POST['product-id'];

			//If the two ID's match
			if ($cartItemID == $addItemID) {
				$_SESSION['cart'][$i]['quantity'] += $_POST['quantity'];
				$productFound = true;
			}
		}

		//If the product was not found in the cart
		if ( ! $productFound) {
			
			//Add the item to the cart
		$_SESSION['cart'][] = [
			'id'=> $_POST['product-id'],
			'name'=> $_POST['name'],
			'description'=> $_POST['description'],
			'price'=> $result['price'],
			'quantity'=> $_POST['quantity']
			];
		}
		
	}

	//Include header
	include 'templates/header.template.php';

?>

	<h1>Products</h1>

	<?php

		// Get all the products from the DB
		$sql = "SELECT id, name, description, price, stock FROM products";

		//Run the Query
		$result = $dbc->query($sql);

		//Loop over the result
		while($row = $result->fetch_assoc()) {

			//Include the product template
			include 'templates/product.template.php';

		}



	//Include footer
	include 'templates/footer.template.php';

	?>
	
