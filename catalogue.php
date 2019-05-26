<?php
session_start();
$tokenValidity = $_GET['id'];

if(!isset($_SESSION['token'])){
    session_unset();
    session_destroy();
    header('Location: login.php');
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
            header('Location: login.php');
            exit();
        } else {
            $_SESSION['iddle_state'] = time() + 600;
            if(!isset($_SESSION['useron'])){
                session_unset();
                session_destroy();
                header('Location: login.php');
                exit();
            } else {
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
                            <h2 class="text-center">Catalogue</h2>
                            <p>&nbsp;</p>
                            <?php //SKUs = 'LPN45', 'LPX230U', 'MBP2019U', 'HPP12U' ?>
                            <div class="row text-center">
                            <div class="col-lg-6 col-md-6">
                            <form action="" id="allProductsForm">
                              <input type="hidden" name="token" id="alltoken" value="<?php echo $_SESSION['token']; ?>"/>
                              <input type="submit" class="btn btn-primary" value="Όλα τα προϊόντα">
                            </form>
                            </div>
                            <div class="col-lg-6 col-md-6">
                            <form action="" id="searchForm">
                              <input type="text" name="searchbar" id="searchbar" placeholder="Search...">
                              <input type="hidden" name="token" id="token" value="<?php echo $_SESSION['token']; ?>"/>
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
                            <p>There are <span id="cartcontainer">0</span> items in your cart</p>
                            <p>Total Amount: <span id="carttotal">0</span>.00 €</p>
                            
                            <div class="" id="cartDescription"></div>
                            
                            <form action="checkout.php" method="post" id="checkoutform">
                              <input type="hidden" id="finalPrice" name="finalPrice" value="0">
                              <input type="hidden" id="finalQuantity" name="finalQuantity" value="0">
                              <input type="hidden" id="hiddencheckout" name="hiddencheckout">
                              <input type="hidden" id="finalToken" name="token" value="<?php echo $_SESSION['token']; ?>">
                              <input type="hidden" id="go" name="go" value="1">
                              <input type="submit" class="btn btn-primary" name="Submit" value="Checkout">
                            </form>
                            <p>
                                <button class="btn btn-secondary" id="clearCart" onclick="resetCart()">Reset Cart</button>
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                <script>
                $("#searchForm").submit(function(){
                  $("#result").empty();
                  event.preventDefault();
                  var term = $("input#searchbar").val();
                  var token = $("input#token").val();
                  $.ajax({
                        url:"/addtocart.php ",
                        method:"POST",
                        data:{
                          searchbar: term,
                          token: token
                        },
                        success:function(data) {
                          if(data == null || typeof data == 'string')  {
                            var product = document.getElementById("result");
                            product.innerHTML = "<div class='product-box col-lg-12' style='text-align: center;'><span style='color: red;'>No results.</span><br><i>You must provide a valid SKU in order to receive successful results!</i></div>";
                            $("#cartinfo").css("display", "none");
                          } else {
                              var i = 0;
                              for (var item in data) {
                                var createDiv = "<div class='product-box col-lg-4'>";
                                var endDiv = "</div>";
                                var beLow = "<br>";
                                var addToCartForm = "<form id='addToCart" + i + "' action=''><input type='text' class='product-quantity' id='prodquantity" + i +
                                                    "' name='prodquantity" + i + "' value='1' size='2' /><br>" +
                                                    "<input type='hidden' id='price" + i + "' value= '" + data[i].price + "'>" +
                                                    "<input type='hidden' id='prodname" + i + "' value='" + data[i].name + "'>" +
                                                    "<input type='hidden' id='cartToken' name='cartToken' value='" + token + "'>" +
                                                    "<input type='submit' class='btn btn-danger' onclick='addToCart(addToCart" + i + ",prodquantity" + i + ", price" + i + ", prodname" + i + ")' value='Add to Cart' /></form>";
                                var product = document.getElementById("result");
                                product.innerHTML += createDiv + data[i].name + beLow + 'SKU: ' + data[i].code + beLow + 'Price: ' + data[i].price + ' €' + beLow + '<img width="200" height="160" src="/assets/images/' + data[i].image + '" />' + beLow + addToCartForm;
                                i++;
                              }
                              $("#cartinfo").css("display", "");
                            }
                       },
                       error:function(){
                        alert("error");
                       }
                     });
                });
                
                $("#allProductsForm").submit(function(){
                  $("#result").empty();
                  event.preventDefault();
                  var products = true;
                  var token = $("input#alltoken").val();
                  $.ajax({
                        url:"/products.php ",
                        method:"POST",
                        data:{
                          products: products,
                          token: token
                        },
                        success:function(data) {
                          var i = 0;
                          for (var item in data) {
                            var createDiv = "<div class='product-box col-lg-4'>";
                            var endDiv = "</div>";
                            var beLow = "<br>";
                            var addToCartForm = "<form id='addToCart" + i + "' action=''><input type='text' class='product-quantity' id='prodquantity" + i +
                                                "' name='prodquantity" + i + "' value='1' size='2' /><br>" +
                                                "<input type='hidden' id='price" + i + "' value= '" + data[i].price + "'>" +
                                                "<input type='hidden' id='prodname" + i + "' value='" + data[i].name + "'>" +
                                                "<input type='hidden' id='cartToken' name='cartToken' value='" + token + "'>" +
                                                "<input type='submit' class='btn btn-danger' onclick='addToCart(addToCart" + i + ",prodquantity" + i + ", price" + i + ", prodname" + i + ")' value='Add to Cart' /></form>";
                            var product = document.getElementById("result");
                            product.innerHTML += createDiv + data[i].name + beLow + 'SKU: ' + data[i].code + beLow + 'Price: ' + data[i].price + ' €' + beLow + '<img width="200" height="160" src="/assets/images/' + data[i].image + '" />' + beLow + addToCartForm;
                            i++;
                          }
                          $("#cartinfo").css("display", "");
                       },
                       error:function(){
                        alert("error");
                       }
                     });
                });
                
                var cartItems = [];
                
                function addToCart(formid,quantity,price,product){
                  $(formid).one( "submit", function() {
                    event.preventDefault();
                    prodQuantity = $(quantity).val();
                    itemPrice = $(price).val();
                    prodPrice = parseInt(prodQuantity) * parseInt(itemPrice);
                    prodName = $(product).val();
                    cartToken = $("#cartToken").val();
                    
                    $.ajax({
                          url:"/addtocart.php ",
                          method:"POST",
                          data:{
                            prodQuantity: prodQuantity,
                            prodPrice: prodPrice,
                            prodName: prodName,
                            token: cartToken
                          },
                          success:function(data) {
                            var finalQuantity = $("#finalQuantity");
                            var displayCartQuantity = document.getElementById("cartcontainer");
                            var containerVal = $("#cartcontainer").html();
                            var storeQuantity = parseInt(containerVal) + parseInt(data.prodQuantity);
                            displayCartQuantity.innerHTML = storeQuantity;
                
                            var finalPrice = $("#finalPrice");
                            var displayCartTotal = document.getElementById("carttotal");
                            var containerTotal = $("#carttotal").html();
                            var storeTotal = parseFloat(containerTotal) + parseFloat(data.prodPrice);
                            displayCartTotal.innerHTML = storeTotal;
                            
                            var displayFooterCartTotal = document.getElementById("cartbadge");
                            var footerCartTotal = $("#cartbadge").html();
                            var footerTotal = parseInt(footerCartTotal) + parseInt(data.prodQuantity);
                            displayFooterCartTotal.innerHTML = footerTotal;
                            if (footerTotal != 0){
                                $("#cartbadge").css("color", "#ffb556");
                            }
                            
                            
                            var displayCartDescription = document.getElementById("cartDescription");
                            var hiddenCheckoutField = $('#hiddencheckout');
                            displayCartDescription.innerHTML += "<p>" + data.prodQuantity + "<strong> X </strong> " + data.prodName + ", Price: " + data.prodPrice + ".00 €</p><hr>";
                            
                            finalQuantity.val(storeQuantity);
                            finalPrice.val(storeTotal);
                            
                            cartItems.push( [data.prodName, data.prodQuantity, data.prodPrice] );
                            $('#hiddencheckout').val(JSON.stringify(cartItems));     
                         },
                         error:function(){
                          alert("error");
                         }
                       });
                       
                       
                       
                       console.log(cartItems);
                    });
                }
                
                function resetCart(){
                    var finalPriceField = $("input#finalPrice");
                    var finalQuantityField = $("input#finalQuantity");
                    var displayCartQuantity = document.getElementById("cartcontainer");
                    var displayCartTotal = document.getElementById("carttotal");
                    finalPriceField.val(0);
                    finalQuantityField.val(0);
                    displayCartQuantity.innerHTML = 0;
                    displayCartTotal.innerHTML = 0;
                }
                </script>
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
