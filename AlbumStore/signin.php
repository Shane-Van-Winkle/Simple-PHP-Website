<?php 

//this function can be used to detect if a username is here, 
//if it returns a user, that name is taken
//this can also be used for logging in a user.
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



	include('config/db_connect.php');
	
	$uname = $upass = '';
	$errors = array('uname' => '', 'upass' => '');

	if(isset($_POST['submit'])){
		
		// check for empty username
		if(empty($_POST['uname'])){
			$errors['uname'] = 'Please Enter a username';
		} else{
			$uname = $_POST['uname'];
		}

		// check for empty password
		if(empty($_POST['upass'])){
			$errors['upass'] = 'you must have a password';
		} else{
			$upass = $_POST['upass'];
		}
		

		if(array_filter($errors)){
			//echo 'errors in form';
		} else {
			// escape sql chars
			$uname = mysqli_real_escape_string($conn, $_POST['uname']);
			$upass = mysqli_real_escape_string($conn, $_POST['upass']);


		}
		
		$checkPwd = false;
		$usernameTaken = usernameTaken($conn, $uname);
		if($usernameTaken == false){
			$errors['uname'] = 'incorrect username or password';
		}
		else{
		
		$hashedPassword = $usernameTaken['u_pwd'];
		$checkPwd = password_verify($upass, $hashedPassword);
		}
		
		if($checkPwd == false){
			$errors['uname'] = 'incorrect username or password';
		}
		else{
		session_start();

		$_SESSION['name'] = $_POST['uname'];

		header('Location: index.php');
		}


	}
	
?>
  
<!DOCTYPE html>
<html>
	<?php include('templates/header.php'); ?>

	<section class="container grey-text">
		<h4 class="center">Sign in!</h4>
		<form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<div class="red-text"><?php echo $errors['uname']; ?></div>		
			<input type="text" name="uname" id="uname" value="<?php echo htmlspecialchars($uname) ?>">
			<label for="uname">Username</label>
			
			<div class="red-text"><?php echo $errors['upass']; ?></div>
			<input type="password", id="upass" name="upass" value="<?php echo htmlspecialchars($upass) ?>">
			<label for="upass">Password</label>

			<div class="center">
				<input type="submit" name="submit" value="Submit" class="btn brand z-depth-0">
			</div>
		</form>
	</section>

	<?php include('templates/footer.php'); ?>

</html>
  
</body>

