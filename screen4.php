<!-- screen 4: Book Reviews by Prithviraj Narahari, php coding: Alexander Martens-->
<?php
require_once __DIR__ . '/src/db.php';

[$isbn, $title] = array_values($_GET);
$sql = "SELECT \"Description\" FROM REVIEW NATURAL JOIN BOOK ";
$sql .= "WHERE ISBN = '$isbn';";
$sql_result = db\select_from_db($sql);


?>
<!DOCTYPE html>
<html>
<head>
<title>Book Reviews - 3-B.com</title>
<style>
.field_set
{
	border-style: inset;
	border-width:4px;
}
</style>
</head>
<body>
  <table align="center" style="border:1px solid blue;">
    <tr>
	  <td align="center">
		<h5> Reviews For: <?php echo $title ?></h5>
	  </td>
			<td align="left">
				<h5> </h5>
			</td>
		</tr>
			
		<tr>
			<td colspan="2">
			<div id="bookdetails" style="overflow:scroll;height:200px;width:300px;border:1px solid black;">
			<table>
				<!-- why so many tables? -->
				<?php
				foreach ($sql_result as $review) {
					echo '<tr><td class="field_set">' . $review['Description'] . '</td></tr>';
				}
				?>
			</table>
			</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<form action="screen2.php" method="post">
					<input type="submit" value="Done">
				</form>
			</td>
		</tr>
	</table>

</body>

</html>