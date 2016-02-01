<?php 
	require '../db_connection.php';
	
	//Gets all games sorted by title
	function getGames() {
		global $dbConn;
		$sql = "SELECT * FROM games_info ORDER BY gameTitle";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute();
		return $stmt->fetchAll();
	}
	
	//Gets list from table. Takes a table and a heading to sort as arguments 
	function getList($table, $headingName) {
		global $dbConn;
		$sql = "SELECT * FROM $table ORDER BY $headingName";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute();
		return $stmt->fetchAll();
	}
	
	//Filter games. First parameter defines the filter category and second paramenter defines value to compare. 
	function filterGames($filter, $value) {
		global $dbConn;
		$sql = "SELECT * FROM games_info WHERE $filter = :value";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute(array(':value' => $value));
		return $stmt->fetchAll();
	}
	
	function sortGames($sorting) {
		global $dbConn;
		if ($sorting == 1) {
			$sql = "SELECT * FROM games_info ORDER BY gameTitle ASC";
		} elseif ($sorting == 2) {
			$sql = "SELECT * FROM games_info ORDER BY gameTitle DESC";
		} elseif ($sorting == 3) {
			$sql = "SELECT * FROM games_info ORDER BY gamePrice DESC";
		} elseif ($sorting == 4) {
			$sql = "SELECT * FROM games_info ORDER BY gamePrice ASC";
		} else {
			$sql = "SELECT * FROM games_info";
		}
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
	
	function getGenre($genreId) {
		global $dbConn;
		$sql = "SELECT genreName FROM game_genres WHERE genreId = $genreId";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute();
		return $stmt->fetch();
	}
	
	function getRating($ratingId) {
		global $dbConn;
		$sql = "SELECT ratingName FROM game_ratings WHERE ratingId = $ratingId";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute();
		return $stmt->fetch();
	}
	
	function getPublisher($publisherId) {
		global $dbConn;
		$sql = "SELECT publisherName FROM game_publishers WHERE publisherId = $publisherId";
		$stmt = $dbConn -> prepare($sql);
		$stmt -> execute();
		return $stmt->fetch();
	}
	
	//Gets piblishers, genres and ratings
	$gamePublishers = getList('game_publishers', 'publisherName');
	$gameGenres = getList('game_genres','genreName');
	$gameRatings = getList('game_ratings', 'ratingName'); 
	
	//Checks which filter button was submitted and gets appropriate list
	if (isset($_POST['filterGenre'])) {
		$gameNames = filterGames('gameGenre', $_POST['genreId']);
	} elseif (isset($_POST['filterPublisher'])) {
		$gameNames = filterGames('gamePublisher', $_POST['publisherId']);
	} elseif (isset($_POST['filterRatings'])) {
		$gameNames = filterGames('gameRating', $_POST['ratingId']);
	} elseif (isset($_POST['sortGames'])) {
		$gameNames = sortGames($_POST['sortingMethod']);
	} else {
		$gameNames = getGames();
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>new_file</title>
		<link rel="stylesheet" type="text/css" href="css/main.css" />
	</head>
	<body>
		<div id="wrapper">
			<header>
				<form method="post" action="logout.php">
					<input type="submit" value="Logout"/>
				</form>
				<form method="post" action="update_db.php">
					<input type="submit" value="Update"/>
				</form>
				<h1>Game Stop</h1>
			</header>
			<nav>
				<form method="POST">
					<select name="genreId">
						<option value="-1">- Select Genre</option>
						<?php 
							foreach ($gameGenres as $genre) {
								echo "<option value=\"" . $genre['genreId'] . "\">" . $genre['genreName'] . "</option>";
							}
						?>
					</select>
					<input type="submit" name="filterGenre" value="Filter" />
					<select name="publisherId">
						<option value="-1">- Select Publisher</option>
						<?php 
							foreach ($gamePublishers as $publisher) {
								echo "<option value=\"" . $publisher['publisherId'] . "\">" . $publisher['publisherName'] . "</option>";
							}
						?>
					</select>
					<input type="submit" name="filterPublisher" value="Filter" />
					<select name="ratingId">
						<option value="-1">- Select Rating</option>
						<?php
							foreach ($gameRatings as $rating) {
								echo "<option value=\"" . $rating['ratingId'] . "\">" . $rating['ratingName'] . "</option>";
							}
						?>
					</select>
					<input type="submit" name="filterRatings" value="Filter" />
					
					
					<input type="submit" name="clearFilter" value="Clear Filter" />
					
					<select name="sortingMethod">
						<option value="-1">- Sort By</option>
						
						<option value="1">A-Z</option>
						<option value="2">Z-A</option>
						<option value="3">Price: High to Low</option>
						<option value="4">Price: Low to High</option>
							
					</select>
					<input type="submit" name="sortGames" value="Sort" />
					
					
					
					
				</form>
			</nav>
				<?php 
					if (isset($_POST['moreInfo'])) {
						$gameInfo = getGame($_POST['gameId']);
						$currentGenre = getGenre($gameInfo['gameGenre']);
						$currentRating = getRating($gameInfo['gameRating']);
						$currentPublisher = getPublisher($gameInfo['gamePublisher']);
						echo "<div id=\"moreInfoPanel\">";
						echo "<img src = \"images\\" . $gameInfo['gameId'] . ".jpg\" class = \"gameArt\" height = \"295px\" width = \"225px\">";
						echo "<h5>". $gameInfo['gameTitle'] . "</h5>";
						echo "<p> Players: " . $gameInfo['players'] . "</p>";
						echo "<p> Co-Op Play: " . $gameInfo['co-op'] . "</p>";
						echo "<p>Rating: ". $currentRating[0] . "</p>";
						echo "<p>Genre: ". $currentGenre[0] . "</p>";
						echo "<p>Publisher: ". $currentPublisher[0] . "</p>";
						echo "<p>Release Date: ". $gameInfo['releaseDate'] . "</p>";
						echo "<a href=\"index.php\">Close More Info</a>";
						echo "</div>";
					}
				?>
			<div id="gamesList">
					<?php 
						foreach ($gameNames as $game) {
							echo "<div class=\"gameProfile\">";
							echo "<img src = \"images\\" . $game['gameId'] . ".jpg\" class = \"gameArt\" height = \"190px\" width = \"150px\">";
							echo "<h5>". $game['gameTitle'] . "</h5>";
							echo "<p>". $game['gamePrice'] . "</p>";
							echo "<form method=\"POST\">";
								echo "<input type=\"submit\" name=\"moreInfo\" value=\"More Info\"/>";
								echo "<input type=\"hidden\" name=\"gameId\" value=\"" . $game['gameId'] . "\"/>";
							echo "</form>";
							echo "</div>";
						}
					?>
			</div>
		</div>
	</body>
</html>