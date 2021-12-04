<?php
session_start();

require_once __DIR__ . '/src/utils.php';
require_once __DIR__ . '/src/db.php';

$uname = $_SESSION['username'];  // comes wrapped in single quotes for queries

$sql = 'SELECT FirstName, LastName, Address, City, State, Zip, CardType, CardNumber, CardExpDate ';
$sql .= "FROM CUSTOMER WHERE Username = $uname";

$sql_result = db\select_from_db($sql)[0];
// unpack into variables
[$fname, $lname, $address, $city, $state, $zip, $ctype, $cnum, $cexp] = array_values($sql_result);
// reformat expiration date
$cexp = $cexp[0] . $cexp[1] . '/' . $cexp[2] . $cexp[3];

// get books in cart
$sql = 'SELECT ISBN, Title, Author, Price, Quantity ';
$sql .= 'FROM "BOOK-SHOPPING_CART" NATURAL JOIN BOOK;';
$books = db\select_from_db($sql);

//get totals
$numBooksInCart = 0;
$cartSbTotal = 0;
foreach ($books as $dex => $book) {
	[$isbn, $title, $author, $price, $quantity] = array_values($book);
	$numBooksInCart+= $quantity;
	$cartSbTotal+= ($price * $quantity);
	$updateQuantity = db\crud_db("UPDATE BOOK SET Inventory = Inventory - $quantity WHERE ISBN = $isbn;");
}
$shippingHandling = $numBooksInCart * 2;
$total = $cartSbTotal + $shippingHandling;

//add total to database for sales report
$insertTotal = db\crud_db("UPDATE SHOPPING_CART SET TotalPrice = $total WHERE rowid = (SELECT max(rowid) FROM SHOPPING_CART);");

//get date
$dateQuery = db\select_from_db("SELECT DATE() AS dait;");
$date = $dateQuery[0]['dait'];

//get time
$timeQuery = db\select_from_db("SELECT TIME() AS tyme;");
$time = $timeQuery[0]['tyme'];

if (isset($_POST['update_customerprofile'])){
	$deleteBookCart = db\crud_db("DELETE FROM \"BOOK-SHOPPING_CART\" WHERE CartID = 123456;");
	header('Refresh: 0; url=screen2.php');
}

?>

<!DOCTYPE HTML>
<head>
	<title>Proof purchase</title>
	<header align="center">Proof purchase</header> 
</head>
<body>
	<table align="center" style="border:2px solid blue;">
	<form id="buy" action="" method="post">
	<tr>
	<td>
	Shipping Address:
	</td>
	</tr>
	<td colspan="2"><?php echo str_replace("'", "", $fname);
	echo " ";
	echo str_replace("'", "", $lname);
	?></td>
	<td rowspan="3" colspan="2">
		<b>UserID:</b><?php echo " "; echo str_replace("'", "", $uname);?><br />
		<b>Date:</b><?php echo " "; echo str_replace("'", "", $date);?><br />
		<b>Time:</b><?php echo " "; echo str_replace("'", "", $time);?><br />
		<b>Card Info:</b><br/><?php echo "$ctype - $cnum - $cexp<br>";?></td>
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
		SubTotal:<?php echo " $$cartSbTotal"?></br>Shipping_Handling:<?php echo " $$shippingHandling"?></br>_______</br>Total:<?php echo " $$total"?></div>
	</td>
	</tr>
	<tr>
		<td align="right">
			<input type="submit" id="buyit" name="btnbuyit" value="Print" disabled>
		</td>
		</form>
		<td align="right">
			<form id="update" action="" method="post">
			<input type="submit" id="update_customerprofile" name="update_customerprofile" value="New Search">
			</form>
		</td>
		<td align="left">
			<form id="cancel" action="index.php" method="post">
			<input type="submit" id="exit" name="exit" value="EXIT 3-B.com">
			</form>
		</td>
	</tr>
	</table>
</body>
</HTML>
