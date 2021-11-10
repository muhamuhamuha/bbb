<!-- Figure 3: Search Result Screen by Prithviraj Narahari, php coding: Alexander Martens -->
<?php
require_once __DIR__ . '/src/db.php';

// the html forwards the following listed parameters
// $searchon is an array, the rest are variables
// $category will be returned as a number..‍️.🤦‍♂️ 
// $search is useless
// $searchfor must be split into an array if there's a comma
list($searchfor, $search, $searchon, $category) = array_values($_GET);

print_r($_GET);
echo "<br>searchfor: $searchfor<br>search: $search<br>";

print_r($searchon);
echo "<br>category:$category";


$sql = 'SELECT isbn, title FROM book';


$books = [
	['Harry Pothead', 'J.K. Rowling', 'Puffin', '12345', 11.99],
	['Gary Pothead', 'J.K. Rowling', 'Falcon', '54321', 99.11],
	['Mowgli and Dancing Teapot', 'R. Kipling', 'Flamingo', '10101', 500],
];

/** spits out data structured into html table syntax that this UI expects... */
function outputHTML(string $title,
									 	string $author,
										string $publisher,
										string $isbn,
										float $price): void {

	// creates 3 rows
	echo "<tr>";
	echo "<td align='left'>";
	echo "<button name='btnCart' id='btnCart' onClick='cart(\"$isbn\", \"\", \"Array\", \"all\")'>";
	echo "Add to Cart";
	echo "</button>";
	echo "</td>";
	echo "<td rowspan='2' align='left'>";
	echo "$title</br>";
	echo "<b>By:</b> $author</br>";
	echo "<b>Publisher:</b> $publisher,</br>";
	echo "<b>ISBN:</b> $isbn</t> <b>Price:</b> $price";
	echo "</td>";
	echo "</tr>";  // end row 1
	echo "<tr>";
	echo "<td align='left'>";
	echo "<button name='review' id='review' onClick='review(\"$isbn\", \"$title\")'>";
	echo "Reviews";
	echo "</button>";
	echo "</td>";
	echo "</tr>";  // end row 2
	echo "<tr>";
	echo "<td colspan='2'>";
	echo "<p>_______________________________________________</p>";
	echo "</td>";
	echo "</tr>";  // end row 3
}

?>

<html>

<head>
	<title> Search Result - 3-B.com </title>
	<script>
		//redirect to reviews page
		function review(isbn, title) {
			window.location.href = "screen4.php?isbn=" + isbn + "&title=" + title;
		}
		//add to cart
		function cart(isbn, searchfor, searchon, category) {
			window.location.href = "screen3.php?cartisbn=" + isbn + "&searchfor=" + searchfor + "&searchon=" + searchon + "&category=" + category;
		}
	</script>
</head>

<body>
	<table align="center" style="border:1px solid blue;">
		<tr>
			<td align="left">

				<h6>
					<fieldset>Your Shopping Cart has 0 items</fieldset>
				</h6>

			</td>
			<td>
				&nbsp
			</td>
			<td align="right">
				<form action="shopping_cart.php" method="post">
					<input type="submit" value="Manage Shopping Cart">
				</form>
			</td>
		</tr>
		<tr>
			<td style="width: 350px" colspan="3" align="center">
				<div id="bookdetails" style="overflow:scroll;height:180px;width:400px;border:1px solid black;background-color:LightBlue">
					<table>
						<?php
							foreach($books as $book) {
								// title, author, publisher, isbn, price
								[$t, $a, $p, $i, $pr] = $book;
								outputHTML($t, $a, $p, $i, $pr);
							}
						?>
					</table>
				</div>

			</td>
		</tr>
		<tr>
			<td align="center">
				<form action="confirm_order.php" method="get">
					<input type="submit" value="Proceed To Checkout" id="checkout" name="checkout">
				</form>
			</td>
			<td align="center">
				<form action="screen2.php" method="post">
					<input type="submit" value="New Search">
				</form>
			</td>
			<td align="center">
				<form action="index.php" method="post">
					<input type="submit" name="exit" value="EXIT 3-B.com">
				</form>
			</td>
		</tr>
	</table>
</body>
</html>