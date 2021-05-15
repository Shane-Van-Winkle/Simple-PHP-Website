<?php 

include('config/db_connect.php');
include('templates/header.php');
//this function is a copy of supplierRepNameTaken but for supplier names






	// write query for all categories
	$sql = 'SELECT genre_code, genre_description FROM genres ORDER BY genre_code';

	// get the result set (set of rows)
	$result = mysqli_query($conn, $sql);

	// fetch the resulting rows as an array
	$genres = mysqli_fetch_all($result, MYSQLI_ASSOC);
	
	
	// query for all albums
		
	$sql = 'SELECT Album_ID, a_name FROM albums ORDER BY a_name';

	// get the result set (set of rows)
	$result = mysqli_query($conn, $sql);

	// fetch the resulting rows as an array
	$albums = mysqli_fetch_all($result, MYSQLI_ASSOC);
	


	mysqli_free_result($result);


	// check connection
	if(!$conn){
		echo 'Connection error: '. mysqli_connect_error();
	}
	

	if(isset($_POST['link'])){


			$genre_code = mysqli_real_escape_string($conn, $_POST['genre_code']);
			$Album_ID = mysqli_real_escape_string($conn, $_POST['Album_ID']);
			
			$sql = "INSERT INTO albums_assigned_to_genres(aag_Album_ID, aag_Genre_Code) VALUES(?, ?);";
			// INSERT INTO albums_assigned_to_genres(aag_Album_ID, aag_Genre_Code) VALUES(2, "MIDEMO");
						
			$stmt = mysqli_stmt_init($conn);
			
			if(!mysqli_stmt_prepare($stmt, $sql)){
				header("location: index.php?ERRORGenreLink");
				exit();
			}

			mysqli_stmt_bind_param($stmt, "ss", $Album_ID, $genre_code);
			
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
			
			header("Location: rootlyPowers.php?$genre_code");		
	}// end post
?>
<!DOCTYPE html>
<html>

	
	<section class="container grey-text">
		<h4 class="center">Link Albums to Genres</h4>
		<form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		
			
			<div class="container">
				<div class="row">
					<select name="genre_code" class="browser-default">
						<option>Genre</option>
							<?php foreach($genres as $genre): ?>
								<option value="<?php echo $genre['genre_code']?>"><?php echo $genre['genre_code'] ?></option>

							<?php endforeach; ?>
					
						
					</select>
				</div>
			</div>			
			
			<div class="container">
				<div class="row">
					<select name="Album_ID" class="browser-default">
						<option> album</option>
							<?php foreach($albums as $album): ?>
								<option value="<?php echo $album['Album_ID']?>"><?php echo $album['a_name'] ?></option>

							<?php endforeach; ?>
					
						
					</select>
				</div>
			</div>
			
			
			
			<div class="center">
				<input type="submit" name="link" value="Link" class="btn brand z-depth-0">
			</div>
			
		</form>
	</section>
	<?php include('templates/footer.php'); ?>

</html>
  


