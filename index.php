<!-- Figure 1: Welcome Screen by Alexander -->
<?php
session_start();
require_once __DIR__ . '/src/db.php';

//deleting cart on start menu
$deleteCart = db\crud_db("DELETE FROM SHOPPING_CART WHERE CartID = 1;");
if ( $deleteCart ) {
	// database sent an error
	raise_alert('This cart cannot be deleted!');
}

// before deleting we have to update the inventory in the book table
$inv_isbn = db\select_from_db('SELECT ISBN, Quantity FROM "BOOK-SHOPPING_CART";');
foreach ($inv_isbn as $result_arr) {
	[$isbn, $quan] = array_values($result_arr);
	db\crud_db("UPDATE BOOK SET Inventory = Inventory + $quan WHERE ISBN = $isbn;");
}

// deleting cart-book on start menu
$deleteBookCart = db\crud_db("DELETE FROM \"BOOK-SHOPPING_CART\" WHERE CartID = 123456;");

?>

<title>Welcome to Best Book Buy Online Bookstore!</title>
<body>
	<table align="center" style="border:1px solid blue;">
	<tr><td><h2>Best Book Buy (3-B.com)</h2></td></tr>
	<tr><td><h4>Online Bookstore</h4></td></tr>
	<tr><td><form action="" method="post">
		<input type="radio" name="group1" value="SearchCat.php" onclick="document.location.href='screen2.php'">Search Online<br/>
		<input type="radio" name="group1" value="customer_registration.php" onclick="document.location.href='customer_registration.php'">New Customer<br/>
		<input type="radio" name="group1" value="user_login.php" onclick="document.location.href='user_login.php'">Returning Customer<br/>
		<input type="radio" name="group1" value="admin_login.php" onclick="document.location.href='admin_login.php'">Administrator<br/>
		<input type="submit" name="submit" value="ENTER">
	</form></td></tr>
	</table>

</body>
</html>