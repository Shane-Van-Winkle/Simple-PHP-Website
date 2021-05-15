<?php 
//make this more secure
	//check for admin name in rootly powers
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
		<form class="white"> <!-- default method sends info to address bar 
								for GET-ing-->
					
			<select name="querySelect" class="browser-default">
						<option> Query Attribute</option>
						<option value="a_name">Album Name</option>
						<option value="b_name">Band</option>
						<option value="genre">Genre</option>
						<option value="Dist_name">Distributor</option>	
			</select>
			
			
			<input type="text" name="textQuery" id="textQuery">
			
			
			<div class="center">
				<input type="submit" name="query" value="Query" class="btn brand z-depth-0"> <!-- this is a GET request -->
			</div>		
			<?php
			if(isset($_GET['orderID']))
			{
				//echo("<label class=\"left\" for=\"Address\">".$_GET['orderID']."</label>");
				echo("<input type=\"hidden\" name=\"orderID\" value=".$_GET['orderID'].">");
			}
			?>	
		</form>
<table>
        <thead>
          <tr>
              <th>Album Name</th>
              <th>Band </th>
              <th>Genre</th>
              <th>Distributor</th>
              <th>Price</th>
          </tr>
        </thead>
        <tbody>
        <?php  
        
//the order is made, just focus on adding an to the order
if(isset($_GET['orderID'])){

		
		
		echo("INDEX HAS ORDER ID");
		if(isset($_GET['query'])){
			echo("INDEX ALSO HAS QUERY");
		}
		$result = mysqli_query($conn, $sql);

		// fetch the resulting rows as an array
		$albums = mysqli_fetch_all($result, MYSQLI_ASSOC); // use a for each to get it
		
		foreach($albums as $album){
		
			echo("<tr>");
			echo("<td>".$album['a_name']."</td>");
			echo("<td>".$album['a_']. " ".$album['a_lname']."</td>");
			echo("<td>".$album['b_price']."</td>");
			
			if($name != 'Guest'){

				

 				echo("<td>");
				echo("<div class=\"center\">");
				echo ("<a href=\"addItemToOrder.php?itemID=".$album['oi_item_number']."&username=".$name."\""."&orderID=".$_GET['orderID']."class=\"btn brand z-depth-0\">Add to Order</a>");
				echo("</div>");
				echo("</td>");
		}
			echo("</tr>");

		}
			
	}

