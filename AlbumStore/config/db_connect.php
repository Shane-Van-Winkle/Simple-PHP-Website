<?php 

  	$dbServername = "localhost";
	$dbUsername = "root";
	$dbPassword = "";
	$dbName = "albumstore";
	
	$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

	$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword);// INITIAL CONNECTION to server
		if(!$conn){
			die('Connection error: '. mysqli_connect_error());
	}
	
	
	// Above code is experimentation with detecting if the database exists or not
	// Why not just let mysql do the heavy lifting using 'IF NOT EXISTS'?
	
	//create the database if need be
	$sql = 'CREATE DATABASE IF NOT EXISTS albumstore';
	mysqli_query($conn, $sql);
	// check connection
	if(!$conn){
		echo 'Connection error: '. mysqli_connect_error();
	}
	
	
	$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);// connect to the database
	if(!$conn){
		die('Connection error: '. mysqli_connect_error());
	}
	//create the table if need be
	$sql = '/* Base tables first */

/*Distributors*/
create table if not exists Distributors(
Dist_ID int primary key AUTO_INCREMENT not null,
Dist_name varchar(20)
);

/*Users*/
create table if not exists `Users`( 
u_username varchar(50) primary key,
u_pwd Text,
u_email varchar(50)
);


/*Information the store stores */
create table if not exists Members(
Member_Num int primary key AUTO_INCREMENT not null,
m_fname varchar(20),
m_lname varchar(20),
m_instrument varchar(20)
);
  
    


create table if not exists Bands(
band_id int primary key AUTO_INCREMENT NOT NULL,
b_name varchar(50)
);

create table if not exists Albums(
Album_ID int primary key AUTO_INCREMENT not null,
a_name varchar(50),
a_price float,
a_release_date varchar(20),
a_dist_ID int,
a_band_id int,
Constraint fk_a_dist_ID foreign key(a_dist_ID) references Distributors(Dist_ID),
CONSTRAINT fk_a_band_id foreign key(a_band_id) references Bands(band_id)
);

create table if not exists Members_In_Bands(
mib_Member_Num int not null,
mib_Band_ID int not null,
Constraint fk_mib_Member_Num foreign key(mib_Member_Num) references Members(Member_Num),
Constraint fk_mib_Band_ID foreign key(mib_Band_ID) references Bands(band_id)
);


create table if not exists Genres(
genre_code varchar(10) primary key, 
genre_description varchar(50)
);

create table if not exists albums_Assigned_To_Genres(
aag_Album_ID int,
aag_Genre_Code varchar(10),
Constraint fk_aag_Album_ID foreign key(aag_Album_ID) references Albums(Album_ID), 
Constraint fk_aag_Genre_Code foreign key(aag_Genre_Code) references Genres(genre_code)
);


create table if not exists Orders(
o_Order_ID int primary key AUTO_INCREMENT not null,
o_Order_date varchar(15),
o_Order_value float,
o_username varchar(50),
Constraint fk_username foreign key(o_username) references Users(u_username)
);

create table if not exists Order_Items(
oi_item_number int primary key AUTO_INCREMENT not null,
oi_item_price float,
oi_album_ID int,
Constraint fk_oi_album_ID foreign key(oi_album_ID) references Albums(Album_ID)
);

create table if not exists Order_Has_Order_Items(
ohi_item_number int not null,
ohi_Order_ID int not null,
Constraint fk_ohi_item_number foreign key(ohi_item_number) references Order_Items(oi_item_number),
Constraint fk_ohi_order_ID foreign key(ohi_Order_ID) references Orders(o_Order_ID)
);


';
	mysqli_query($conn, $sql);

?>
