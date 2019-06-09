<?php
session_start();
require_once("_inc/token.php");

if (!isset($_SESSION['useron'], $_SESSION['token'])) {
	session_unset();
	session_destroy();
	header( "refresh:1;url=login.php" );
	echo "<body style='background-color: #2f3947;'></body>";
	exit();
} else {
			if(time() >= $_SESSION['token_expire'] || time() >= $_SESSION['iddle_state']){
				 session_unset();
				 session_destroy();
				 header( "refresh:3;url=login.php" );
				 echo "Your session has expired. You will have to login again.";
			 exit();
		 } elseif(Token::tokenValidity($_SESSION['token'])) {
			 $_SESSION['iddle_state'] = time() + 600;
			 Token::generateToken();
			 ?>
			 <!DOCTYPE html>
			 <html>
			 <?php if (file_exists(__DIR__ . '/_header.php')): ?>
						 <?php include_once(__DIR__ . '/_header.php'); ?>
				 <?php endif; ?>
				 <body class="loggedin">
					 <nav class="navtop">
						 <div>
							 <h1>Software Security Shop</h1>
							 <a href="index.php?session=<?php echo $_SESSION['token']; ?>">Home</a>
							 <a href="catalogue.php?session=<?php echo $_SESSION['token']; ?>">Catalogue</a>
							 <a href="logout.php">Logout</a>
						 </div>
					 </nav>
					 <div class="content">
						 <h2>Home Page</h2>
						 <p>Welcome back, <strong><?php echo $_SESSION['name']; ?></strong>!</p>

					 </div>

					 <?php
							 if (file_exists(__DIR__ . '/_footer.php'))
							 {
								 include_once __DIR__ . '/_footer.php';
							 }
							 ?>

				 </body>
			 </html>
			 <?php
		 }
	else{
		session_unset();
		session_destroy();
		header("refresh:3;url=login.php");
		echo "Ivalid Token.";
	}
}
