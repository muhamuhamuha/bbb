<?php session_start(); ?>
<!DOCTYPE HTML>
<head>
<title>User Login</title>
</head>
<body>
	<table align="center" style="border:2px solid blue;">
		<form action="" method="post" id="login_screen">
		<tr>
			<td align="right">
				Username<span style="color:red">*</span>:
			</td>
			<td align="left">
				<input type="text" name="username" id="username">
			</td>
			<td align="right">
				<input type="submit" name="login" id="login" value="Login">
			</td>
		</tr>
		<tr>
			<td align="right">
				PIN<span style="color:red">*</span>:
			</td>
			<td align="left">
				<input type="password" name="pin" id="pin">
			</td>
			</form>
			<form action="index.php" method="post" id="login_screen">
			<td align="right">
				<input type="submit" name="cancel" id="cancel" value="Cancel">
			</td>
			</form>
		</tr>
	</table>
</body>
</html>
<?php
	require_once __DIR__ . '/src/db.php';

	if (checkRequest()) {
		$user = "'" . $_POST['username'] . "'";
		$pin = "'" . $_POST['pin'] . "'";

		$query = "SELECT FirstName, LastName FROM CUSTOMER ";
		$res = db\select_from_db($query . "WHERE Username = $user AND PIN = $pin;");

		if (empty($res)) {
			raise_alert("Sorry, that information doesn't line up with what we have in the system.");
		} else {
			[$fname, $lname] = array_values($res[0]);
			$_SESSION['username'] = $user;
			raise_alert("Welcome back $fname $lname");
			header('Refresh: 0; url=screen2.php');
			
		}

	}
?>
