<?php
include('config/db_connect.php');
// check GET request id param
	
	//the order is made so we only add an item to that order
	if(isset($_GET['orderID'])){
	// escape sql chars
	 ($_GET['orderID']);
		$itemID = mysqli_real_escape_string($conn, $_GET['itemID']);// order item to add ID
		$username = mysqli_real_escape_string($conn, $_GET['username']);
		//order was created already so we've got this.
		$orderID = $_GET['orderID'];
		
	
		//create the order item and place the item in the order
		
		$orderItemsql = "insert into order_has_order_items(ohi_item_number, ohi_Order_ID) values (?, ?)";
		
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $orderInitSQL)){
			header("location: index.php?ERRORnewOrder");
			exit();
		}
		mysqli_stmt_bind_param($stmt, "ss",$itemID, $orderID);
		
		
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		
		echo("ORDER ID IS THERE");
		//header("Location: index.php?querySelect=QueryAttribute&textQuery=&query=Query&orderID=$orderID");
	
	}	
	
	// if the order hasn't been made yet, initalize the order and add an item to the order
	else if(isset($_GET['itemID'])){
		// escape sql chars
		$id = mysqli_real_escape_string($conn, $_GET['itemID']);// order item to add ID
		$uname = mysqli_real_escape_string($conn, $_GET['username']);
		
		$today = date("d-m-Y");
		
		
		// initialize the order
		$orderInitSQL = "insert into orders(o_Order_date, o_Order_value, o_username) values(?, 0.0, ?);";
	
		$stmt = mysqli_stmt_init($conn);
		if(!mysqli_stmt_prepare($stmt, $orderInitSQL)){
			header("location: index.php?ERRORnewOrder");
			exit();
		}
		mysqli_stmt_bind_param($stmt, "ss",$today, $uname);
		
		
		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);
		
		$currentOrderIDsql = "select `orders`.`o_Order_ID` from `orders`, `users` where `users`.`u_username` = \"$uname\"";
		
		$result_orderID = mysqli_query($conn, $currentOrderIDsql);
		
		$orderID_arr = mysqli_fetch_assoc($result_orderID);
		
		$orderID = $orderID_arr['o_Order_ID'];
		
		mysqli_free_result($result_orderID);
		
		//create the order item and place the item in the order
		
		$orderItemsql = "insert into order_has_order_items(ohi_item_number, ohi_Order_ID) values (\"$id\", \"$orderID\")";
	
		 mysqli_query($conn, $orderItemsql);
		
		echo("ItemID is there");
	
		header("Location: index.php?orderID=$orderID&name=$uname");
	}
	
	

	



//header("Location: index.php?$usersID");
?>
