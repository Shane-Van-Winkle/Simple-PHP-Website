<?php 
//make this more secure

	include('config/db_connect.php');
	
	$sql = "SELECT o_order_ID FROM orders WHERE ";
	
	// get the query result
        $result = mysqli_query($conn, $sql);

        // fetch result in array format
        $customer = mysqli_fetch_assoc($result);

        mysqli_free_result($result);
		
		
		

	if(isset($_POST['delete'])){

		// no real user input so security is okay
		$id_to_delete = mysqli_real_escape_string($conn, $_POST['id_to_change']);

		$sql = "DELETE FROM customer WHERE Customer_ID = $id_to_delete";

		if(mysqli_query($conn, $sql)){
			header('Location: customerEdit.php');
		} else {
			echo 'query error: '. mysqli_error($conn);
		}

	}
	
		/*$sql = "SELECT 'phone'.'Phone', 'address'.'Address' , 'email'.'Email' 
		FROM customer, contact_details, phone, address, email

		WHERE 'customer'.'?' = 'contact_details'.'?'
			  'contact_details'.'?' = 'phone'.'?'
			  'contact_details'.'?' = 'address'.'Cont_ID'
			  'contact_details'.'?' = 'email'.'?'";
		*/
	
	$c_username = $c_fname = $c_lname = $c_pwd = $Email = $Address = $Phone = '';
	
	$errors = array('c_username' => '', 'c_fname' => '', 'c_lname' => '', 'c_pwd' => '',
	'Email' => '', 'Address' => '', 'Phone' => '');
	
	//update to a new group
	if(isset($_POST['update'])){
		
		
		$c_username = mysqli_real_escape_string($conn, $_POST['c_username']);
		$c_fname = mysqli_real_escape_string($conn, $_POST['c_fname']);
		$c_lname = mysqli_real_escape_string($conn, $_POST['c_lname']);
		//$c_pwd = mysqli_real_escape_string($conn, $_POST['c_pwd']);
		$Email = mysqli_real_escape_string($conn, $_POST['Email']);
		$Address = mysqli_real_escape_string($conn, $_POST['Address']);
		$Phone = mysqli_real_escape_string($conn, $_POST['Phone']);
		
		$id_to_update = mysqli_real_escape_string($conn, $_POST['id_to_change']);

		$sql = "UPDATE customer set c_username = ?, c_fname = ?, c_lname = ? where Customer_ID = $id_to_update";

			$stmnt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmnt, $sql)){
			header("location: index.php?ERROR_UPDATE");
		}
	
		mysqli_stmt_bind_param($stmnt, "sss", $c_username, $c_fname, $c_lname);
		mysqli_stmt_execute($stmnt);
		
		
		
	
		// phone update

		$sql_phone = "select phone.Cont_ID from customer, phone, contact_details where customer.Customer_ID = $id_to_update AND customer.c_cont_ID = contact_details.Cont_ID AND phone.Cont_ID = customer.c_cont_ID;";
		
		$result_phone= mysqli_query($conn, $sql_phone);
		
		$phone= mysqli_fetch_assoc($result_phone);
		$phone_ID = $phone['Cont_ID'];	//the phone contact id also is the same for address and email so we're all good
		
		
		$sql = "UPDATE phone set Phone = ? where Cont_ID = $phone_ID";
	
		$stmnt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmnt, $sql)){
			header("location: index.php?ERROR_UPDATE");
		}
	
		mysqli_stmt_bind_param($stmnt, "s", $Phone); 
		mysqli_stmt_execute($stmnt);		
	

		// email updates		
		$sql = "UPDATE email set Email = ? where Cont_ID = $phone_ID";
	
		$stmnt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmnt, $sql)){
			header("location: index.php?ERROR_UPDATE");
		}
	
		mysqli_stmt_bind_param($stmnt, "s", $Email); 
		mysqli_stmt_execute($stmnt);
		

		// address updates		
		$sql = "UPDATE address set Address = ? where Cont_ID = $phone_ID";
	
		$stmnt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmnt, $sql)){
			header("location: index.php?ERROR_UPDATE");
		}
	
		mysqli_stmt_bind_param($stmnt, "s", $Address); 
		mysqli_stmt_execute($stmnt);


		header('Location: Prophile.php');
	}// end update	

	
		//global variables to store data
		$customer = $email = $phone = $address = '';


	// check GET request id param
	if(isset($_GET['id'])){
		
		// escape sql chars
		$id = mysqli_real_escape_string($conn, $_GET['id']);
		
		$sql_order = "select orders.o_Order_ID from orders where orders.o_Order_ID = $id";
		
		$result_order= mysqli_query($conn, $sql_order);
		
		$phone= mysqli_fetch_assoc($result_order);
		$phone_ID = $phone['Cont_ID'];	//the phone contact id also is the same for address and email so we're all good

		// make sql
		$sql_customer = "SELECT * FROM customer WHERE Customer_ID = $id";
		$sql_address = "SELECT * FROM address WHERE Cont_ID = $phone_ID";
		$sql_phone = "SELECT * FROM phone WHERE Cont_ID = $phone_ID";
		$sql_email = "SELECT * FROM email WHERE Cont_ID = $phone_ID";
		
		// get the query result
		$result_customer = mysqli_query($conn, $sql_customer);
		$result_address = mysqli_query($conn, $sql_address);
		$result_phone = mysqli_query($conn, $sql_phone);
		$result_email = mysqli_query($conn, $sql_email);
		

		// fetch result in array format
		$customer = mysqli_fetch_assoc($result_customer);
		$email = mysqli_fetch_assoc($result_email);		
		$phone = mysqli_fetch_assoc($result_phone);		
		$address = mysqli_fetch_assoc($result_address);
		
		mysqli_free_result($result_customer);
		mysqli_free_result($result_address);
		mysqli_free_result($result_phone);
		mysqli_free_result($result_email);
		
		mysqli_close($conn);

	}

