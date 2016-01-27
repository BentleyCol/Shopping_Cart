<?php

	//Include header
	include 'templates/header.template.php';

?>

	<h1>Products</h1>

	<?php

		//Connect to the DB
		$dbc = new mysqli('localhost', 'root', '', 'shopping_cart');

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
	
