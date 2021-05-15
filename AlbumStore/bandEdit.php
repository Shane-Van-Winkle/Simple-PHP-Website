<?php

	include('config/db_connect.php');

	// write query for all boats
	$sql = 'SELECT band_id, b_name FROM bands ORDER BY band_id';

	// get the result set (set of rows)
	$result = mysqli_query($conn, $sql);

	// fetch the resulting rows as an array
	$bands = mysqli_fetch_all($result, MYSQLI_ASSOC);


	mysqli_free_result($result);

	// close connection
	mysqli_close($conn);


?>

<!DOCTYPE html>
<html>
	
	<?php include('templates/header.php');?>

	<h4 class="center grey-text">band Listing</h4>

	<div class="container">
		<div class="row">

			<?php foreach($bands as $band): ?>

				<div class="col s6 m3">
					<div class="card z-depth-0">
						<img src="images/store.png"class=" center wheel" width="200" height="200">
						<div class="card-content center">
							<h6><?php echo htmlspecialchars($band['b_name']); ?></h6>
							<ul class="grey-text">
								<?php foreach(explode(',', $band['band_id']) as $ing): ?>
									<li><?php echo htmlspecialchars($ing); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
						<div class="card-action right-align">
							<a class="brand-text" href="bandDetails.php?id=<?php echo $band['band_id'] ?>">Manage</a>
						</div>
					</div><!--cards-->
				</div>

			<?php endforeach; ?>

		</div>
	</div>

	<?php include('templates/footer.php'); ?>

</html>
