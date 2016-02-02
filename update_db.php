<?php 
	require 'db_connection.php';

	function getGames() {
		global $dbConn;
		$sql = "SELECT gameId, gameTitle FROM games_info ORDER BY gameTitle";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute();
		return $stmt->fetchAll();
	}
	
	function getList($table, $headingName) {
		global $dbConn;
		$sql = "SELECT * FROM $table ORDER BY $headingName";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute();
		return $stmt->fetchAll();
	}
	
	function getGame($gameId){
		global $dbConn;
		$sql = "SELECT * FROM games_info WHERE gameId = :gameId";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute(array(":gameId"=>$gameId));
		return $stmt->fetch(); 
	}
	
	if (isset($_POST['save'])) {
		$sql = "UPDATE games_info SET gameTitle = :gameTitle, gamePrice = :gamePrice
				WHERE gameId = :gameId";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute(array(":gameTitle"=>$_POST['gameTitle'],
								":gamePrice"=>$_POST['gamePrice'],
								":gameId"=>$_POST['gameId']				
		)); 

		$message = "Game Info Updated!!"; 
	}
	
	if (isset($_POST['delete'])) {
		$sql = "DELETE FROM games_info WHERE gameId = :gameId";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute(array(':gameId' => $_POST['gameId']));
		
		$message = "The game was deleted!";
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Update DB</title>
		<link rel="stylesheet" type="text/css" href="main.css" />
		<link href='https://fonts.googleapis.com/css?family=Black+Ops+One' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div id="wrapper">
			<header>
				<a href="index.php">Return To Main Page</a>
				<h1>Update DB</h1>
			</header>
				<form method="post">
					<select name="gameId">
						<option value="-1">- Select Game to Update</option>
						<?php
							$gameNames = getGames(); 
							foreach ($gameNames as $game) {
								echo "<option value=\"" . $game['gameId'] . "\">" . $game['gameTitle'] . "</option>";
							}
						?>
					</select>
					<input type="submit" name="update" value="Update" />
					<input type="submit" name="delete" value="Delete" />
				</form>
				<div id="update">
				<? 
					if (isset($_POST['update'])) {
						$gameInfo = getGame($_POST['gameId']);
						echo "<form method='post'>";
						echo "GAME TITLE: <input type='text' name='gameTitle' value='" . $gameInfo['gameTitle'] . "' /><br />";
						echo "PRICE: <input type='text' name='gamePrice' value='" . $gameInfo['gamePrice'] . "' /><br />";
						echo "<input type='hidden' name='gameId' value='" . $gameInfo['gameId'] . "'//>";
						echo "<input type='submit' name='save' value='Save'>"; 
						echo "</form>";
						
					}
					
					if (!empty($message)) {
						echo "<h4>" . $message . "</h4>";
					}
				?>
				</div>
		</div>
	</body>
</html>