<?php 
//root password, CautiousRunner3@
  session_start();
  if($_SERVER['QUERY_STRING'] == 'noname'){
    session_unset();
  }
   $name = $_SESSION['name'] ?? 'Guest';
   
   //  Xkcd979!
?>

<head>
<title>Album</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <style type="text/css">
	  .brand{
	  	background: #61249d !important;
	  }
  	.brand-text{
  		color: #61249d !important;
  	}
  	form{
  		max-width: 460px;
  		margin: 20px auto;
  		padding: 20px;
  	}
    .album{
      width: 100px;
      margin: 40px auto -30px;
      display: block;
      position: relative;
      top: -30px;
    }
  </style>
</head>
<body class="grey lighten-4">
	<nav class="white z-depth-0">
    <div class="container">
      <a href="index.php" class="brand-logo brand-text">Album Store</a>
      <ul id="nav-mobile" class="right hide-on-small-and-down">
		<li class="grey-text">Hello, <?php echo htmlspecialchars($name);  ?></li>
        <li><?php 	
					if($name == 'Admin') {
						echo ("<a href=\"rootlyPowers.php\" class=\"btn brand z-depth-0\">Administrate</a>");
					}
					else if($name != 'Guest'){
						echo ("<a href=\"Profile.php\" class=\"btn brand z-depth-0\">Profile</a>");
					}
					
					else{
						echo ("<a href=\"signup.php\" class=\"btn brand z-depth-0\">Sign Up</a>");
					}
			?>
         </li>
		 <li>
			 <?php 	if($name != 'Guest'){
						echo ("<a href=\"signout.php\" class=\"btn brand z-depth-0\">Sign out</a>");
					}
					else{
						echo ("<a href=\"signin.php\" class=\"btn brand z-depth-0\">Sign In</a>");
					}
			?>
         </li>
      </ul>
    </div>
  </nav>
</body>
