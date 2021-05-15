<?php 
//make this more secure

function bandNameTaken($conn, $name){
	$sqlQuery = "SELECT * FROM bands WHERE b_name = ?;";
	$stmnt = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmnt, $sqlQuery)){
		return "mysql statement failed";
	}
	
	mysqli_stmt_bind_param($stmnt, "s", $name);
	mysqli_stmt_execute($stmnt);
	
	$resultData = mysqli_stmt_get_result($stmnt);
	
	if($row = mysqli_fetch_assoc($resultData)){
		return $row;
	}
	else{
		$result = false;
		return $result;
	
	mysqli_stmt_close($stmnt);
	}
}


	
	include('config/db_connect.php');
	include('templates/header.php'); 
	
	$sql = "SELECT band_id, b_name FROM bands WHERE band_id ";
	
	// get the query result
        $result = mysqli_query($conn, $sql);

        // fetch result in array format
        $bands = mysqli_fetch_assoc($result);

        mysqli_free_result($result);
		
		

	if(isset($_GET['id']))
	{
		$_SESSION['id'] = $_GET['id'];
	}
		

	if(isset($_POST['delete'])){

		// no real user input so security is okay
		$id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_change']);
		
		$sql = "UPDATE Albums SET a_band_id = null WHERE a_band_id = '$id_to_delete'";
		if(mysqli_query($conn, $sql)){
			//header('Location: bandEdit.php');
		} else {
			echo 'query error: '. mysqli_error($conn);
		}
		
		
		$sql = "DELETE FROM bands WHERE band_id = '$id_to_delete'";

		if(mysqli_query($conn, $sql)){
			header('Location: bandEdit.php');
		} else {
			echo 'query error: '. mysqli_error($conn);
		}

	}
	
	$b_name = '';
	$errors = array('b_name' => '');
	//update to a new group
	if(isset($_POST['update'])){
		
		// check b_name don't care what the username is
		if(empty($_POST['b_name'])){
			$errors['b_name'] = 'Please Enter a band name';
		} else{
			$b_name = $_POST['b_name'];
		}
		
		//change for band name
		if(bandNameTaken($conn, $b_name) !== false){
			$errors['b_name'] = 'This band Exists';
		}
		

	
				
		
		if(array_filter($errors)){
			
			//echo 'errors in form';
			//echo $errors['b_name'];	
			
		} 
		else 
		{
			
			//echo("I am here too for some reason");
			// escape sql chars
			//$Student_ID = mysqli_real_escape_string($conn, $_POST['Student_ID']);
		
			$b_name = mysqli_real_escape_string($conn, $_POST['b_name']);
			
			$id_to_update = mysqli_real_escape_string($conn, $_POST['id_to_change']);

			$sql = "UPDATE bands set b_name = ? where band_id = $id_to_update";
		
			
			$stmnt = mysqli_stmt_init($conn);
			if(!mysqli_stmt_prepare($stmnt, $sql)){
				header("location: index.php?ERROR_UPDATE");
			}


			mysqli_stmt_bind_param($stmnt, "s", $b_name);
			mysqli_stmt_execute($stmnt);

			header('Location: bandEdit.php');
		}
	}// end update	

		//the session variable has what we need now

		$sid = $_SESSION['id'];
		$sql = "SELECT * FROM bands WHERE band_id = $sid";

		
		// get the query result
		// get the query result
		$result = mysqli_query($conn, $sql);

		// fetch result in array format
		$bands = mysqli_fetch_assoc($result);

		
		mysqli_free_result($result);
		mysqli_close($conn);
		

		
	

?>

<!DOCTYPE html>
<html>

	

	<div class="container center grey-text">
		<?php if($bands): ?>
			<h4><?php echo $bands['b_name']; ?></h4>
			<p>ID: <?php echo $bands['band_id']; ?></p>
			
	<!--UPDATE FORM-->		
	<section class="container grey-text">
		<h4 class="center">Edit band</h4>
		<form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		
			

			<div class="red-text"><?php echo $errors['b_name']; ?></div>		
			<input type="text" name="b_name" id="b_name" value="<?php echo htmlspecialchars($bands['b_name']) ?>">
			<label class="left" for="b_name">band Name</label>
			
	
	
			<input type="hidden" name="id_to_change" value="<?php echo $_SESSION['id']; ?>">
			
			<div class="center">
				<input type="submit" name="update" value="Update" class="btn brand z-depth-0">
			</div>
			
				
		</form>
	</section>
			
			<!-- DELETE FORM -->
			<form action="bandDetails.php" method="POST">
				<input type="hidden" name="id_to_change" value="<?php echo $bands['band_id']; ?>">
				<input type="submit" name="delete" value="Delete" class="btn brand z-depth-0">
			</form>

		<?php else: ?>
			<h5>No such user exists.</h5>
		<?php endif ?>
	</div>
	<?php include('templates/footer.php'); ?>

</html>
