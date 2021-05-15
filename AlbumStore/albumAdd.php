<?php 

/*TODO
 * Make sure that the supplierID is selected through a combo box tomorrow
 */
 
include('config/db_connect.php');


	// write query for all distributors
	$sql = 'SELECT Dist_ID, Dist_name FROM distributors ORDER BY Dist_ID';

	// get the result set (set of rows)
	$result = mysqli_query($conn, $sql);

	// fetch the resulting rows as an array
	$distributors = mysqli_fetch_all($result, MYSQLI_ASSOC);


	mysqli_free_result($result);

	// write query for all bands
	$sql = 'SELECT band_id, b_name FROM bands ORDER BY band_id';

	// get the result set (set of rows)
	$result = mysqli_query($conn, $sql);

	// fetch the resulting rows as an array
	$bands = mysqli_fetch_all($result, MYSQLI_ASSOC);


	mysqli_free_result($result);

	//check connection
	if(!$conn){
		echo 'Connection error: '. mysqli_connect_error();
	}
	
	$Album_ID = $a_name = $a_price = $a_release_date = $a_band_id  = $a_dist_ID = ''  ;
	$errors = array('Album_ID' => '', 'a_name' => '', 'a_price' => '', 'a_release_date' => '', 'a_dist_ID' => '');

	if(isset($_POST['insert'])){
		
		/*
		//Album ID
		if(empty($_POST['Album_ID'])){
			$errors['Album_ID'] = 'Please Enter an I';
		} else{
			$Album_ID = $_POST['Album_ID'];
		}
		*/

		if(empty($_POST['a_name'])){
			$errors['a_name'] = 'Please Enter a name';
		}
		
		// check publication date
		if(empty($_POST['a_release_date'])){
			$errors['a_release_date'] = 'Please Enter a release date';
		}
		
		// check price
		if(empty($_POST['a_price'])){
			$errors['a_price'] = 'Please Enter a price';
		} 
		
		
		$a_price = mysqli_real_escape_string($conn, $_POST['a_price']);
		$a_price = number_format((float) $a_price, 2, '.', '');
			
		if(!filter_var($a_price, FILTER_VALIDATE_FLOAT))
		{
			$errors['a_price'] = 'Price must be a floating point number';
		}		


		if(array_filter($errors)){
		//	echo 'errors in form';
			//	foreach($errors as $error){
		//	echo($error);
		
		} else {
			// escape sql chars
			//$Album_ID = mysqli_real_escape_string($conn, $_POST['Album_ID']);
			$a_name = mysqli_real_escape_string($conn, $_POST['a_name']);
			$a_release_date = mysqli_real_escape_string($conn, $_POST['a_release_date']);
			//$a_price = mysqli_real_escape_string($conn, $_POST['a_price']);
			$a_dist_ID = mysqli_real_escape_string($conn, $_POST['Dist_ID']);
		
			$a_band_id = mysqli_real_escape_string($conn, $_POST['band_id']);
			$sql = "INSERT INTO albums(a_name, a_price, a_release_date,  a_dist_ID, a_band_id) VALUES(?, ?, ?, ?, ?);";
			// INSERT INTO albums(a_name, a_price, a_release_date,  a_dist_ID) VALUES('American Football', 12.99, 'September 14, 1999', 1);
							
			$stmt = mysqli_stmt_init($conn);
			
			if(!mysqli_stmt_prepare($stmt, $sql)){
				header("location: index.php?albumAddError");
				exit();
			}
			//mysqli_stmt_bind_param($stmt, "sssss", $supRepName, $rep_work_number, $rep_cell_number, $rep_email, $rep_sup_id);
			mysqli_stmt_bind_param($stmt, "sssss", $a_name, $a_price, $a_release_date, $a_dist_ID, $a_band_id);
			//echo($stmt);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
			
			
			//Now we have to find the album's id to add to the order items.
			//We might be overthingking this
			// no need for security since this is a search.
			
			
			
			$sql_orderAlbum = "SELECT Album_ID, a_name
			FROM albums
			WHERE a_name LIKE '$a_name';";

			// get the result set (set of rows)
			$result = mysqli_query($conn, $sql_orderAlbum);

			// fetch the resulting rows as an array
			$orderAlbumID = mysqli_fetch_row($result);
			

			mysqli_free_result($result);
			/*
			
			$sql_orderAlbum = "SELECT Album_ID 
			FROM albums
			WHERE a_name LIKE ?;";
			
			$stmt_orderItem = mysqli_stmt_init($conn);
			
			if(!mysqli_stmt_prepare($stmt_orderItem, $sql_orderAlbum)){
				header("location: index.php?albumIDError");
				exit();
			}
			
			mysqli_stmt_bind_param($stmt_orderItem, "s", $a_name);
			//echo($stmt);
			mysqli_stmt_execute($stmt_orderItem);
			$result = mysqli_stmt_get_result($stmt);
			$orderAlbumID = "test"; // mysqli_fetch_array($result, MYSQLI_NUM);
			
			while($row = mysqli_fetch_array($result, MYSQLI_NUM))
			{
				foreach($row as $r)
				{
					$orderAlbumID= $r;
					
				}
			}
			
			mysqli_stmt_close($stmt);
			
			*/
		
			$sql_orderItem = "INSERT into order_items(oi_item_price, oi_album_ID) values ( ?, ?);";
			
			$stmt_orderItem = mysqli_stmt_init($conn);
			if(!mysqli_stmt_prepare($stmt_orderItem, $sql_orderItem)){
				header("location: index.php?ERROR2");
				exit();
			}
			
			mysqli_stmt_bind_param($stmt_orderItem, "ss", $a_price, $orderAlbumID[0]);
			
			mysqli_stmt_execute($stmt_orderItem);
			mysqli_stmt_close($stmt_orderItem);
			
			//$print = implode(", ", $orderAlbumID);
			$print = $orderAlbumID[0];
			header("Location: rootlyPowers.php?ff=$print");

		}

	
	} // end POST check
