<?php
session_start();
$tokenValidity = htmlspecialchars(htmlspecialchars($_GET['session'], ENT_QUOTES));

if(!isset($_SESSION['token'])){
    session_unset();
    session_destroy();
    header( "refresh:3;url=login.php" );
    echo "Invalid Token";
    exit();
} else {
    if($_SESSION['token'] != $tokenValidity || $_SESSION['token'] == null){
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit();
    } else{
        if(time() >= $_SESSION['token_expire'] && time() >= $_SESSION['iddle_state']){
            session_unset();
            session_destroy();
            header( "refresh:3;url=login.php" );
            echo "Your session has expired. You will have to login again.";
            exit();
        } else {
            $_SESSION['iddle_state'] = time() + 600;
            if(!isset($_SESSION['useron'])){
                session_unset();
                session_destroy();
                header( "refresh:3;url=login.php" );
                echo "Your session has expired. You will have to login again.";
                exit();
            } else {
                require_once("_inc/token.php");
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
                		<div class="content" id="content">
                      <div class="container">
                        <div class="row">
                          <div class="col-lg-12 col-md-12">
                            <h2>Catalogue</h2>
                            <p>&nbsp;</p>
                            <div class="row text-center">
                            <div class="col-lg-12 col-md-12">
                            <form action="" id="searchForm" autocomplete="off">
                              <label for="sel1">Product Filters: (select one):</label>
                              <div class="form-group">
                                <select class="form-control" id="sel1">
                                  <option value="1">Product Name</option>
                                  <option value="2">SKU</option>
                                  <option value="3">Name / Price ASC</option>
                                  <option value="4">Name / Price DESC</option>
                                  <option value="5">SKU / Price ASC</option>
                                  <option value="6">SKU / Price DESC</option>
                                </select>
                              </div>
                              <div class="form-group">
                                <input type="text" class="form-control" name="searchbar" id="searchbar" placeholder="Search...">
                              </div>
                              <input type="hidden" name="token" id="token" value="<?php echo $_SESSION['token']; ?>"/>
                              <input type="hidden" name="cartToken" id="cartToken" value="<?php echo Token::generateCartToken(); ?>"/>
                              <input type="submit" class="btn btn-primary" value="Search">
                            </form>
                            </div>
                            </div>
                          </div>
                        </div>
                        <div class="row" id="result">
                        </div>
                        <div class="row" id="cartinfo" style="display: none;">
                          <div class="col-lg-12 col-md-12 text-center product-box">
                            <p id="cartcontainerp">There are <span id="cartcontainer">0</span> items in your cart</p>
                            <p id="carttotalp">Total Amount: <span id="carttotal">0</span>.00 €</p>
                            <p id="product-box" class="alert alert-danger" style="display: none;"></p>
                            <p id="reset-box" class="alert alert-success" style="display: none;"></p>
                            <div class="" id="cartDescription"></div>

                            <form action="checkout.php" method="post" id="checkoutform" autocomplete="off">
                              <input type="hidden" id="finalPrice" name="finalPrice" value="0">
                              <input type="hidden" id="finalQuantity" name="finalQuantity" value="0">
                              <input type="hidden" id="hiddencheckout" name="hiddencheckout">
                              <input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>">
                              <input type="hidden" id="checkout" name="checkout" value="<?php echo Token::generateCheckoutToken(); ?>">
                              <input type="hidden" id="go" name="go" value="1">
                              <input type="submit" class="btn btn-primary" name="Submit" value="Checkout">
                            </form>
                            <p class="reset-cart">
                                <button class="btn btn-secondary" id="clearCart" onclick="resetCart()">Reset Cart</button>
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                <script src="js/catalogue.js"></script>
                <?php if (file_exists(__DIR__ . '/_footer.php')): ?>
                  <?php include_once(__DIR__ . '/_footer.php'); ?>
                <?php endif; ?>
                  </body>
                </html>

                <?php
            }
        }
    }
}
?>
