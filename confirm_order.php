<?php
	session_start();

	require_once __DIR__ . '/src/utils.php';
	require_once __DIR__ . '/src/db.php';
	require_once __DIR__ . '/src/app.php';

	// redirect user to register
	if (!isset($_SESSION['username'])) {
		header('Refresh:0; url=customer_registration.php');
	}
	$uname = $_SESSION['username'];  // comes wrapped in single quotes for queries

	$sql = 'SELECT Address, City, State, Zip, CardType, CardNumber, CardExpDate, ';
	$sql .= 'FirstName, LastName ';
	$sql .= "FROM CUSTOMER WHERE Username = $uname";

	$sql_result = db\select_from_db($sql)[0];
	// unpack into variables
	[$address, $city, $state, $zip, $ctype, $cnum, $cexp, $fname, $lname] = array_values($sql_result);
	// reformat expiration date
	$cexp = $cexp[0] . $cexp[1] . '/' . $cexp[2] . $cexp[3];

	// get books in cart
	$sql = 'SELECT Title, Author, Price, Quantity ';
	$sql .= 'FROM "BOOK-SHOPPING_CART" NATURAL JOIN BOOK;';
	$books = db\select_from_db($sql);

?>
<!DOCTYPE HTML>
<head>
	<title>CONFIRM ORDER</title>
	<header align="center">Confirm Order</header> 
</head>
<body>
	<table align="center" style="border:2px solid blue;">
	<form id="buy" action="proof_purchase.php" method="post">
	<tr>
		<td>Shipping Address:</td>
	</tr>
	<td colspan="2"><?php echo "$fname $lname"; ?></td>
	<td rowspan="3" colspan="2">
		<input type="radio" name="cardgroup" value="profile_card" checked>
		<?php echo "Use Credit Card on File:<br>$ctype - $cnum - $cexp<br>"; ?>

		<input type="radio" name="cardgroup" value="new_card">New Credit Card<br />
				<select id="credit_card" name="credit_card">
					<option selected disabled>select a card type</option>
					<option>VISA</option>
					<option>MASTER</option>
					<option>DISCOVER</option>
				</select>
				<input type="text" id="card_number" name="card_number" placeholder="Credit card number">
				<br />Exp date<input type="text" id="card_expiration" name="card_expiration" placeholder="mm/yyyy">
	</td>
	<tr>
		<td colspan="2"><?php echo $address; ?></td>		
	</tr>
	<tr>
		<td colspan="2"><?php echo $city ?></td>
	</tr>
	<tr>
		<td colspan="2"><?php echo $state . ', ' . $zip ?></td>
	</tr>
	<tr>
	<td colspan="3" align="center">
	<div id="bookdetails" style="overflow:scroll;height:180px;width:520px;border:1px solid black;">
	<table border='1'>
		<th>Book Description</th><th>Qty</th><th>Price</th>
		<?php
			foreach ($books as $dex => $book) {
				[$title, $author, $price, $quantity] = array_values($book);
				echo "<tr>";
				echo "<td>$title<br><b>By:</b> $author</td>";
				echo "<td>$quantity</td>";
				echo "<td>$price</td>";
				echo "</tr>";
			}
		?>
		</tr>
	</table>
	</div>
	</td>
	</tr>
	<tr>
	<td align="left" colspan="2">
	<div id="bookdetails" style="overflow:scroll;height:180px;width:260px;border:1px solid black;background-color:LightBlue">
	<b>Shipping Note:</b> The book will be </br>delivered within 5</br>business days.
	</div>
	</td>
	<td align="right">
	<div id="bookdetails" style="overflow:scroll;height:180px;width:260px;border:1px solid black;">
		<?php
			$subtot = calcSubtotal($books);
			$ship = calcShipping($books);
			$tot = $subtot + $ship;
			echo "Subtotal: $ $subtot";
			echo "<br>Shipping & Handling: $ $ship";
			echo "<br>Total: $ $tot";
		?>
	</td>
	</tr>
	<tr>
		<td align="right">
			<input type="submit" id="buyit" name="btnbuyit" value="BUY IT!">
		</td>
		</form>
		<td align="right">
			<form id="update" action="update_customerprofile.php" method="post">
			<input type="submit" id="update_customerprofile" name="update_customerprofile" value="Update Customer Profile">
			</form>
		</td>
		<td align="left">
			<form id="cancel" action="index.php" method="post">
			<input type="submit" id="cancel" name="cancel" value="Cancel">
			</form>
		</td>
	</tr>
	</table>
</body>
</HTML>