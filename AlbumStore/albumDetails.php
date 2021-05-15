<?php 
//make this more secure

	include('config/db_connect.php');
	include('templates/header.php'); 
	$sql = "SELECT Album_ID, a_name FROM albums WHERE Album_ID";
	
	// get the query result
        $result = mysqli_query($conn, $sql);

        // fetch result in array format
        $albums = mysqli_fetch_assoc($result);

        mysqli_free_result($result);
		
		
	// write query for all distributors
	$sql = 'SELECT Dist_ID, Dist_name FROM distributors ORDER BY Dist_ID';

	// get the result set (set of rows)
	$result = mysqli_query($conn, $sql);

	// fetch the resulting rows as an array
	$distributors = mysqli_fetch_all($result, MYSQLI_ASSOC);


	// write query for all Bands
	$sql = 'SELECT band_id, b_name FROM bands ORDER BY band_id';

	// get the result set (set of rows)
	$result = mysqli_query($conn, $sql);

	// fetch the resulting rows as an array
	$bands = mysqli_fetch_all($result, MYSQLI_ASSOC);

	mysqli_free_result($result);		
	
	// check GET request id param
	if(isset($_GET['id'])){
		$_SESSION['id'] = $_GET['id'];

	}

	if(isset($_POST['delete'])){

		// no real user input so security is okay
		$id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_change']);

		$sql = "DELETE FROM albums WHERE Album_ID = $id_to_delete";

		if(mysqli_query($conn, $sql)){
			header('Location: albumEdit.php');
		} else {
			echo 'query error: '. mysqli_error($conn);
		}

	}

	
	$a_name = $a_release_date = $a_price = $a_dist_ID = '';
	
	$errors = array('a_name' => '', 'a_release_date' => '', 'a_price' => '', 'a_dist_ID' => '');
	
	//update to a new group
	if(isset($_POST['update'])){
		
		if(empty($_POST['a_name'])){
				$errors['a_name'] = 'Please Enter a name';
			}
			
			// check publication date
			if(empty($_POST['a_release_date'])){
				$errors['a_release_date'] = 'Please Enter a release date';
			}
			
			// check price
			if(empty($_POST['a_price'])){
				$errors['a_price'] = 'Please Enter a price';
			} 
			
			
			$a_price = mysqli_real_escape_string($conn, $_POST['a_price']);
			$a_price = number_format((float) $a_price, 2, '.', '');
				
			if(!filter_var($a_price, FILTER_VALIDATE_FLOAT))
			{
				$errors['a_price'] = 'Price must be a floating point number';
			}
			
			
			if(array_filter($errors)){
			//echo 'errors in form';
			//echo $errors['Dist_name'];
		
			
			
			} 		
			else
				{
			
				$a_name = mysqli_real_escape_string($conn, $_POST['a_name']);
				$a_release_date = mysqli_real_escape_string($conn, $_POST['a_release_date']);
				//$a_price = mysqli_real_escape_string($conn, $_POST['a_price']);
				//$Sup_ID = mysqli_real_escape_string($conn, $_POST['Sup_ID']);
				$a_dist_ID = mysqli_real_escape_string($conn, $_POST['a_dist_ID']);
				$a_band_id = mysqli_real_escape_string($conn, $_POST['a_band_id']);
				
				$id_to_update = mysqli_real_escape_string($conn, $_POST['id_to_change']);

				$sql = "UPDATE albums set a_name = ?, a_release_date = ?, a_price = ?, a_dist_ID = ?, a_band_id = ? where Album_ID = $id_to_update";

					$stmnt = mysqli_stmt_init($conn);
				if(!mysqli_stmt_prepare($stmnt, $sql)){
					header("location: index.php?ERROR_UPDATE");
				}
			
				mysqli_stmt_bind_param($stmnt, "sssss", $a_name, $a_release_date, $a_price, $a_dist_ID, $a_band_id);
				mysqli_stmt_execute($stmnt);
				
				
				header("Location: albumEdit.php");
			}
	}// end update	

	
		//global variables to store data
		//$albums = $distributors = '';



	
		//the session variable has what we need now

		$sid = $_SESSION['id'];
		$sql = "SELECT * FROM albums WHERE Album_ID = $sid";

		
		// get the query result
		// get the query result
		$result = mysqli_query($conn, $sql);

		// fetch result in array format
		$albums = mysqli_fetch_assoc($result);

		
		mysqli_free_result($result);
		mysqli_close($conn);	

?>

<!DOCTYPE html>
<html>



	<div class="container center grey-text">
		<?php if($albums): ?>
			<h4><?php echo $albums['a_name']; ?></h4>
			<p>ID: <?php echo $albums['Album_ID']; ?></p>
			
	<!--UPDATE FORM-->		
	<section class="container grey-text">
		<h4 class="center">Edit album</h4>
		<form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			
			<div class="red-text"><?php echo $errors['a_name']; ?></div>
			<input type="text" name="a_name" id="a_name" value="<?php echo $albums['a_name']; ?>">
			<label class="left" for="a_name">Album Name</label>

				
			<div class="red-text"><?php echo $errors['a_release_date']; ?></div>		
			<input type="text" name="a_release_date" id="a_release_date" value="<?php echo $albums['a_release_date']; ?>">
			<label class="left" for="a_release_date">Album Release Date</label>
			
			
			<div class="red-text"><?php echo $errors['a_price']; ?></div>		
			<input type="text" name="a_price" id="a_price" value="<?php echo $albums['a_price']; ?>">
			<label class="left" for="a_price">album price</label>
			
			<input type="hidden" name="id_to_change" value="<?php echo $_SESSION['id']; ?>">

			<div class="container">
				<div class="row">
					<select name="a_dist_ID" class="browser-default">
						<option> Distributor</option>
							<?php foreach($distributors as $distributor): ?>
								<option value="<?php echo $distributor['Dist_ID']?>"><?php echo $distributor['Dist_name'] ?></option>

							<?php endforeach; ?>
					
						
					</select>
					
					<select name="a_band_id" class="browser-default">
						<option> Band</option>
							<?php foreach($bands as $band): ?>
								<option value="<?php echo $band['band_id']?>"><?php echo $band['b_name'] ?></option>

							<?php endforeach; ?>
					
						
					</select>					
			<div class="center">
				<input type="submit" name="update" value="Update" class="btn brand z-depth-0">
			</div>
				</div>
			</div>			
		</form>
	</section>
			
			<!-- DELETE FORM -->
			<form action="albumDetails.php" method="POST">
				<input type="hidden" name="id_to_change" value="<?php echo $albums['Album_ID']; ?>">
				<input type="submit" name="delete" value="Delete" class="btn brand z-depth-0">
			</form>

		<?php else: ?>
			<h5>No such album exists.</h5>
		<?php endif ?>
	</div>
	<?php include('templates/footer.php'); ?>

</html>
