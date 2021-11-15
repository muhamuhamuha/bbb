<?php
	require_once __DIR__ . '/src/db.php';

	$sql = 'SELECT Title, Author, Publisher, ISBN, Quantity, Price ';
	$sql .= 'FROM "BOOK-SHOPPING_CART" NATURAL JOIN BOOK;';
	$books = db\select_from_db($sql);
	

	/** used at the end to calculate subtotals */
	function calcSubtotal(array $books): float {
		// filter out prices
		$prices = array_map(function($x) { return floatval(end($x)); }, $books);
		$quantites = array_map(function($x) { return intval($x['Quantity']); }, $books);
		for ($i = 0; $i < count($prices); $i++) {
			$prices[$i] = $quantites[$i] * $prices[$i];
		}
		return array_sum($prices);
	}

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
	const recalcBtn = document.querySelector('input[id="recalculate_payment"]');
	const xpath = "//td[contains(text(), 'Subtotal: ')]";
	const subtotalText =
		document
			.evaluate(xpath, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null)
			.singleNodeValue;
		
	/** Apparently javascript will not round numbers properly. */
	function roundToTwo(num) {    
    return +(Math.round(num + "e+2") + "e-2");
	}

	recalcBtn.addEventListener('click', (e) => {
		// e.preventDefault();

		quantities =
			[...document.querySelectorAll('input[id^="txt"]')]
			.map(x => parseInt(x.value));

		prices =
			[...document.querySelectorAll('td + td + td + td')]
			.map(x => roundToTwo(parseFloat(x.textContent)));

		summed = 
			quantities
			.map( (q, index) => q * prices[index] )
			.reduce((x, a) => x + a, 0)
			.toString()
			.match(/\d+\.\d?\d?/)[0];

		// replace subtotal value
		subtotalText.textContent = subtotalText.textContent.replace(/\d+\.?\d*/, summed);
	});

</script>
<?php
	require_once __DIR__ . '/src/utils.php';

	if ( checkRequest(array_key_exists('delIsbn', $_GET), 'GET') ) {
		$isbn = $_GET['delIsbn'];

		// hit database twice, once to save quantity, and then again to delete...
		$sql = 'SELECT Quantity FROM "BOOK-SHOPPING_CART" WHERE ISBN=' . $isbn . ';';
		$quantity = array_map(function ($x) { return $x['Quantity']; }, db\select_from_db($sql))[0];
		echo "<br>quan: $quantity";

		// db\crud_db(str_replace('SELECT Quantity', 'DELETE', $sql)));
		echo "<br>";
	} elseif ( checkRequest(array_key_exists('recalculate_payment', $_POST)) ){

		foreach ($_POST as $k => $v) {
			// keep only isbns
			if (str_starts_with($k, 'txt')) {
				$sql = 'UPDATE "BOOK-SHOPPING_CART" SET Quantity=' . $v . ' WHERE ';
				$sql .= 'ISBN=' . substr($k, 3) . ';';  // cut out 'txt' prefix

				// update database
				db\crud_db($sql);
			}
		}
	}

	echo "<br>GET<br>";
	print_r($_GET);
	echo "<br>POST<br>";
	print_r($_POST);


?>