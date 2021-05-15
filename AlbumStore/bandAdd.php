<?php 

/*TODO
 *  Refer to the usernameTaken function 
 */
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
	
	// check connection
	if(!$conn){
		echo 'Connection error: '. mysqli_connect_error();
	}
	
	$b_name = '';
	$errors = array('b_name' => '');

	if(isset($_POST['insert'])){
		
		// check supName don't care what the username is
		if(empty($_POST['b_name'])){
			$errors['b_name'] = 'Please Enter a band name';
		}
		
		//change for band name
		if(bandNameTaken($conn, $b_name) !== false){
			echo(bandNameTaken($conn, $b_name));
			$errors['b_name'] = 'This band Exists';
		}
				
		
		
		if(array_filter($errors)){
			//echo 'errors in form';
		} else {
			// escape sql chars
		
			$b_name = mysqli_real_escape_string($conn, $_POST['b_name']);
			
			
			
		
			$sql = "INSERT INTO bands(b_name) VALUES(?);";
			
					
			$stmt = mysqli_stmt_init($conn);
			if(!mysqli_stmt_prepare($stmt, $sql)){
				header("location: index.php?ERRORbandAdd");
				exit();
			}
			mysqli_stmt_bind_param($stmt, "s",$b_name);
			
			
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
		<h4 class="center">Add a Band!</h4>
		<form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		
			<div class="red-text"><?php echo $errors['b_name']; ?></div>		
			<input type="text" name="b_name" id="b_name" value="<?php echo htmlspecialchars($b_name) ?>">
			<label for="b_name">Band name</label>

			<div class="center">
				<input type="submit" name="insert" value="Submit" class="btn brand z-depth-0">
			</div>
		</form>
	</section>
	<?php include('templates/footer.php'); ?>

</html>
  
</body>

