<?php
	session_start();
	require_once __DIR__ . '/src/db.php';
	require_once __DIR__ . '/src/app.php';

	$sql = 'SELECT Title, Author, Publisher, ISBN, Quantity, Price ';
	$sql .= 'FROM "BOOK-SHOPPING_CART" NATURAL JOIN BOOK;';
	$books = db\select_from_db($sql);
	
	/** spits out data structured into html table syntax that this UI expects... */
	function outputHTML(string $title,
											string $author,
											string $publisher,
											string $isbn,
											string $quantity,
											float $price): void {
  	echo "<tr>";
		echo "<td>";
		echo "<button name='delete' id='delete' onClick='del(\"$isbn\"); return false;'>";
		echo "Delete Item";
		echo "</button>";
		echo "</td>";
		echo "<td>";
		echo "$title</br>";
		echo "<b>By:</b> $author</br>";
		echo "<b>Publisher:</b> $publisher";
		echo "</td>";
		echo "<td>";
		echo "<input id='txt$isbn' name='txt$isbn' value='$quantity' size='1' />";
		echo "</td>";
		echo "<td>$price</td>";
  	echo "</tr>";
	}
?>
<!DOCTYPE HTML>

<head>
	<title>Shopping Cart</title>
	<script>
		//remove from cart
		function del(isbn) {
			window.location.href = "shopping_cart.php?delIsbn=" + isbn;
		}
	</script>
</head>

<body>
	<table align="center" style="border:2px solid blue;">
		<tr>
			<td align="center">
				<form id="checkout" action="confirm_order.php" method="get">
					<input type="submit" name="checkout_submit" id="checkout_submit" value="Proceed to Checkout">
				</form>
			</td>
			<td align="center">
				<form id="new_search" action="screen2.php" method="post">
					<input type="submit" name="search" id="search" value="New Search">
				</form>
			</td>
			<td align="center">
				<form id="exit" action="index.php" method="post">
					<input type="submit" name="exit" id="exit" value="EXIT 3-B.com">
				</form>
			</td>
		</tr>
		<tr>
			<form id="recalculate" name="recalculate" action="" method="post">
				<td colspan="3">
					<div id="bookdetails" style="overflow:scroll;height:180px;width:400px;border:1px solid black;">
						<table align="center" BORDER="2" CELLPADDING="2" CELLSPACING="2" WIDTH="100%">
							<th width='10%'>Remove</th>
							<th width='60%'>Book Description</th>
							<th width='10%'>Qty</th>
							<th width='10%'>Price</th>
							<?php
								foreach($books as $book) {
									// title, author, publisher, isbn, price
									[$t, $a, $p, $i, $q, $pr] = array_values($book);
									outputHTML($t, $a, $p, $i, $q, $pr);
								}
							?>
						</table>
					</div>
				</td>
		</tr>
		<tr>
			<td align="center">
				<input type="submit" name="recalculate_payment" id="recalculate_payment" value="Recalculate Payment">
				</form>
			</td>
			<td align="center">
				&nbsp;
			</td>
			<td align="center">
				Subtotal: <?php echo calcSubtotal($books); ?>
			</td>
		</tr>
	</table>
</body>
<script>
	// prevent page from reloading if user hits enter in one of the quantity fields
	quantities = document.querySelectorAll('input[id^="txt"]');

	quantities.forEach(x => x.addEventListener('keypress', e => {
		if (e.key === 'Enter')
			e.preventDefault();
	}));

</script>
<?php
	require_once __DIR__ . '/src/utils.php';

	/** checks if given isbn is in inventory */
	function in_inventory(string $isbn, int $amount = 0): bool {
		$sql = 'SELECT Inventory FROM BOOK WHERE ISBN=' . $isbn . ';';
		$inv = array_map(function($x) { return $x['Inventory']; }, db\select_from_db($sql))[0];
		return intval($inv) > $amount;
	}

	if ( checkRequest(array_key_exists('delIsbn', $_GET), 'GET') ) {
		$isbn = $_GET['delIsbn'];

		// hit database twice, once to get quantity, and then again to delete...
		$sql = 'SELECT Quantity FROM "BOOK-SHOPPING_CART" WHERE ISBN=' . $isbn . ';';
		$sql_result = db\select_from_db($sql);
		$quantity = array_map(function ($x) { return $x['Quantity']; }, $sql_result)[0];

		// delete item from shopping cart
		db\crud_db(str_replace('SELECT Quantity', 'DELETE', $sql));

		// hit database again and update BOOK
		$sql = 'UPDATE BOOK SET Inventory = Inventory + ' . $quantity;
		$sql .= " WHERE ISBN = $isbn;";
		db\crud_db($sql);
	} elseif ( checkRequest(array_key_exists('recalculate_payment', $_POST)) ){

		foreach ($_POST as $txtIsbn => $quantity) {
			// keep only isbns
			if (str_starts_with($txtIsbn, 'txt')) {
				$isbn = substr($txtIsbn, 3);

				if ( in_inventory($isbn, intval($quantity)) ) {
					$sql = 'UPDATE "BOOK-SHOPPING_CART" SET Quantity=' . $quantity . ' WHERE ';
					$sql .= 'ISBN=' . $isbn . ';';

					// update database and refresh page to actually calculate subtotal
					db\crud_db($sql);
					header('Refresh:0; url=shopping_cart.php');
				} else {
					raise_alert('Sorry, we don\'t have that many in stock!');
				}
			}
		}
	}

?>