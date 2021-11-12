<!-- UI: Prithviraj Narahari, php code: Alexander Martens -->
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
			<form id="no_registration" action="index.php" method="post">
			<td colspan="2" align="center">
				<input type="submit" id="donotregister" name="donotregister" value="Don't Register">
			</td>
			</form>
		</tr>
	</table>
</body>
</HTML>
<?php
	require_once __DIR__ . '/src/db.php';
	require_once __DIR__ . '/src/utils.php';


	function validate_all_keys(array $keys): bool {
		foreach($keys as $k => $v) {
			if ( empty($v) ) {
				raise_alert("$k cannot be empty.");
				return false;
			}
		}
		return true;
	}

	// if form has been submitted
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$sql = 'SELECT username FROM dummy_user_data;';
		$sql_result = array_map(function($x) { return $x['username']; }, db\select_from_db($sql));
		$uname = $_POST['username'];
		$good = false;

		// validate username
		if ( in_array($uname, $sql_result) && !empty($uname) ) {
			raise_alert($uname . ' already registered, please choose another username.');
		} else {
			$good = true;
		}

		// ensure all fields are filled and update db if so
		if (validate_all_keys($_POST) && $good) {

			// filter out button at the end
			$fields = (array_slice($_POST, 0, -1));

			// filter out retype_pin
			$new_fields = Array();
			foreach ($fields as $k => $v) {
				if ( $k !== 'retype_pin' )
					$new_fields[$k] = $v;
			}
			$ddl = 'INSERT INTO dummy_user_data (';
			$ddl .= implode(', ', array_keys($new_fields)) . ') VALUES (';
			$ddl .= implode(',' , array_values($new_fields)) . ');';
			db\insert_into_db($ddl);
		}
	}

?>
