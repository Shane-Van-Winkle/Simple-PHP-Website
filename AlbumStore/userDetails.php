<?php 
//make this more secure

	include('config/db_connect.php');
	
	$sql = "SELECT u_username, u_email FROM users";
	
	// get the query result
        $result = mysqli_query($conn, $sql);

        // fetch result in array format
        $users = mysqli_fetch_assoc($result);

        mysqli_free_result($result);
		
function usernameTaken($conn, $username){
	$sqlQuery = "SELECT * FROM users WHERE u_username = ?;";
	$stmnt = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmnt, $sqlQuery)){
		return "mysql statement failed";
	}
	
	mysqli_stmt_bind_param($stmnt, "s", $username);
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

function emailTaken($conn, $email){
	$sqlQuery = "SELECT * FROM email WHERE Email = ?;";
	$stmnt = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmnt, $sqlQuery)){
		return "mysql statement failed";
	}
	
	mysqli_stmt_bind_param($stmnt, "s", $email);
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


		

	if(isset($_POST['delete'])){

		// no real user input so security is okay
		$id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_change']);

		$sql = "DELETE FROM users WHERE u_username = '$id_to_delete'";

		if(mysqli_query($conn, $sql)){
			header('Location: userEdit.php');
		} else {
			echo 'query error: '. mysqli_error($conn);
		}

	}
	
	
	$u_username = $u_pwd = $u_email = '';
	
	$errors = array('u_username' => '', 'u_pwd' => '','u_email' => '');
	
	//update to a new group
	if(isset($_POST['update'])){
		
		
		//$u_username = mysqli_real_escape_string($conn, $_POST['u_username']);
		//$u_pwd = mysqli_real_escape_string($conn, $_POST['u_pwd']);
		//$u_email = mysqli_real_escape_string($conn, $_POST['u_email']);
		
		
		
		// check username, We don't care what the username is
		if(empty($_POST['u_username'])){
			$errors['u_username'] = 'Please Enter a username';
		} else{
			$u_username = $_POST['u_username'];
		}
		
	
		if(usernameTaken($conn, $u_username) !== false){
			$errors['u_username'] = 'This username Exists';
		}
		
		
		// check email, we don't care
		if(empty($_POST['u_email'])){
			$errors['u_email'] = 'Please enter an email';
		} else{
			$u_email = $_POST['u_email'];
			if(!filter_var($u_email, FILTER_VALIDATE_EMAIL)){
				$errors['u_email'] = 'Please enter a valid email';
			}
		}	
		
		
		if(array_filter($errors)){
			echo 'errors in form';
			echo $errors['u_username'];
			echo $errors['u_email'];
	
				
	
			
			
		} else {
			// escape sql chars
			$u_username = mysqli_real_escape_string($conn, $_POST['u_username']);
		
			$u_email = mysqli_real_escape_string($conn, $_POST['u_email']);			
				
		
		$id_to_update = mysqli_real_escape_string($conn, $_POST['id_to_change']);

		$sql = "UPDATE users set u_username = ?, u_email = ? WHERE u_username = '$id_to_update'";
		
		$stmnt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmnt, $sql)){
			header("location: index.php?ERROR_UPDATE");
		}
	
		mysqli_stmt_bind_param($stmnt, "ss", $u_username, $u_email);
		mysqli_stmt_execute($stmnt);
		

		header('Location: userEdit.php');
		}
	}// end update	

	
		//global variables to store data
		$users = $email = '';


	// check GET request id param
	if(isset($_GET['id'])){
		
		// escape sql chars
		$id = mysqli_real_escape_string($conn, $_GET['id']);
		

		// make sql
		$sql_users = "SELECT * FROM users WHERE u_username = '$id'";

		
		// get the query result
		$result_users = mysqli_query($conn,$sql_users);


		// fetch result in array format
		$users = mysqli_fetch_assoc($result_users);
		
		mysqli_free_result($result_users);
		mysqli_close($conn);

	}

?>

<!DOCTYPE html>
<html>

	<?php include('templates/header.php'); ?>

	<div class="container center grey-text">
		<?php if($users): ?>
			<h4><?php echo $users['u_username']; ?></h4>
			<!--<p>ID: <?php echo $users['u_username']; ?></p>-->
			
	<!--UPDATE FORM-->		
	<section class="container grey-text">
		<h4 class="center">Edit User</h4>
		<form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			
			<!--
			<div class="red-text"><?php echo $errors['c_fname']; ?></div>
			<input type="text" name="c_fname" id="c_fname" value="<?php echo $customer['c_fname']; ?>">
			<label class="left" for="c_fname">Customer First Name</label>
			-->
			
			<!--
				
			<div class="red-text"><?php echo $errors['c_lname']; ?></div>		
			<input type="text" name="c_lname" id="c_lname" value="<?php echo $customer['c_lname']; ?>">
			<label class="left" for="c_lname">Customer Last Name</label>
			-->
			
			<div class="red-text"><?php echo $errors['u_username']; ?></div>		
			<input type="text" name="u_username" id="u_username" value="<?php echo $users['u_username']; ?>">
			<label class="left" for="u_username">Username</label>
			
			<!--
			<div class="red-text"><?php echo $errors['c_pwd']; ?></div>		
			<input type="text" name="c_pwd" id="c_pwd" value="<?php echo htmlspecialchars($c_pwd); ?>">
			<label class="left" for="c_pwd">Customer Password</label>
			-->
			
			<!--
			<div class="red-text"><?php echo $errors['Phone']; ?></div>		
			<input type="text" name="Phone" id="Phone" value="<?php echo $phone['Phone']; ?>">
			<label class="left" for="Phone">Customer Phone</label>
			-->
			
			<div class="red-text"><?php echo $errors['u_email']; ?></div>		
			<input type="text" name="u_email" id="u_email" value="<?php echo $users['u_email']; ?>">
			<label class="left" for="u_email">Email</label>
			
			<!--
			<div class="red-text"><?php echo $errors['Address']; ?></div>		
			<input type="text" name="Address" id="Address" value="<?php echo $address['Address']; ?>">
			<label class="left" for="Address">Customer Address</label>
			-->
			
			
			<input type="hidden" name="id_to_change" value="<?php echo $users['u_username']; ?>">
			<div class="center">
				<input type="submit" name="update" value="Update" class="btn brand z-depth-0">
			</div>
			
				
			</div>
		</form>
	</section>
			
			<!-- DELETE FORM -->
			<form action="userDetails.php" method="POST">
				<input type="hidden" name="id_to_change" value="<?php echo $users['u_username']; ?>">
				<input type="submit" name="delete" value="Delete" class="btn brand z-depth-0">
			</form>

		<?php else: ?>
			<h5>No such user exists.</h5>
		<?php endif ?>
	</div>
	<?php include('templates/footer.php'); ?>

</html>
