<?php

	// dummy data
	$books = [
		['Harry Potter', 'J.K. Rowling', 'Puffin', '12345', 11.99],
		['Gary Potter', 'K.J. Rowling', 'Falcon', '54321', 99.11],
		['Larry Potter', 'R. Jkowling', 'Flamingo', '10101', 500],
		['Mary Potter', 'R. Kjowling', 'Sparrow', '10101', 500],
	];


	/** used at the end to calculate subtotals */
	function calcSubtotal(array $books): float {
		// filter out prices
		$prices = array_map(function($x) { return end($x); }, $books);
		return array_sum($prices);
	}

	/** spits out data structured into html table syntax that this UI expects... */
	function outputHTML(string $title,
											string $author,
											string $publisher,
											string $isbn,
											float $price): void {
  	echo "<tr>";
		echo "<td>";
		echo "<button name='delete' id='delete' onClick='del(\"$isbn\")'>";
		echo "Delete Item";
		echo "</button>";
		echo "</td>";
		echo "<td>";
		echo "$title</br>";
		echo "<b>By:</b> $author</br>";
		echo "<b>Publisher:</b> $publisher";
		echo "</td>";
		echo "<td>";
		echo "<input id='txt$isbn' name='txt$isbn' value='1' size='1' />";
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
									[$t, $a, $p, $i, $pr] = $book;
									outputHTML($t, $a, $p, $i, $pr);
								}
							?>
							<!-- <tr>
								<td>
									<button name='delete' id='delete' onClick='del("123441");return false;'>
										Delete Item
									</button>
								</td>
								<td>
									iuhdf</br>
									<b>By</b> Avi Silberschatz</br>
									<b>Publisher:</b> McGraw-Hill
								</td>
								<td>
									<input id='txt123441' name='txt123441' value='1' size='1' />
								</td>
								<td>12.99</td>
							</tr> -->
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

<?php
	require_once __DIR__ . '/src/utils.php';

	/**
	 * if the user deletes, POST is a nested dictionary
	 * where the isbn to be deleted will have the key 'delete',
	 * and it will in turn be the key to the quantity to delete
	 * Array([delete] => [txt000000] => 2)
	 */
	function processDeletes(array $deletes): bool {
		$item2del = $deletes['delete'];
		// hit the database and remove
		
	}

	if (checkRequest(array_key_exists('delete', $_POST))) {
		print_r($_POST);
		
	} elseif (checkRequest(array_key_exists('recalculate_payment', $_POST))) {
		echo('hello<br>');
		print_r($_POST);
		// hit database and recalculate sum

	}

?>