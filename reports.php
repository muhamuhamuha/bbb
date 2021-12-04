<?php
require_once __DIR__ . '/src/db.php';

$query1 = db\select_from_db("SELECT count(Username) AS Num FROM CUSTOMER;");
$numCustomers = $query1[0]['Num'];

$query2 = db\select_from_db("SELECT Category, count(ISBN) AS Numba FROM BOOK GROUP BY Category ORDER BY Numba DESC;");

$query3 = db\select_from_db("SELECT strftime('%Y', \"DateTime\") as year, strftime('%m', \"DateTime\") AS month, avg(TotalPrice) AS aver FROM SHOPPING_CART GROUP BY year, month ORDER BY year DESC, month ASC limit 1;
");

$query4 = db\select_from_db("SELECT Title, count(ReviewID) AS NumReviews FROM BOOK NATURAL JOIN REVIEW GROUP BY Title;");

?>

<!DOCTYPE HTML>
<head>
	<title>ADMIN TASKS</title>
</head>
<body>
	<table align="center" style="border:2px solid blue;">
		<tr>
			<form action="index.php" method="post" id="exit">
			<td align="center">
				<input type="submit" name="cancel" id="cancel" value="EXIT 3-B.com[Admin]" style="width:200px;">
			</td>
			</form>
		</tr>
	</table>
    <tr>
		<td>&nbsp</td>
	</tr>
    <table align="center" style="border:2px solid blue;">
		<tr>
			<td>
                <div>
                    <P">
                    <?php
                    echo "Total number of registered customers: $numCustomers<br>\n";
                    echo "Total number of book titles available in each category:\n";
                    echo "<pre>";
                    echo "Catagory\t\tQuantity\n";
                    foreach ( $query2 as $row ) {
                        echo "\n", $row['Category'], "\t\t", $row['Numba'];
                    }
                    echo "</pre>";
                    echo " Average monthly sales, in dollars, for the current year: ";
                    foreach ( $query3 as $row2 ) {
                        preg_match('/\d+\.\d\d/',$row2['aver'],$m);
                        echo "<br>", $row2['year'], "\t\t", $row2['month'], "\t\t", $m[0];
                    }
                    echo "<br>";
                    echo "All book titles and the number of reviews for each book:\n";
                    echo "<pre>";
                    echo "Title\t\tReviews\n";
                    foreach ( $query4 as $row1 ) {
                        echo "\n", $row1['Title'], "\t\t", $row1['NumReviews'];
                    }
                    echo "</pre>";
                    ?>
                    </p>
                </div>
			</td>
		</tr>
	</table>
</body>


</html>