?>
<!DOCTYPE html>
<html>
<?php include('templates/header.php'); ?>
	
	<section class="container grey-text">
		<h4 class="center">Add an Album</h4>
		<form class="white" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<!--
			<div class="red-text"><?php echo $errors['Album_ID']; ?></div>		
			<input type="text" name="Album_ID" id="Album_ID" value="<?php echo htmlspecialchars($Album_ID) ?>">
			<label for="Album_ID">Book ISBN</label>
		-->
			<div class="red-text"><?php echo $errors['a_name']; ?></div>		
			<input type="text" name="a_name" id="a_name" value="<?php echo htmlspecialchars($a_name) ?>">
			<label for="a_name">Album Name</label>
			
			<div class="red-text"><?php echo $errors['a_release_date']; ?></div>		
			<input type="text" name="a_release_date" id="a_release_date" value="<?php echo htmlspecialchars($a_release_date) ?>">
			<label for="a_release_date">Album Release Date</label>
			
			<div class="red-text"><?php echo $errors['a_price']; ?></div>		
			<input type="text" name="a_price" id="a_price" value="<?php echo htmlspecialchars($a_price) ?>">
			<label for="a_price">Album Price</label>
			
			
			
				<div class="container">
				<div class="row">
					<select name="Dist_ID" class="browser-default">
						<option> Distributors</option>
							<?php foreach($distributors as $distributor): ?>
								<option value="<?php echo $distributor['Dist_ID']?>"><?php echo $distributor['Dist_name'] ?></option>

							<?php endforeach; ?>
					
						
					</select>
				</div>
			</div>
			
			
				<div class="container">
				<div class="row">
					<select name="band_id" class="browser-default">
						<option> Bands</option>
							<?php foreach($bands as $band): ?>
								<option value="<?php echo $band['band_id']?>"><?php echo $band['b_name'] ?></option>

							<?php endforeach; ?>
					
						
					</select>
				</div>
			</div>			
			<div class="center">
				<input type="submit" name="insert" value="Submit" class="btn brand z-depth-0">
			</div>
			
		</form>
	</section>
	<?php include('templates/footer.php'); ?>

</html>
  
</body>

