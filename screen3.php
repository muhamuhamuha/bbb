<!-- Figure 3: Search Result Screen by Prithviraj Narahari, php coding: Alexander Martens -->
<?php
	session_start();
	require_once __DIR__ . '/src/db.php';



	// the html forwards the following listed parameters
	// $searchon is an array, the rest are variables
	// $category is a string
	// $search is useless
	// $searchfor must be split into an array if there's a comma
	[$searchfor, $search, $searchon, $category] = array_values($_GET);


	$sql = 'SELECT Title, Author, Publisher, ISBN, Price FROM BOOK';
	$where_clauses = [];
	if ($category !== 'all') {
		$where_clauses []= 'Category = "' . $category . '"';
	}

	if (is_array($searchon)) {
		if (in_array('anywhere', $searchon)) {
			$more_clauses = "(Title = '$searchfor' OR AUTHOR = '$searchfor' ";
			$more_clauses .= "OR Publisher = '$searchfor' OR ISBN = '$searchfor')";
			$where_clauses []= $more_clauses;
		} else {
			if ( count($searchon) === 1 ) {
				$where_clauses []= $searchon[0] . " = '$searchfor'";
			}	else {
				// combine columns to search and search comma-separated criteria
				$combined = array_map(null, $searchon, explode(',', $searchfor));
				// serialize tuples
				$combined = array_map(function($arr) { return "$arr[0] = '$arr[1]'"; }, $combined);

				$where_clauses = array_merge($where_clauses, $combined);
			}
		}
	}

	$where_clauses []= 'Inventory > 0';
	$where_clauses = implode(" AND ", $where_clauses);
	$sql .= " WHERE $where_clauses";
	// pull from sql
	$books = db\select_from_db($sql . ';');

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
		// add to cart
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
					<?php
						// get shopping cart data if the user is logged in.
						if ( isset($_SESSION['username']) ) {
							$sql1 = 'SELECT SUM(Quantity) FROM "BOOK-SHOPPING_CART";';
							$sql1_result = db\select_from_db($sql1);
							[$num_items] = array_values($sql1_result[0]);
							echo "<fieldset>Your Shopping Cart Has ";
							echo isset($num_items) ? $num_items : 0;
							echo " Items</fieldset>";

						} else {
							echo "<fieldset>Your Shopping Cart Has 0 Items</fieldset>";
						}
					?>
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
								[$t, $a, $p, $i, $pr] = array_values($book);
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
<script>
	const params = new URLSearchParams(location.search);
	const searchon = params.getAll('searchon[]');
	const searchfor = params.get('searchfor');
	const numItems = (searchfor.match(/,/g) || []).length + 1;  // add one since default searchon is 1
	if ( searchon.find(x => x === 'anywhere') && numItems > 1) {
		alert(`Cannot search for multiple items if specifying "anywhere" in "Search In" field.`);
		window.history.back();

	}
	else if ( numItems > 0
						&& numItems !== searchon.length
						&& window.location.href.indexOf('screen2') > -1 ) {
		alert(`Given ${numItems} items: ${searchfor} but ${searchon.length} "Search In" option(s).`);
		window.history.back();
	}
</script>

<?php
  require_once __DIR__ . '/src/utils.php';

	// so the javascript forwards the add to cart as a $_GET request, not $_POST...
	if (checkRequest(array_key_exists('cartisbn', $_GET), 'GET')) {

		// add cartisbn to cart
		$isbn = $_GET['cartisbn'];

		$sql = "SELECT Price FROM BOOK WHERE ISBN = '$isbn'";
		$sql_result = db\select_from_db($sql);
		$price = $sql_result[0]['Price'];
		
		$ret = db\crud_db("INSERT INTO \"BOOK-SHOPPING_CART\" VALUES(123456, $isbn, 1, $price);");
		// TODO make sure that 
		if ( $ret ) {
			// database sent an error
			raise_alert('That book is already in your cart. Happy shopping! :)');
		} else {
			// use title instead of isbn in alert message
			raise_alert("ISBN: $isbn added successfully to the cart!");
		}
	}

?>
