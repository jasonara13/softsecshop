<?php
session_start();
/*
$startTime = microtime(1);
$startMem  = memory_get_usage();
session_cache_limiter('private');
session_cache_expire(0);
*/
$length = 32;
$_SESSION['token'] = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);
$_SESSION['token_expire'] = time() + 3600;
$_SESSION['iddle_state'] = time() + 600;

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
    <body>

    <?php
    if (file_exists(__DIR__ . '/_header.php'))
    {
    	include_once __DIR__ . '/_header.php';
    }

    else if (!file_exists(__DIR__ . '/_header.php'))
    {
    	die('Something went wrong! Please check your directory and try again.');
    }
    ?>

        <nav class="navtop">
    		<div>
    			<h1>Software Security Shop</h1>
    		</div>
    	</nav>
        <div class="content">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"></div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <form method="post" action="validate.php" autocomplete="off">
                    <div class="form-group">
                      <label for="username">Username</label>
                      <input type="text" class="form-control" name="username" id="username" placeholder="Enter username">
                    </div>
                    <div class="form-group">
                      <label for="password">Password</label>
                      <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
                      <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>"/>
                    </div>
                    <button type="submit" class="btn btn-primary">Log in</button><br>
                    <span><i>In case you forgot your password, please contact the system administrator at <a href="mailto:info@administrator.example">info@administrator.example</a></i></span>
                    </form>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"></div>
            </div>
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
<?php }

?>
