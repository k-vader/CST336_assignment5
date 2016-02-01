<?php 
	require '../db_connection.php';

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
	
	if (isset($_GET['save'])) {
		echo $_GET['gameTitle'];
		$sql = "UPDATE games_info SET gameTitle = :gameTitle, gamePrice = :gamePrice
				WHERE gameId = :gameId";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute(array(":gameTitle"=>$_GET['gameTitle'],
								":gamePrice"=>$_GET['gamePrice'],
								":gameId"=>$_GET['gameId']				
		)); 

		echo "Game Info Updated!! <br> <br>"; 
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Update DB</title>
		<link rel="stylesheet" type="text/css" href="css/main.css" />
	</head>
	<body>
		<div id="wrapper">
			<header>
				<h1>Update DB</h1>
			</header>
				<form method="get">
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
				</form>
				<div id="update">
				<? 
					if (isset($_GET['update'])) {
						$gameInfo = getGame($_GET['gameId']);
						echo "<form method='get'>";
						echo "Game Title: <input type='text' name='gameTitle' value='" . $gameInfo['gameTitle'] . "' /><br />";
						echo "Price: <input type='text' name='gamePrice' value='" . $gameInfo['gamePrice'] . "' /><br />";
						echo "<input type='hidden' name='gameId' value='" . $gameInfo['gameId'] . "'//>";
						echo "<input type='submit' name='save' value='Save'>"; 
						echo "</form>";
					}
				?>
		</div>
		</div>
	</body>
</html>