?>

<!DOCTYPE html>
<html>

	<?php include('templates/header.php'); ?>

	<div class="container center grey-text">
		<?php if($customer): ?>
			<h4><?php echo $customer['c_username']; ?></h4>
			<p>ID: <?php echo $customer['Customer_ID']; ?></p>
			
	<!--UPDATE FORM-->		
	<section class="container grey-text">
		<h4 class="center">Edit Customer</h4>
		<form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			
			<div class="red-text"><?php echo $errors['c_fname']; ?></div>
			<input type="text" name="c_fname" id="c_fname" value="<?php echo $customer['c_fname']; ?>">
			<label class="left" for="c_fname">Customer First Name</label>

				
			<div class="red-text"><?php echo $errors['c_lname']; ?></div>		
			<input type="text" name="c_lname" id="c_lname" value="<?php echo $customer['c_lname']; ?>">
			<label class="left" for="c_lname">Customer Last Name</label>
			
			
			<div class="red-text"><?php echo $errors['c_username']; ?></div>		
			<input type="text" name="c_username" id="c_username" value="<?php echo $customer['c_username']; ?>">
			<label class="left" for="c_username">Customer Username</label>
			
			<!--
			<div class="red-text"><?php echo $errors['c_pwd']; ?></div>		
			<input type="text" name="c_pwd" id="c_pwd" value="<?php echo htmlspecialchars($c_pwd); ?>">
			<label class="left" for="c_pwd">Customer Password</label>
			-->

			<div class="red-text"><?php echo $errors['Phone']; ?></div>		
			<input type="text" name="Phone" id="Phone" value="<?php echo $phone['Phone']; ?>">
			<label class="left" for="Phone">Customer Phone</label>
			
			
			<div class="red-text"><?php echo $errors['Email']; ?></div>		
			<input type="text" name="Email" id="Email" value="<?php echo $email['Email']; ?>">
			<label class="left" for="Email">Customer Email</label>
			
			
			<div class="red-text"><?php echo $errors['Address']; ?></div>		
			<input type="text" name="Address" id="Address" value="<?php echo $address['Address']; ?>">
			<label class="left" for="Address">Customer Address</label>
			
			<input type="hidden" name="id_to_change" value="<?php echo $customer['Customer_ID']; ?>">
			<div class="center">
				<input type="submit" name="update" value="Update" class="btn brand z-depth-0">
			</div>
			
				
			</div>
		</form>
	</section>
			
			<!-- DELETE FORM -->
			<form action="customerDetails.php" method="POST">
				<input type="hidden" name="id_to_change" value="<?php echo $customer['Customer_ID']; ?>">
				<input type="submit" name="delete" value="Delete" class="btn brand z-depth-0">
			</form>

		<?php else: ?>
			<h5>No such customer exists.</h5>
		<?php endif ?>
	</div>
	<?php include('templates/footer.php'); ?>

</html>
