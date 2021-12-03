<!-- UI: Prithviraj Narahari, php code: Alexander Martens -->
<?php session_start(); ?>
<head>
<title> CUSTOMER REGISTRATION </title>
</head>
<body>
	<table align="center" style="border:2px solid blue;">
		<tr>
			<form id="register" action="" method="post">
			<td align="right">
				Username<span style="color:red">*</span>:
			</td>
			<td align="left" colspan="3">
				<input type="text" id="username" name="username" placeholder="Enter your username">
			</td>
		</tr>
		<tr>
			<td align="right">
				PIN<span style="color:red">*</span>:
			</td>
			<td align="left">
				<input type="password" id="pin" name="pin">
			</td>
			<td align="right">
				Re-type PIN<span style="color:red">*</span>:
			</td>
			<td align="left">
				<input type="password" id="retype_pin" name="retype_pin">
			</td>
		</tr>
		<tr>
			<td align="right">
				Firstname<span style="color:red">*</span>:
			</td>
			<td colspan="3" align="left">
				<input type="text" id="firstname" name="firstname" placeholder="Enter your firstname">
			</td>
		</tr>
		<tr>
			<td align="right">
				Lastname<span style="color:red">*</span>:
			</td>
			<td colspan="3" align="left">
				<input type="text" id="lastname" name="lastname" placeholder="Enter your lastname">
			</td>
		</tr>
		<tr>
			<td align="right">
				Address<span style="color:red">*</span>:
			</td>
			<td colspan="3" align="left">
				<input type="text" id="address" name="address">
			</td>
		</tr>
		<tr>
			<td align="right">
				City<span style="color:red">*</span>:
			</td>
			<td colspan="3" align="left">
				<input type="text" id="city" name="city">
			</td>
		</tr>
		<tr>
			<td align="right">
				State<span style="color:red">*</span>:
			</td>
			<td align="left">
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
			<td align="left">
				<input type="text" id="zip" name="zip">
			</td>
		</tr>
		<tr>
			<td align="right">
				Credit Card<span style="color:red">*</span>
			</td>
			<td align="left">
				<select id="credit_card" name="credit_card">
				<option selected disabled>select a card type</option>
				<option>VISA</option>
				<option>MASTER</option>
				<option>DISCOVER</option>
				</select>
			</td>
			<td colspan="2" align="left">
				<input type="text" id="card_number" name="card_number" placeholder="Credit card number">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				Expiration Date<span style="color:red">*</span>:
			</td>
			<td colspan="2" align="left">
				<input type="text" id="expiration" name="expiration" placeholder="MM/YY">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"> 
				<input type="submit" id="register_submit" name="register_submit" value="Register">
			</td>
			</form>
			<form id="no_registration" action="screen2.php" method="post">
			<td colspan="2" align="center">
				<input type="submit" id="donotregister" name="donotregister" value="Don't Register">
			</td>
			</form>
		</tr>
	</table>
</body>
<script>
	// pop alert if the user refused to register
	const refuseBtn = document.querySelector('input[id="donotregister"]');

	$msg = 'In order to proceed with the payment, you need to register first.'
	refuseBtn.addEventListener('click', () => alert($msg));

</script>
</HTML>
<?php
	require_once __DIR__ . '/src/db.php';
	require_once __DIR__ . '/src/utils.php';
	require_once __DIR__ . '/src/app.php';

	if (checkRequest()) {  // form has been submitted
		$sql = 'SELECT Username FROM CUSTOMER;';
		$sql_result = array_map(function($x) { return $x['Username']; }, db\select_from_db($sql));
		$uname = $_POST['username'];
		$good = false;

		// validate username
		if ( in_array($uname, $sql_result) && !empty($uname) ) {
			raise_alert($uname . ' already registered, please choose another username.');
		} else {
			$good = true;
		}

		// ensure all fields are filled and update db if so
		if (assert_no_empty_fields($_POST) && $good) {

			// filter out button at the end
			$fields = (array_slice($_POST, 0, -1));

			// filter out retype_pin
			$new_fields = Array();
			foreach ($fields as $k => $v) {
				if ( $k !== 'retype_pin' )
					// replaces slash in exp date for database
					$new_fields[$k] = str_replace('/', '', $v);
			}

			// validate here instead of passing to the db and getting errors.
			if (!preg_match('/\d{4}/', $new_fields['expiration'])) {
				$good = false;
				 raise_alert('Invalid date format in card expiration field.');
			}

			if (!preg_match('/\d{16}/', $new_fields['card_number'])) {
				$good = false;
				raise_alert('Invalid card number given.');
			}

			if ($fields['retype_pin'] !== $new_fields['pin']) {
				$good = false;
				raise_alert('PIN numbers must match.');
			}

			if (strlen($fields['pin']) > 5) {
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
				$dml .= '(' . implode(',' , $row_data ) . ');';
				db\crud_db($dml);
				raise_alert('Successfully registered ' . $uname);
				// save user
				$_SESSION['username'] = $uname;
				header('Refresh: 0; url=confirm_order.php;');
			}
		}
	}

?>
