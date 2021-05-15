<?php 

/*TODO
 * 
 * Check the Email, Phone , and Addresses for duplicates.
 *  Refer to the usernameTaken function
 * Make sure the user is root when accesing this page.
 * 		send user back to index if not.
 */
?>


<body class="grey lighten-4">


<!DOCTYPE html>
<html>
<?php include('templates/header.php'); ?>
	
	<section class="col s6 m3 container center grey-text">
		<h4 class="center">User Stuff</h4>
		<form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<div class="center row">
			<a href="userEdit.php" class="btn brand z-depth-0" name="uname">Edit Users</a>
			</div>

		</form>
	</section>
	
	<!--
	<section class="col s6 m3 container center grey-text">
		<h4 class="center">Member Stuff</h4>
		<form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<div class="center row">
			<a href="authorAdd.php" class="btn brand z-depth-0" name="uname">Add Member</a>
			<a href="authorEdit.php" class="btn brand z-depth-0" name="uname">Edit Members</a>
			</div>
			<div class=" center row">
			<a href="authorLink.php" class="btn brand z-depth-0" name="uname">Link Members to a Band</a>
			</div>				
		</form>
	</section>
	-->
	
		<section class="col s6 m3 container center grey-text">
		<h4 class="center">Band Stuff</h4>
		<form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<div class="center row">
			<a href="bandAdd.php" class="btn brand z-depth-0" name="uname">Add Band</a>
			</div>
			<div class=" center row">
			<a href="bandEdit.php" class="btn brand z-depth-0" name="uname">Edit Bands</a>
			</div>
		</form>
	</section>
	
	<section class="col s6 m3 container center grey-text">
		<h4 class="center"> Album Stuff</h4>
		<form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<div class="center row">
			<a href="albumAdd.php" class="btn brand z-depth-0" name="uname">Add Album</a>
			<a href="albumEdit.php" class="btn brand z-depth-0" name="uname">Edit Albums</a>
			</div>
			<div class=" center row">
			<a href="genreAdd.php" class="btn brand z-depth-0" name="uname">Add Genre</a>
			<a href="genreLink.php" class="btn brand z-depth-0" name="uname">Link Genres</a>
			</div>			
		</form>
	</section>
	
	<section class="col s6 m3 container center grey-text">
		<h4 class="center">Order Stuff</h4>
		<form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<div class="center row">
			<a href="orderView.php" class="btn brand z-depth-0" name="uname">View Orders</a>
			</div>
		</form>
	</section>

	<section class="col s6 m3 container center grey-text">
		<h4 class="center">Distributor Stuff</h4>
		<form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<div class="center row">
			<a href="addDistributor2.php" class="btn brand z-depth-0" name="uname">Add Distributor</a>
			</div>
			<div class=" center row">
			<a href="distributorEdit.php" class="btn brand z-depth-0" name="uname">Edit Distributors</a>
			</div>
		</form>
	</section>
	
	<?php include('templates/footer.php'); ?>

</html>
  
</body>


