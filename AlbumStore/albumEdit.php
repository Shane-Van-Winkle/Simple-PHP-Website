<?php

	include('config/db_connect.php');

	// write query for all boats
	$sql = 'SELECT Album_ID, a_name FROM albums ORDER BY Album_ID';

	// get the result set (set of rows)
	$result = mysqli_query($conn, $sql);

	// fetch the resulting rows as an array
	$albums = mysqli_fetch_all($result, MYSQLI_ASSOC);


	mysqli_free_result($result);

	// close connection
	mysqli_close($conn);


?>

<!DOCTYPE html>
<html>
	
	<?php include('templates/header.php');?>

	<h4 class="center grey-text">Albums Listing</h4>

	<div class="container">
		<div class="row">

			<?php foreach($albums as $album): ?>

				<div class="col s6 m3">
					<div class="card z-depth-0">
						<img src="images/store.png"class=" center wheel" width="200" height="200">
						<div class="card-content center">
							<h6><?php echo htmlspecialchars($album['a_name']); ?></h6>
							<ul class="grey-text">
								<?php foreach(explode(',', $album['Album_ID']) as $ing): ?>
									<li><?php echo htmlspecialchars($ing); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
						<div class="card-action right-align">
							<a class="brand-text" href="albumDetails.php?id=<?php echo $album['Album_ID'] ?>">Manage</a>
						</div>
					</div><!--cards-->
				</div>

			<?php endforeach; ?>

		</div>
	</div>

	<?php include('templates/footer.php'); ?>

</html>
