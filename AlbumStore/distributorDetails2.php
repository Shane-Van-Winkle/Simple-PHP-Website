<?php 
//make this more secure
function DistributorNameTaken($conn, $name){
	$sqlQuery = "SELECT * FROM distributors WHERE Dist_name = ?;";
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
	
	$sql = "SELECT Dist_ID, Dist_name FROM distributors WHERE Dist_ID ";
	
	// get the query result
        $result = mysqli_query($conn, $sql);

        // fetch result in array format
        $distributors = mysqli_fetch_assoc($result);

        mysqli_free_result($result);
		
		

	if(isset($_GET['id']))
	{
		$_SESSION['id'] = $_GET['id'];
	}
		

	if(isset($_POST['delete'])){

		// no real user input so security is okay
		$id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_change']);
		
		$sql = "UPDATE Albums SET a_dist_ID = null WHERE a_dist_ID = '$id_to_delete'";
		if(mysqli_query($conn, $sql)){
			//header('Location: distributorEdit.php');
		} else {
			echo 'query error: '. mysqli_error($conn);
		}
		
		
		$sql = "DELETE FROM distributors WHERE Dist_ID = '$id_to_delete'";

		if(mysqli_query($conn, $sql)){
			header('Location: distributorEdit.php');
		} else {
			echo 'query error: '. mysqli_error($conn);
		}

	}
	
	$Dist_name = '';
	$errors = array('Dist_name' => '');
	//update to a new group
	if(isset($_POST['update'])){
		
		// check Dist_name don't care what the username is
		if(empty($_POST['Dist_name'])){
			$errors['Dist_name'] = 'Please Enter a distributor name';
		} else{
			$Dist_name = $_POST['Dist_name'];
		}
		
		//change for Distributor name
		if(DistributorNameTaken($conn, $Dist_name) !== false){
			$errors['Dist_name'] = 'This distributor Exists';
		}
		
		if(array_filter($errors)){
			//echo 'errors in form';
			//echo $errors['Dist_name'];
		
			
			
		} 
	
				
		
		if(array_filter($errors)){
			
			//echo 'errors in form';
			//echo $errors['Dist_name'];	
			
		} 
		else 
		{
			
			//echo("I am here too for some reason");
			// escape sql chars
			//$Student_ID = mysqli_real_escape_string($conn, $_POST['Student_ID']);
		
			$Dist_name = mysqli_real_escape_string($conn, $_POST['Dist_name']);
			
			$id_to_update = mysqli_real_escape_string($conn, $_POST['id_to_change']);

			$sql = "UPDATE distributors set Dist_name = ? where Dist_ID = $id_to_update";
		
			
			$stmnt = mysqli_stmt_init($conn);
			if(!mysqli_stmt_prepare($stmnt, $sql)){
				header("location: index.php?ERROR_UPDATE");
			}


			mysqli_stmt_bind_param($stmnt, "s", $Dist_name);
			mysqli_stmt_execute($stmnt);

			header('Location: distributorEdit.php');
		}
	}// end update	

		//the session variable has what we need now

		$sid = $_SESSION['id'];
		$sql = "SELECT * FROM distributors WHERE Dist_ID = $sid";

		
		// get the query result
		// get the query result
		$result = mysqli_query($conn, $sql);

		// fetch result in array format
		$distributors = mysqli_fetch_assoc($result);

		
		mysqli_free_result($result);
		mysqli_close($conn);
		

		
	

?>

<!DOCTYPE html>
<html>

	

	<div class="container center grey-text">
		<?php if($distributors): ?>
			<h4><?php echo $distributors['Dist_name']; ?></h4>
			<p>ID: <?php echo $distributors['Dist_ID']; ?></p>
			
	<!--UPDATE FORM-->		
	<section class="container grey-text">
		<h4 class="center">Edit Distributor</h4>
		<form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		
			

			<div class="red-text"><?php echo $errors['Dist_name']; ?></div>		
			<input type="text" name="Dist_name" id="Dist_name" value="<?php echo htmlspecialchars($distributors['Dist_name']) ?>">
			<label class="left" for="Dist_name">Distributor Name</label>
			
	
	
			<input type="hidden" name="id_to_change" value="<?php echo $_SESSION['id']; ?>">
			
			<div class="center">
				<input type="submit" name="update" value="Update" class="btn brand z-depth-0">
			</div>
			
				
		</form>
	</section>
			
			<!-- DELETE FORM -->
			<form action="distributorDetails2.php" method="POST">
				<input type="hidden" name="id_to_change" value="<?php echo $distributors['Dist_ID']; ?>">
				<input type="submit" name="delete" value="Delete" class="btn brand z-depth-0">
			</form>

		<?php else: ?>
			<h5>No such user exists.</h5>
		<?php endif ?>
	</div>
	<?php include('templates/footer.php'); ?>

</html>
