<?php
session_start();

if (!isset($_SESSION['useron'])) {
	session_unset();
    session_destroy();
	header('Location: login.php');
	exit();
}
if(!isset($_SESSION['token'])){ // || $_SESSION['token'] != $_GET['id'] when coming back from internal URL
    session_unset();
    session_destroy();
    header('Location: login.php');
	exit();
} else {
        if(time() >= $_SESSION['token_expire'] && time() >= $_SESSION['iddle_state']){
           session_unset();
           session_destroy();
           header('Location: login.php');
	       exit(); 
        } else {
            
            $_SESSION['iddle_state'] = time() + 600;
            
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
            				<a href="index.php?id=<?php echo $_SESSION['token']; ?>">Home</a>
                            <a href="catalogue.php?id=<?php echo $_SESSION['token']; ?>">Catalogue</a>
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
            
                    else if (!file_exists(__DIR__ . '/_footer.php'))
                    {
                    	die('Something went wrong! Please check your directory and try again.');
                    }
                    ?>
            
            	</body>
            </html>
            <?php
        }
}
