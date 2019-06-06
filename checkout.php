<?php
session_start();

if($_SESSION['token'] != $_POST['token']){
    session_unset();
    session_destroy();
    header( "refresh:3;url=login.php" );
    echo "Invalid Token";
    exit();
} else {
    if(time() >= $_SESSION['token_expire'] && time() >= $_SESSION['iddle_state']){
        session_unset();
        session_destroy();
        header( "refresh:3;url=login.php" );
        echo "Your session has expired. You will have to login again.";
        exit();
    } else{
        $_SESSION['iddle_state'] = time() + 600;
        if(!isset($_SESSION['useron'])){
            session_unset();
            session_destroy();
            header( "refresh:3;url=login.php" );
            echo "Your session has expired. You will have to login again.";
            exit();
        } else{
            require_once("_inc/token.php");
            if(Token::checkoutTokenValidity($_POST['checkout'])){
                $checkCartItems = strip_tags($_POST['hiddencheckout']);
                $cartItems = json_decode($checkCartItems);
                $cartTotal = 0;
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
                		<div class="content" id="content">
                          <div class="container">
                            <div class="row">
                              <div class="col-lg-12 col-md-12 checkout-container">
                                <h2>Checkout</h2>
                                <p>&nbsp;</p>
                                <p>In order to complete your order you must provide a delivery address and confirm your cart items:</p>
                                <div class="col-lg-12">
                                  <table>
                                    <tr>
                                      <th width="25%">Item</th>
                                      <th width="25%">SKU</th>
                                      <th width="25%">Qty</th>
                                      <th width="25%">Price</th>
                                    </tr>
                                    <?php foreach($cartItems as $ci): ?>
                                    <?php $cartTotal = (int)$cartTotal + (int)$ci[3]; ?>
                                      <tr>
                                        <td><?php echo htmlspecialchars(htmlspecialchars($ci[0], ENT_QUOTES)); ?></td>
                                        <td><?php echo htmlspecialchars(htmlspecialchars($ci[1], ENT_QUOTES)); ?></td>
                                        <td><?php echo htmlspecialchars(htmlspecialchars($ci[2], ENT_QUOTES)); ?></td>
                                        <td><?php echo htmlspecialchars(htmlspecialchars($ci[3], ENT_QUOTES)); ?></td>
                                      </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                      <td><strong>Total Price</strong></td>
                                      <td></td>
                                      <td></td>
                                      <td><strong><?php echo $cartTotal; ?>.00 €</strong></td>
                                    </tr>
                                  </table>
                                </div>
                                <hr class="checkout-hr">
                                <p id="success-box" class="alert alert-success" style="display: none;"></p>
                                <form method="post" id="orderConfirmationForm" autocomplete="off">
                                  <div class="form-group">
                                    <label for="address">Address *</label>
                                    <input type="text" name="customerAddress" id="customerAddress" required>
                                  </div>
                                  <div class="form-group">
                                    <input type="checkbox" name="orderConfirmed" id="orderConfirmed" value="1" required/>
                                    <label for="orderConfirmed">I have checked my virtual cart and provided a valid address, so my order can be created and delivered.</label>
                                  </div>
                                  <input type="hidden" name="cartDescription" id="cartDescription" value='<?php echo $checkCartItems; ?>'>
                                  <input type="hidden" id="token" name="token" value="<?php echo $_SESSION['token']; ?>">
                                  <input type="hidden" id="checkout" name="checkout" value="<?php echo Token::generateCheckoutToken(); ?>">
                                  <input type="hidden" id="orderSession" name="orderSession" value="<?php echo Token::generateCartToken(); ?>">
                                  <input type="submit" id="checkoutSubmit" class="btn btn-danger" name="Submit" value="Confirm Order">
                                </form>
                              </div>
                            </div>
                          </div>
                        </div>
                        <script>
                            $("#orderConfirmationForm").submit(function(){
                              event.preventDefault();
                              var customerAddress = $("input#customerAddress").val();
                              var cartDescription = $("input#cartDescription").val();
                              var token = $("input#token").val();
                              var checkout = $("input#checkout").val();
                              var confirmation = $("input#orderConfirmed").val();
                              var orderSession = $("input#orderSession").val();
                              $.ajax({
                                    url:"/confirmation.php ",
                                    method:"POST",
                                    data:{
                                      customerAddress: customerAddress,
                                      cartDescription: cartDescription,
                                      token: token,
                                      checkout: checkout,
                                      confirmation: confirmation,
                                      orderSession: orderSession
                                    },
                                    success:function(data) {
                                      console.log(data);
                                      if(data.confirmed == "yes" && data.sent == "no"){
                                          var checkout = $('#checkoutSubmit');
                                          $('#orderConfirmed').val('2');
                                          checkout.removeClass('btn-danger');
                                          checkout.addClass('btn-success');
                                          checkout.val('Send Order');
                                          console.log("approved");
                                      }
                                      if (data.approved == "yes" && data.sent == "yes"){
                                          var thisForm = document.getElementById("orderConfirmationForm");
                                          var successBox = document.getElementById("success-box");
                                          successBox.innerHTML = "Your order has been placed succesfully";
                                          $("#success-box").css("display","");
                                          $("#orderConfirmationForm").css("display","none");
                                      }
                                   },
                                   error:function(data){
                                    console.log("error");
                                    console.log(data);
                                   }
                                 });
                            });
                        </script>
                        <?php if (file_exists(__DIR__ . '/_footer.php')): ?>
                          <?php include_once(__DIR__ . '/_footer.php'); ?>
                        <?php endif; ?>
                    </body>
                </html>

                <?php
            } else {
                session_unset();
                session_destroy();
                header( "refresh:3;url=login.php" );
                echo "Invalid Cart Token";
                exit();
            }
        }
    }
}

?>
