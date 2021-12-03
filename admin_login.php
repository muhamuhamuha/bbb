<?php
require_once __DIR__ . '/src/db.php';

if (isset($_POST['login'])){
	$adminName = $_POST['adminname'];
	$adminPIN = $_POST['pin'];
	
	$q1 = db\select_from_db("SELECT Username FROM ADMINISTRATOR WHERE Username = 'AD001';");
	$q2 = db\select_from_db("SELECT PIN FROM ADMINISTRATOR WHERE Username = 'AD001';");

	$adminNameQery = $q1[0]['Username'];
	$adminPINQuery = $q2[0]['PIN'];
	
	if ($adminNameQery == $adminName && $adminPINQuery == $adminPIN) {
		raise_alert('Welcome Administrator!');
		header('Refresh: 0; url=admin_tasks.php');
	} else {
		raise_alert("Invalid Adminname or PIN!");
	}
}

?>

<!DOCTYPE HTML>
<head>
<title>Admin Login</title>
</head>

<body>
<table align="center" style="border:2px solid blue;">
		<form action="" method="post" id="adminlogin_screen">
		<tr>
			<td align="right">
				Adminname<span style="color:red">*</span>:
			</td>
			<td align="left">
				<input type="text" name="adminname" id="adminname">
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
