<head>
<title>UPDATE CUSTOMER PROFILE</title>

</head>
<body>
	<form id="update_profile" action="" method="post">
	<table align="center" style="border:2px solid blue;">
		<tr>
			<td align="right">
				Username: 
			</td>
			<td colspan="3" align="center">
							</td>
		</tr>
		<tr>
			<td align="right">
				New PIN<span style="color:red">*</span>:
			</td>
			<td>
				<input type="text" id="new_pin" name="new_pin">
			</td>
			<td align="right">
				Re-type New PIN<span style="color:red">*</span>:
			</td>
			<td>
				<input type="text" id="retypenew_pin" name="retypenew_pin">
			</td>
		</tr>
		<tr>
			<td align="right">
				First Name<span style="color:red">*</span>:
			</td>
			<td colspan="3">
				<input type="text" id="firstname" name="firstname">
			</td>
		</tr>
		<tr>
			<td align="right"> 
				Last Name<span style="color:red">*</span>:
			</td>
			<td colspan="3">
				<input type="text" id="lastname" name="lastname">
			</td>
		</tr>
		<tr>
			<td align="right">
				Address<span style="color:red">*</span>:
			</td>
			<td colspan="3">
				<input type="text" id="address" name="address">
			</td>
		</tr>
		<tr>
			<td align="right">
				City<span style="color:red">*</span>:
			</td>
			<td colspan="3">
				<input type="text" id="city" name="city">
			</td>
		</tr>
		<tr>
			<td align="right">
				State<span style="color:red">*</span>:
			</td>
			<td>
				<select id="state" name="state">
				<option selected disabled>select a state</option>
				<option>Michigan</option>
				<option>California</option>
				<option>Tennessee</option>
				</select>
			</td>
			<td align="right">
				Zip<span style="color:red">*</span>:
			</td>
			<td>
				<input type="text" id="zip" name="zip">
			</td>
		</tr>
		<tr>
			<td align="right">
				Credit Card<span style="color:red">*</span>:
			</td>
			<td>
				<select id="credit_card" name="credit_card">
				<option selected disabled>select a card type</option>
				<option>VISA</option>
				<option>MASTER</option>
				<option>DISCOVER</option>
				</select>
			</td>
			<td align="left" colspan="2">
				<input type="text" id="card_number" name="card_number" placeholder="Credit card number">
			</td>
		</tr>
		<tr>
			<td align="right" colspan="2">
				Expiration Date<span style="color:red">*</span>:
			</td>
			<td colspan="2" align="left">
				<input type="text" id="expiration_date" name="expiration_date" placeholder="MM/YY">
			</td>
		</tr>
		<tr>
			<td align="right" colspan="2">
				<input type="submit" id="update_submit" name="update_submit" value="Update">
			</td>
			</form>
		<form id="cancel" action="index.php" method="post">	
			<td align="left" colspan="2">
				<input type="submit" id="cancel_submit" name="cancel_submit" value="Cancel">
			</td>
		</tr>
	</table>
	</form>
</body>
</html>
<?php
	require_once __DIR__ . '/src/app.php';
	require_once __DIR__ . '/src/utils.php';

	// some keys are different from registration... 🤦‍♂️
	// TODO get username
	// $uname = 
	$good = true;
	if (checkRequest() && assert_no_empty_fields($_POST)) {
	
		// filter out button at end
		$fields = (array_slice($_POST, 0, -1));

		// filter out retypenew_pin
		$new_fields = Array();
		foreach ($fields as $k => $v) {
			if ( $k !== 'retypenew_pin' )  
				// replaces slash in exp date for database
				$new_fields[$k] = str_replace('/', '', $v);
		}

		// validate here instead of passing to the db and getting errors.
		if (!preg_match('/\d{4}/', $new_fields['expiration_date'])) {
			$good = false;
				raise_alert('Invalid date format in card expiration field.');
		}

		if (!preg_match('/\d{16}/', $new_fields['card_number'])) {
			$good = false;
			raise_alert('Invalid card number given.');
		}

		if ($fields['retypenew_pin'] !== $new_fields['new_pin']) {
			$good = false;
			raise_alert('PIN numbers must match.');
		}

		if (strlen($fields['new_pin']) > 5) {
			$good = false;
			raise_alert('PIN can only by 5 characters long.');
		}

		if (strlen($fields['zip']) !== 5 || !preg_match('/\d{5}/', $fields['zip'])) {
			$good = false;
			raise_alert('Zip code must be 5 digits.');
		}

		if ($good) {
			$dml = 'INSERT INTO CUSTOMER ';
			$dml .= '(Username,PIN,FirstName,LastName,Address,City,';
			$dml .= 'State,ZIP,CardType,CardNumber,CardExpDate) VALUES ';

			// wrap each item in an apostrophe
			$row_data = array_map(function($x) { return "'" . $x . "'"; },
														array_values($new_fields));
			// TODO add username here
			$dml .= '(' . implode(',' , $row_data ) . ');';
			// db\crud_db($dml);
			raise_alert('Successfully updated information ' . $uname);
		}
	}
?>