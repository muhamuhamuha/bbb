
<!-- Figure 2: Search Screen by Alexander -->
<?php
	require_once __DIR__ . '/src/db.php';

	//create a shopping cart
	$insertCart = db\crud_db("INSERT INTO SHOPPING_CART VALUES (000001,0,0,CURRENT_TIMESTAMP);");

	// read unique categories from sql
	$sql = 'SELECT DISTINCT Category FROM BOOK;';
	$sql_result = db\select_from_db($sql);
	$cats = array_map(function($arr) { return $arr['Category']; }, $sql_result);

	function outputHTML($categories) {
		foreach ($categories as $cat) {
			echo "<option value='$cat'>$cat</option>";
		}
		echo "<option value='all' selected='selected'>All Categories</option>";
	}

?>
<html>
<head>
	<title>SEARCH - 3-B.com</title>
</head>
<body>
	<table align="center" style="border:1px solid blue;">
		<tr>
			<td>Search for: </td>
			<form action="screen3.php" method="get">
				<td><input name="searchfor" /></td>
				<td><input type="submit" name="search" value="Search" /></td>
		</tr>
		<tr>
			<td>Search In: </td>
				<td>
					<select name="searchon[]" multiple>
						<option value="anywhere" selected='selected'>Keyword anywhere</option>
						<option value="title">Title</option>
						<option value="author">Author</option>
						<option value="publisher">Publisher</option>
						<option value="isbn">ISBN</option>				
					</select>
				</td>
				<td><a href="shopping_cart.php"><input type="button" name="manage" value="Manage Shopping Cart" /></a></td>
		</tr>
		<tr>
			<td>Category: </td>
				<td>
					<select name="category">
						<?php outputHTML($cats); ?>
					</select>
				</td>
			</form>
	<form action="index.php" method="post">	
				<td><input type="submit" name="exit" value="EXIT 3-B.com" /></td>
			</form>
		</tr>
	</table>
</body>
</html>
<script>
	// disables search button if nothing is added to input field
	const submitField = document.querySelector('input[name="searchfor"]');
	const submitBtn = document.querySelector('input[name="search"]');
	
	submitBtn.disabled = true;

	/** returns true if submitField has text */
	function noInput() { return submitField.value.length === 0; }
	submitField.addEventListener('input', () => submitBtn.disabled = noInput());

</script>