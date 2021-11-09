<!-- screen 4: Book Reviews by Prithviraj Narahari, php coding: Alexander Martens-->
<?php
require_once __DIR__ . '/src/db.php';

// it would have been smart to add isbn in dummy database but I didn't so we filter by title only...
list($isbn, $title) = array_values($_GET);

// WHERE clause didn't work for some stupid reason GOD I HATE PHP.
$sql_result = db\select_from_db("SELECT title, review FROM dummy_review;");

// since WHERE clause didn't work, we filter...
$only_title_reviews = array_filter($sql_result, function($x) use ($title) {
	return $x['title'] === $title;
});

// since php filter is stupid, we filter again...
$only_title_reviews = array_map(function($x) { return $x['review']; }, array_values($only_title_reviews));

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
				foreach ($only_title_reviews as $review) {
					echo '<tr class="field_set"><td>' . $review . '</td></tr>';
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