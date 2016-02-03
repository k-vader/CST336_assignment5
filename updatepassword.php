<?php

require 'db_connection.php';
session_start();

?>

<title>Update Password</title>
	<html>
	<head>
		<link rel='stylesheet' type='text/css' href='main.css'>
		<link href=https://fonts.googleapis.com/css?family=Black+Ops+One' rel='stylesheet' type='text/css'>
	</head>

<?php

if(!isset($_SESSION['user'])){
	session_destroy();
	header("Location: http://www.skafia.com/cst336/assignments/4/login.php");
}

if(isset($_POST['submitButton'])){
	function updatePassword($username, $password) {
			global $dbConn;
			$sql = "UPDATE users SET password = :password WHERE username = :username";
			$stmt = $dbConn -> prepare($sql);
			$stmt -> execute(array(":password"=>$password, ":username"=>$username));
			return $stmt;
		}

	if(isset($_POST['newPassword']) && isset($_POST['confirmPassword'])){
		$newPassword = $_POST['newPassword'];
		$confirmPassword = $_POST['confirmPassword'];

		if(strlen($newPassword) == 0){
			print("<center><br><br>Invalid password, <a href='javascript:history.back()'>try again</a><center>");
		}else{
			if($newPassword == $confirmPassword){
			updatePassword($_SESSION['user'], $newPassword);
			print("<center><br><br>Password was updated, click <a href='http://www.skafia.com/cst336/assignments/4/index.php'>here</a> to continue</center>");
		}else{
			print("<center><br><br>Passwords do not match, <a href='javascript:history.back()'>try again</a><center>");
			}
		}
	}
}else if(isset($_POST['cancelButton'])){
	header("Location: http://www.skafia.com/cst336/assignments/4/index.php");
}

if(!isset($_POST['newPassword'])){
	print("
	<body>
	<center>
	<form action=\"updatepassword.php\" method=\"post\">
		<table>
			<tr><td colspan=2 align=center><h3>Update Password<h3></td></tr>
			<tr><td colspan=2 align=center>&nbsp;</td></tr>
			<tr><td>New Password:</td><td><input type=\"password\" name=\"newPassword\"></td></tr>
			<tr><td>Confirm:</td><td><input type=\"password\" name=\"confirmPassword\"></td></tr>
			<tr><td align=center colspan=2><input type=\"submit\" name=submitButton><input type=\"submit\" 
			value=\"Cancel\" name=cancelButton></td></tr>
		</table>
	</form>
	</body>
	</center>");
}

?>

</html>