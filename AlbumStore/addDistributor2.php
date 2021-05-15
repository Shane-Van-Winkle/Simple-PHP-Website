<?php 

/*TODO
 *  Refer to the usernameTaken function 
 */
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
	
	// check connection
	if(!$conn){
		echo 'Connection error: '. mysqli_connect_error();
	}
	
	$Dist_name = '';
	$errors = array('Dist_name' => '');

	if(isset($_POST['insert'])){
		
		// check supName don't care what the username is
		if(empty($_POST['Dist_name'])){
			$errors['Dist_name'] = 'Please Enter a distributor name';
		}
		
		//change for Distributor name
		if(DistributorNameTaken($conn, $Dist_name) !== false){
			echo(DistributorNameTaken($conn, $Dist_name));
			$errors['Dist_name'] = 'This distributor Exists';
		}
				
		
		
		if(array_filter($errors)){
			//echo 'errors in form';
		} else {
			// escape sql chars
		
			$Dist_name = mysqli_real_escape_string($conn, $_POST['Dist_name']);
			
			
			
		
			$sql = "INSERT INTO distributors(Dist_name) VALUES(?);";
			
					
			$stmt = mysqli_stmt_init($conn);
			if(!mysqli_stmt_prepare($stmt, $sql)){
				header("location: index.php?ERRORDIST");
				exit();
			}
			mysqli_stmt_bind_param($stmt, "s",$Dist_name);
			
			
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
				
			
			header('Location: index.php');


		}

	
	} // end POST check
?>
<!DOCTYPE html>
<html>
<?php include('templates/header.php'); ?>
	
	<section class="container grey-text">
		<h4 class="center">Add a Distributor!</h4>
		<form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		
			<div class="red-text"><?php echo $errors['Dist_name']; ?></div>		
			<input type="text" name="Dist_name" id="Dist_name" value="<?php echo htmlspecialchars($Dist_name) ?>">
			<label for="Dist_name">Distributor name</label>

			<div class="center">
				<input type="submit" name="insert" value="Submit" class="btn brand z-depth-0">
			</div>
		</form>
	</section>
	<?php include('templates/footer.php'); ?>

</html>
  
</body>

