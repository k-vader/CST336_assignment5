<?php

require 'db_connection.php';
include 'global.php';
session_start();

print("<title>Login</title>
	<html>
	<head>
		<link rel='stylesheet' type='text/css' href='main.css'>
		<link href=https://fonts.googleapis.com/css?family=Black+Ops+One' rel='stylesheet' type='text/css'>
	</head>");

if(isset($_SESSION['user'])){
	session_destroy();
	header("Location: $loginURL");
}

function login($username, $password) {
	global $dbConn;
	$sql = "SELECT userId FROM users WHERE username = :username AND password = :password";
	$stmt = $dbConn -> prepare($sql);
	$stmt -> execute(array(":username"=>$username,"password"=>$password));
	return $stmt->fetch(); 
	}

function logUser($username, $timestamp) {
	global $dbConn;
	$sql = "INSERT INTO userLogs (username, timestamp)
	VALUES (:username, :timestamp)";
	$stmt = $dbConn -> prepare($sql);
	$stmt -> execute(array(":username"=>$username,":timestamp"=>$timestamp));
	return $stmt;
}

if(isset($_POST['username']) && isset($_POST['password'])){
	$username = $_POST['username'];
	$password = $_POST['password'];

	if(strlen($username) == 0 || strlen($password) == 0){
		print("<center><br><br>Invalid entry, <a href='javascript:history.back()'>try again</a><center>");
	}else{
		$login = login($username, $password);
		$logUser = logUser($username, time());
		if(isset($login[0])){
			$_SESSION['user'] = $username;
			print("<center><br><br>Login was successful, click <a href='index.php'>here</a> to continue<br>");
		}else{
			print("<center><br><br>Incorrect username or password, <a href='javascript:history.back()'>try again</a></center>");
		}
	}
}

if(!isset($_POST['username'])){
		print("<title>Login</title>
		<center>
		<html>
		<body>
			<form action=\"login.php\" method=\"post\">
			<table>
			<tr><td colspan=2 align=center><h3>Login</h3></td></tr>
			<tr><td colspan=2 align=center>&nbsp;</td></tr>
			<tr><td>Username:</td><td><input type=\"text\" name=\"username\"></td></tr>
			<tr><td>Password:</td><td><input type=\"text\" name=\"password\"></tr>
			<tr><td colspan=2 align=center><input type=\"submit\" name='submitButton'></td>
			<tr><td colspan=2>&nbsp;</td></tr>
			<tr><td colspan=2 align=center><a href=\"register.php\">Or you can register here</a></td></tr>

		</form>
		</body>
		</html></center>");
	}

?>