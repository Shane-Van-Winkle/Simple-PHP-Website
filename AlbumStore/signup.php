<?php 

/*TODO
 * 
 * Check the Email, Phone , and Addresses for duplicates.
 *  Refer to the usernameTaken function 
 * Allow for the user to add multiple emails, phones, and addresses
 * make sure to pick the right address/phone/email at checkout
 */

function random_str(int $length = 20, string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_'): string {
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
		$pieces []= $keyspace[random_int(0, $max)];
    }
	return implode('', $pieces);
}
$ukey = random_str();

function passwordMatchCheck($pass1, $pass2){
	
	
}

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

include('config/db_connect.php');
	
	// check connection
	if(!$conn){
		echo 'Connection error: '. mysqli_connect_error();
	}
	
	$uname = $upass = $upassRepeat = $uemail = '';
	$errors = array('uname' => '', 'upass' => '', 'upass2' => '', 'uemail' => '');

	if(isset($_POST['insert'])){
		
		// check username, We don't care what the username is
		if(empty($_POST['uname'])){
			$errors['uname'] = 'Please Enter a username';
		} else{
			$uname = $_POST['uname'];
		}
		
	
		if(usernameTaken($conn, $uname) !== false){
			$errors['uname'] = 'This username Exists';
		}
			
		

		// check password
		if(empty($_POST['upass'])){
			$errors['upass'] = 'you must have a password';
		} 
		else{
			$upass = $_POST['upass'];
			if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*;()<>?])[a-zA-Z0-9!@#$%^&*;:()<>?]{8,}+$/' , $upass)){
				$errors['upass'] = 'bad password input';
			}
		}
	
		// check email, we don't care
		if(empty($_POST['uemail'])){
			$errors['uemail'] = 'Please Enter an email';
		} else{
			$uemail = $_POST['uemail'];
			if(!filter_var($uemail, FILTER_VALIDATE_EMAIL)){
				$errors['uemail'] = 'email input bad';
			}
		}
		
		if(array_filter($errors)){
			//echo 'errors in form';
		} else {
			// escape sql chars
			$uname = mysqli_real_escape_string($conn, $_POST['uname']);
			$upass = mysqli_real_escape_string($conn, $_POST['upass']);
		
			$uemail = mysqli_real_escape_string($conn, $_POST['uemail']);
			// create sql
			//$sql = "CREATE USER '$uname'@'localhost' IDENTIFIED BY '$upass'";
			
			//increment the contactID
			//$result->fetch_array()['columnName'] ?? '';
			
			#password hashing
			$hashedPassword = password_hash($upass, PASSWORD_DEFAULT);
			
			//begin inserting the user
			// start the addresses
			
			
			
			$sql = "INSERT INTO users(u_username, u_pwd, u_email) VALUES(?, ?, ?);";
			
			
					
			$stmt = mysqli_stmt_init($conn);
			if(!mysqli_stmt_prepare($stmt, $sql)){
				header("location: index.php?ERRORUSER");
				exit();
			}
			mysqli_stmt_bind_param($stmt, "sss", $uname, $hashedPassword, $uemail);
			
			
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
				session_start();
				
				$_SESSION['name'] = $_POST['uname'];
				header('Location: index.php');


		}

	
	} // end POST check
?>
<!DOCTYPE html>
<html>
<?php include('templates/header.php'); ?>
	
	<section class="container grey-text">
		<h4 class="center">Sign up!</h4>
		<form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		
			<div class="red-text"><?php echo $errors['uname']; ?></div>		
			<input type="text" name="uname" id="uname" value="<?php echo htmlspecialchars($uname) ?>">
			<label for="uname">Username</label>
			
			<div class="red-text"><?php echo $errors['upass']; ?></div>
			<input type="password", id="upass" name="upass" value="<?php echo htmlspecialchars($upass) ?>">
			<label for="upass">Password</label>

			<div class="red-text"><?php echo $errors['uemail']; ?></div>	
			<input type="text" name="uemail" id="uemail" value="<?php echo htmlspecialchars($uemail) ?>">
			<label for="uemail">Email</label>

			<div class="center">
				<input type="submit" name="insert" value="Submit" class="btn brand z-depth-0">
			</div>
		</form>
	</section>
	<?php include('templates/footer.php'); ?>

</html>
  
</body>

