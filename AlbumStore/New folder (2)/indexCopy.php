<?php 
//make this more secure
//TODO

//FINISH THE ORDERING SYSTEM
	include('config/db_connect.php');
	
	



?>



<!DOCTYPE html>
<html>

	<?php include('templates/header.php'); ?>

	<div class="container center grey-text">
		<h2> Welcome</h2>

	</div>
	<div class="container">

    </div>
	<section class="container grey-text">
		<form class="white">
			

	
			
			<select name="querySelect" class="browser-default">
						<option> Query Attribute</option>
						<option value="title">Title</option>
						<option value="author">Author</option>

					
						
			</select>
			
			
			<input type="text" name="textQuery" id="textQuery">
			<label class="left" for="Address">Query</label>
			
			<div class="center">
				<input type="submit" name="query" value="Query" class="btn brand z-depth-0">
			</div>			
		</form>
<table>
        <thead>
          <tr>
              <th>Book Title</th>
              <th>Book Author</th>
              <th>Book Price</th>
          </tr>
        </thead>
        <tbody>
        <?php  
        
	//get query info from url bar
if(isset($_GET['query'])){
		
		
	$queryString = mysqli_real_escape_string($conn, $_GET['textQuery']);
	$queryAttribute = mysqli_real_escape_string($conn, $_GET['querySelect']);
	
	if($queryAttribute == 'title'){
		$sql = "select `books`.`b_Title`, `books`.`b_price`, `author`.`a_fname`, `author`.`a_lname`,`order_items.`oi_item_number`
			from `books`, `author`, `author_writes_books`, `oi_item_number
			where
			`books`.`b_ISBN` = `author_writes_books`.`awb_Book_ISBN` AND
			`author_writes_books`.`awb_Auth_ID` = `author`.`Author_ID` AND
			`books`.`b_Title` LIKE '%$queryString%';";
		
		$sql = "select `books`.`b_Title`, `books`.`b_price`, `author`.`a_fname`, `author`.`a_lname`,`order_items`.`oi_item_number`
			from `books`, `author`, `author_writes_books`, `order_items`
			where
			`books`.`b_ISBN` = `author_writes_books`.`awb_Book_ISBN` AND
			`author_writes_books`.`awb_Auth_ID` = `author`.`Author_ID` AND
            `order_items`.`oi_book_ISBN` = `books`.`b_ISBN` AND
			`books`.`b_Title` LIKE '%$queryString%'";	
		
/*
 * select `books`.`b_Title`, `books`.`b_price`, `author`.`a_fname`, `author`.`a_lname`
			from `books`, `author`, `author_writes_books`
			where
			`books`.`b_ISBN` = `author_writes_books`.`awb_Book_ISBN` AND
			`author_writes_books`.`awb_Auth_ID` = `author`.`Author_ID` AND
			`books`.`b_Title` LIKE '%ver%'
 */ 
		
		
		// write query for all categories
		//$sql = 'SELECT bc_Category_Code, bc_Category_Description FROM book_Categories ORDER BY bc_Category_Description';

		// get the result set (set of rows)
		$result = mysqli_query($conn, $sql);

		// fetch the resulting rows as an array
		$books = mysqli_fetch_all($result, MYSQLI_ASSOC); // use a for each to get it
		
		foreach($books as $book){
		
			echo("<tr>");
			echo("<td>".$book['b_Title']."</td>");
			echo("<td>".$book['a_fname']. " ".$book['a_lname']."</td>");
			echo("<td>".$book['b_price']."</td>");
			
			if($name != 'Guest'){
			//	echo("<td>");
				//echo("<div class=\"center\">");
				//echo("<form action=".$_SERVER['PHP_SELF']." method=\"POST\">");
				//echo("< type=\"submit\" name=\"orderAdd\" value=\"Add to Order\" class=\"btn brand z-depth-0\">");
			//	echo("<a class=\"  brand z-depth-0\" href=\"addToOrder.php?id=".$book['oi_item_number'].">Manage</a>");
				//echo("<a class=\"brand-text\" href=\"addItemToOrder.php?id=". $book['oi_item_number'].">Manage</a>");
				//echo("</form>");
				//echo("</div>");
				//echo("</td>");
				

 				echo("<td>");
				echo("<div class=\"center\">");
				echo("<input type=\"submit\" name=\"update\" value=\"Add to Order\" class=\"btn brand z-depth-0\">");
				echo("</div>");
				echo("</td>");
 
  
  
  
 
				//echo('<td><button class="btn brand z-depth-0">CHOOSE ME</button></td>');
			//the book id will link to the ohi id
		}
			echo("</tr>");

		}
			
	}
}	
		 ?>
			
	
		
         
            
          </tr>
        </tbody>
      </table>		
	</section>
	
	
	<?php include('templates/footer.php'); ?>

</html>



