<?php 


include('config/db_connect.php');
include('templates/header.php');


	// check connection
	if(!$conn){
		echo 'Connection error: '. mysqli_connect_error();
	}
	
	$genre_code = $genre_description = '';
	$errors = array( 'genre_code' => '',  'genre_description' => '');

	if(isset($_POST['insert'])){
		
		
		
		// check genre_code don't care what the code is
		if(empty($_POST['genre_code'])){
			$errors['genre_code'] = 'Please Enter category code';
		} else{
			$genre_code = $_POST['genre_code'];
		}
		
		// check category description, don't care
		if(empty($_POST['genre_description'])){
			$errors['genre_description'] = 'Please Enter description';
		} else{
			$rep_email = $_POST['genre_description'];

			
		}


		if(array_filter($errors)){
			/*
			echo 'errors in form';
			foreach($errors as $error){
			echo($error);
			
			}
			*/ 
			}
			else {
			// escape sql chars
			$genre_code = mysqli_real_escape_string($conn, $_POST['genre_code']);
			$genre_description = mysqli_real_escape_string($conn, $_POST['genre_description']);
	
			
			
			//$sql = "INSERT INTO distributor_rep(Rep_name, rep_work_number, rep_cell_number, rep_email, rep_sup_ID) VALUES(?, ?, ?, ?, ? );";
			$sql = "INSERT INTO genres(genre_code, genre_description) VALUES(?, ?);";
			
							
			$stmt = mysqli_stmt_init($conn);
			
			if(!mysqli_stmt_prepare($stmt, $sql)){
				header("location: index.php?ERRORdistributorRepAdd");
				exit();
			}
			//mysqli_stmt_bind_param($stmt, "sssss", $supRepName, $rep_work_number, $rep_cell_number, $rep_email, $rep_sup_id);
			mysqli_stmt_bind_param($stmt, "ss", $genre_code, $genre_description);
			
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
			
			header('Location: rootlyPowers.php');		
		}

		
	} // end POST check
?>
<!DOCTYPE html>
<html>

	
	<section class="container grey-text">
		<h4 class="center">Add a Genre</h4>
		<form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		
			<div class="red-text"><?php echo $errors['genre_code']; ?></div>		
			<input type="text" name="genre_code" id="genre_code" value="<?php echo htmlspecialchars($genre_code) ?>">
			<label for="genre_code">Genre Code</label>
			
			<div class="red-text"><?php echo $errors['genre_description']; ?></div>		
			<input type="text" name="genre_description" id="genre_description" value="<?php echo htmlspecialchars($genre_description) ?>">
			<label for="genre_description">Genre Description</label>
			

			
			
			
			<div class="center">
				<input type="submit" name="insert" value="Submit" class="btn brand z-depth-0">
			</div>
			
		</form>
	</section>
	<?php include('templates/footer.php'); ?>

</html>
  


