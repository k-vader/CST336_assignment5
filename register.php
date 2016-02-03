<?php

require 'db_connection.php';
session_start();

print("<title>Register</title>
	<html>
	<head>
		<link rel='stylesheet' type='text/css' href='main.css'>
		<link href=https://fonts.googleapis.com/css?family=Black+Ops+One' rel='stylesheet' type='text/css'>
	</head>");

function checkUser($username) {
		global $dbConn;
		$sql = "SELECT username FROM users WHERE username = :username LIMIT 1";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute(array(":username"=>$username));
		return $stmt->fetch();
	}

function register($username, $password) {
		global $dbConn;
		$sql = "INSERT INTO users (username, password)
		VALUES (:username, :password)";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute(array(":username"=>$username,":password"=>$password));
		return $stmt;
	}

function logUser($username, $timestamp) {
	global $dbConn;
	$sql = "INSERT INTO userLogs (username, timestamp)
	VALUES (:username, :timestamp)";
	$stmt = $dbConn -> prepare($sql);
	$stmt -> execute(array(":username"=>$username,":timestamp"=>$timestamp));
	return $stmt;
}

if(isset($_POST['submitButton'])){
	if(isset($_POST['username']) && isset($_POST['password'])){
		$username = $_POST['username'];
		$password = $_POST['password'];

		if(strlen($username) == 0 || strlen($password) == 0){
			print("<center><br><br>Invalid entry, <a href='javascript:history.back()'>try again</a><center>");
		}else{
			$checkUser = checkUser($username);
			
			if(!isset($checkUser['username'])){
				$register = register($username, $password);	
				if($register){
					$logUser = logUser($username, time());
					$_SESSION['user'] = $username;
					print("<center><br><br>Account creation successful, click <a href='index.php'>here</a> to continue</center>");
				}else{
					print("<center><br><br>There was an unknown error!</center>");
				}
			}else{
				print("<center><br><br>Username exists, <a href='javascript:history.back()'>try again</a></center>");
			}
		}
	}
}

if(!isset($_POST['username'])){
	print("<title>Register</title>
	<center>
	<html>
	<body>
		<form action=\"register.php\" method=\"post\">
		<table>
		<tr><td colspan=2 align=center><h3>Register</3></td></tr>
		<tr><td colspan=2 align=center>&nbsp;</td></tr>
		<tr><td>Username:</td><td><input type=\"text\" name=\"username\"></td></tr>
		<tr><td>Password:</td><td><input type=\"text\" name=\"password\"></tr>
		<tr><td colspan=2 align=center><input type=\"submit\" name='submitButton'></td>
		<tr><td colspan=2>&nbsp;</td></tr>
		<tr><td colspan=2 align=center><a href=\"login.php\">Or you can log in here</a></td></tr>

	</form>
	</body>
	</html></center>");
}

?>

</html>
