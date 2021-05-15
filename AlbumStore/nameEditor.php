<?php 

	if(isset($_POST['submit'])){

		session_start();

		$_SESSION['name'] = $_POST['name'];

		header('Location: index.php');
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>php fun</title>
</head>
<body>

	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<input type="text" name="name">
		<input type="submit" name="submit" value="submit">
	</form>

</body>
</html>

	 <label for="cars">Choose a car:</label>
<form>
<select name="cars" id="cars">
  <option value="volvo">Volvo</option>
  <option value="saab">Saab</option>
  <option value="mercedes">Mercedes</option>
  <option value="audi">Audi</option>
</select> 
</form>
