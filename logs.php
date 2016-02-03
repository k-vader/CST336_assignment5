<?php 

	require 'db_connection.php';

	// Start the session
	session_start();

	if(!(isset($_SESSION['user']))) {
		header("Location: http://www.skafia.com/cst336/assignments/4/login.php");
	}

	// Gets all logs
	function getLogs() {
		global $dbConn;
		$sql = "SELECT * FROM userLogs ORDER BY timestamp DESC";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute();
		return $stmt->fetchAll();
	}

	$logs = getLogs();

	print("<html lang=\"en\">
	<head>
		<title>GAME STOP</title>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"main.css\" />
		<link href='https://fonts.googleapis.com/css?family=Black+Ops+One' rel='stylesheet' type='text/css'>
		</head>
		<center>
		<table>
		<tr><td><h3>User Sessions</h3></td></tr>
		<tr><td>&nbsp;</td></tr>
		<tr><td>username</td><td>Last Login</td></tr>");

	foreach ($logs as $log) {
		$timestamp = $log['timestamp'];
		$username = $log['username'];
		$convertedDate = gmdate("F j, Y, g:i a", $timestamp);
		print("<tr><td>$username</td><td>$convertedDate</td></tr>");
	}
	print("<tr><td>&nbsp</td></tr>
		<tr><td><a href='javascript:history.back()'>Click here to go back</a>
		</td></tr></table>");

?>