// order is not created for the first time yet, create the order and 
//place a album inside
else if(isset($_GET['query'])){
		
	echo("INDEX HAS QUERY");
		
	if(array_key_exists('textQuery', $_GET))
	{
		$queryString = mysqli_real_escape_string($conn, $_GET['textQuery']);
	}

	if(array_key_exists('querySelect', $_GET))
	{
		$queryAttribute = mysqli_real_escape_string($conn, $_GET['querySelect']);
	}	
	
	
	if($queryAttribute != "genre")
	{
		$sql = "select `albums`.`Album_ID`, `albums`.`a_name`, `albums`.`a_price`, `bands`.`b_name`, `distributors`.`Dist_name`,`order_items`.`oi_item_number`
			from `albums`,  `order_items`, `distributors`, `bands`
			where `albums`.`a_band_id` = `bands`.`band_id` AND
            `albums`.`a_dist_ID` = `distributors`.`Dist_ID` AND
            `order_items`.`oi_album_ID` = `albums`.`Album_ID`AND
            $queryAttribute LIKE '%$queryString%'";
		
	
		// get the result set (set of rows)
		$result = mysqli_query($conn, $sql);

		// fetch the resulting rows as an array
		$albums = mysqli_fetch_all($result, MYSQLI_ASSOC); // use a for each to get it
		
		foreach($albums as $album){
		
			echo("<tr>");
			echo("<td>".$album['a_name']."</td>");
			echo("<td>".$album['b_name']."</td>");
			#echo("<td>".$album['a_fname']. " ".$album['a_lname']."</td>");
			$idGet = $album['Album_ID'];
						//do the code that turns multiple things into one here
			// for the genres something include();
		$sql_genres = "SELECT `genres`.`genre_description`
							from `albums`, `genres`, `albums_assigned_to_genres`
							where `albums_assigned_to_genres`.`aag_Album_ID` = $idGet AND
							`albums_assigned_to_genres`.`aag_Genre_Code` = `genres`.`genre_code`";

		$result = mysqli_query($conn, $sql_genres);

		// fetch the resulting rows as an array
		$genre_strings = []; // empty starting array
		$genre_Descs = mysqli_fetch_all($result, MYSQLI_ASSOC); // use a for each to get it	
		foreach($genre_Descs as $desc)
		{
			foreach($desc as $row)
			{
				if(!in_array($row, $genre_strings))//duplicate records can exist
				{
					array_push($genre_strings, $row);
				}
			}
		}	
		$genre_print_string = implode(", <br>", $genre_strings);
		
		//print_r("$genre_print_string");
		
		echo("<td>".$genre_print_string."</td>");
			
			
			//echo("<td>"."CHANGE ME"."</td>");
			
			echo("<td>".$album['Dist_name']."</td>");
					
			
			echo("<td>".$album['a_price']."</td>");
			
			if($name != 'Guest'){

 				echo("<td>");
				echo("<div class=\"center\">");
				echo ("<a href=\"addItemToOrder.php?itemID=".$album['oi_item_number']."&username=".$name."\""."class=\"btn brand z-depth-0\">Add to Order</a>");
				
				echo("</div>");
				echo("</td>");
 
				
			//the album id will link to the ohi id
			}// end if
			echo("</tr>");
		}// end foreach
	}// end if
	
	else {// a whole new set of queries just for genre searching.
		// grab the albums that match the genre description, e.g. "roll" from "Rock and Roll"
		$sql = "select `albums`.`Album_ID`, `albums`.`a_name`, `albums`.`a_price`, `bands`.`b_name`, `distributors`.`Dist_name`,`order_items`.`oi_item_number`
			from `albums`,  `order_items`, `distributors`, `bands`, `albums_assigned_to_genres`, `genres`
			where `albums`.`a_band_id` = `bands`.`band_id` AND
            `albums`.`a_dist_ID` = `distributors`.`Dist_ID` AND
            `order_items`.`oi_album_ID` = `albums`.`Album_ID` AND
			`albums_assigned_to_genres`.`aag_Genre_Code` = `genres`.`genre_code` AND
            `albums_assigned_to_genres`.`aag_Album_ID` = `albums`.`Album_ID` AND
			`genres`.`genre_description` LIKE '%$queryString%'";
			
		// get the result set (set of rows)
		$result = mysqli_query($conn, $sql);

		// fetch the resulting rows as an array
		$albums = mysqli_fetch_all($result, MYSQLI_ASSOC); // use a for each to get it
		
		foreach($albums as $album){
		
			echo("<tr>");
			echo("<td>".$album['a_name']."</td>");
			echo("<td>".$album['b_name']."</td>");
			#echo("<td>".$album['a_fname']. " ".$album['a_lname']."</td>");
			$idGet = $album['Album_ID'];
						//do the code that turns multiple things into one here
			// for the genres something include();
		$sql_genres = "SELECT `genres`.`genre_description`
							from `albums`, `genres`, `albums_assigned_to_genres`
							where `albums_assigned_to_genres`.`aag_Album_ID` = $idGet AND
							`albums_assigned_to_genres`.`aag_Genre_Code` = `genres`.`genre_code`";


		// get the result set (set of rows)
		$result = mysqli_query($conn, $sql_genres);

		// fetch the resulting rows as an array
		$genre_strings = []; // empty starting array
		$genre_Descs = mysqli_fetch_all($result, MYSQLI_ASSOC); // use a for each to get it	
		foreach($genre_Descs as $desc)
		{
			foreach($desc as $row)
			{
				if(!in_array($row, $genre_strings))//duplicate records can exist
				{
					array_push($genre_strings, $row);
				}
			}
		}	
		$genre_print_string = implode(", <br>", $genre_strings);
		
		//print_r("$genre_print_string");
		
		echo("<td>".$genre_print_string."</td>");
			
			//echo("<td>"."CHANGE ME"."</td>");
			
			echo("<td>".$album['Dist_name']."</td>");
					
			
			echo("<td>".$album['a_price']."</td>");
			
			if($name != 'Guest'){

 				echo("<td>");
				echo("<div class=\"center\">");
				//echo("<input type=\"submit\" name=\"update\" value=\"Add to Order\" class=\"btn brand z-depth-0\">");
				echo ("<a href=\"addItemToOrder.php?itemID=".$album['oi_item_number']."&username=".$name."\""."class=\"btn brand z-depth-0\">Add to Order</a>");
				}
				//echo("<a class=\"  brand z-depth-0\" href=\"addToOrder.php?id=".$album['oi_item_number'].">Manage</a>");
				echo("</div>");
				echo("</td>");
 
  
		
  
 
		
			//the album id will link to the ohi id
			// end if
			echo("</tr>");
		}// end foreach	
	
	
	}// end genre searching else
	
			
}// end query
	
		 ?>
			
	
		
         
            
          </tr>
        </tbody>
      </table>		
	</section>
	
	
	<?php include('templates/footer.php'); ?>

</html>



