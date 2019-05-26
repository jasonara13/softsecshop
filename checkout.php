<?php
session_start();

if($_SESSION['token'] != $_POST['token']){
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
    } else{
        $_SESSION['iddle_state'] = time() + 600;
        if(!isset($_SESSION['useron'])){
            session_unset();
            session_destroy();
            header('Location: login.php');
            exit();
        } else{
            $length = 64;
            $_SESSION['orderConfirmation'] = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);
            $cartItems = json_decode($_POST['hiddencheckout']);

            ?>
            <!DOCTYPE html>
            <html>
          		<?php if (file_exists(__DIR__ . '/_header.php')): ?>
          	    <?php include_once(__DIR__ . '/_header.php'); ?>
          	  <?php endif; ?>
            	<body class="loggedin"><?php echo $_SESSION['orderConfirmation']; ?>
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
                          <div class="col-lg-12 col-md-12 text-center">
                            <h2>Checkout</h2>
                            <p>&nbsp;</p>
                            <table style="width:100%">
                              <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Price</th>
                              </tr>
                                <?php foreach($cartItems as $ci): ?>
                                <tr>
                                    <td><?php echo $ci[0]; ?></td>
                                    <td><?php echo $ci[1]; ?></td>
                                    <td><?php echo $ci[2]; ?>.00 €</td>
                                </tr>
                                <?php endforeach; ?>
                              <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                              </tr>
                              <tr>
                                <td></td>
                                <td><strong>Total Qty.: <?php echo $_POST["finalQuantity"]; ?></strong></td>
                                <td><strong>Total Price: <?php echo $_POST["finalPrice"]; ?>.00 €</strong></td>
                              </tr>
                            </table>
                            <p>&nbsp;</p>
                            <form method="post" id="orderConfirmationForm" autocomplete="off">
                              <label for="address">Address *</label>
                              <input type="text" name="customerAddress" id="customerAddress" required>
                              <input type="hidden" name="cartDescription" id="cartDescription" value='<?php echo $_POST["hiddencheckout"]; ?>'>
                              <input type="hidden" name="totalQty" id="totalQty" value="<?php echo $_POST["finalQuantity"]; ?>">
                              <input type="hidden" name="totalPrice" id="totalPrice" value="<?php echo $_POST["finalPrice"]; ?>">
                              <input type="hidden" name="orderConfirmed" id="orderConfirmed">
                              <input type="hidden" id="finalToken" name="token" value="<?php echo $_SESSION['token']; ?>">
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
                          var totalQuantity = $("input#totalQty").val();
                          var totalPrice = $("input#totalPrice").val();
                          var token = $("input#finalToken").val();
                          var confirmation = $("input#orderConfirmed").val();
                          $.ajax({
                                url:"/confirmation.php ",
                                method:"POST",
                                data:{
                                  customerAddress: customerAddress,
                                  cartDescription: cartDescription,
                                  totalQuantity: totalQuantity,
                                  totalPrice: totalPrice,
                                  token: token,
                                  confirmation: confirmation
                                },
                                success:function(data) {
                                  if(data.confirmed == "yes" && data.sent == "no"){
                                      var checkout = $('#checkoutSubmit');
                                      $('#orderConfirmed').val('<?php echo $_SESSION['orderConfirmation']; ?>');
                                      checkout.removeClass('btn-danger');
                                      checkout.addClass('btn-success');
                                      checkout.val('Send Order');
                                      console.log("approved");
                                  }
                                  if (data.approved == "yes" && data.sent == "yes"){
                                      console.log("success");
                                  }
                               },
                               error:function(){
                                alert("error");
                               }
                             });
                        });
                    </script>
                    <?php if (file_exists(__DIR__ . '/cart_footer.php')): ?>
                      <?php include_once(__DIR__ . '/cart_footer.php'); ?>
                    <?php endif; ?>
                    <?php if (file_exists(__DIR__ . '/_footer.php')): ?>
                      <?php include_once(__DIR__ . '/_footer.php'); ?>
                    <?php endif; ?>
                </body>
            </html>

            <?php
        }
    }
}

?>
