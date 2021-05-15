<?php

	include('config/db_connect.php');

	// write query for all boats
	$sql = 'SELECT u_username, u_email FROM users ORDER BY u_username';

	// get the result set (set of rows)
	$result = mysqli_query($conn, $sql);

	// fetch the resulting rows as an array
	$users = mysqli_fetch_all($result, MYSQLI_ASSOC);


	mysqli_free_result($result);

	// close connection
	mysqli_close($conn);


?>

<!DOCTYPE html>
<html>
	
	<?php include('templates/header.php');?>

	<h4 class="center grey-text">User Listing</h4>

	<div class="container">
		<div class="row">

			<?php foreach($users as $user): ?>

				<div class="col s6 m3">
					<div class="card z-depth-0">
						<img src="images/store.png"class=" center wheel" width="200" height="200">
						<div class="card-content center">
							<h6><?php echo htmlspecialchars($user['u_username']); ?></h6>
							<ul class="grey-text">
									<li><?php echo htmlspecialchars($user['u_email']); ?></li>
							</ul>
						</div>
						<div class="card-action right-align">
							<a class="brand-text" href="userDetails.php?id=<?php echo $user['u_username'] ?>">Manage</a>
						</div>
					</div><!--cards-->
				</div>

			<?php endforeach; ?>

		</div>
	</div>

	<?php include('templates/footer.php'); ?>

</html>
