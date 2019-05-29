$("#searchForm").submit(function(){
    $("#result").empty();
    event.preventDefault();
    var term = $("input#searchbar").val();
    if (term === "" || term === " "){
        term = "all";
    }
    var filter = $("select#sel1").val();
    var token = $("input#token").val();
    var product = "";
    $.ajax({
        url:"/addtocart.php ",
        method:"POST",
        data:{
            searchbar: term,
            filter: filter,
            token: token
        },
        success:function(data) {
            console.log(data);
            if(data === null){
                product = document.getElementById("result");
                product.innerHTML = "<div class='product-box col-lg-12' style='text-align: center;'><span style='color: red;'>No results.</span><br><i>You must provide a valid search filter in order to receive successful results!</i></div>";
                $("#cartinfo").css("display", "none");
            } else {
                var i = 0;
                for (var item in data) {
                    var createDiv = "<div class='product-box col-lg-4'>";
                    var endDiv = "</div>";
                    var beLow = "<br>";
                    var addToCartForm = "<form id='addToCart" + i + "' action='' autocomplete='off'><input type='text' class='product-quantity' id='prodquantity" + i +
                                    "' name='prodquantity" + i + "' value='1' size='2' /><br>" +
                                    "<input type='hidden' id='price" + i + "' value= '" + data[i].price + "'>" +
                                    "<input type='hidden' id='prodname" + i + "' value='" + data[i].name + "'>" +
                                    "<input type='hidden' id='prodname" + i + "' value='" + data[i].name + "'>" +
                                    "<input type='hidden' id='cartToken' name='cartToken' value='" + token + "'>" +
                                    "<input type='submit' class='btn btn-danger' onclick='addToCart(addToCart" + i + ",prodquantity" + i + ", price" + i + ", prodname" + i + ")' value='Add to Cart' /></form>";
                    product = document.getElementById("result");
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

window.addEventListener('DOMContentLoaded', (event) => {
    $("#result").empty();
    var products = true;
    var token = $("input#token").val();
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
                var addToCartForm = "<form id='addToCart" + i + "' action='' autocomplete='off'><input type='text' class='product-quantity' id='prodquantity" + i +
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
        var displayCartQuantity = "";
        var displayCartTotal = "";

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
                if(data === false){
                    var displayInvalidMsg = document.getElementById("product-box");
                    displayCartQuantity = document.getElementById("cartcontainer");
                    displayCartTotal = document.getElementById("carttotal");
                    var resetToZero = 0;
                    displayCartQuantity.innerHTML = resetToZero;
                    displayCartTotal.innerHTML = resetToZero;
                    displayInvalidMsg.innerHTML = "You have added an invalid value. Cart has been reset.";
                    $("#product-box").css("display","");
                    $("#cartDescription").empty();
                    $('#hiddencheckout').val(null);
                    cartItems = [];
                } else {
                    var finalQuantity = $("#finalQuantity");
                    displayCartQuantity = document.getElementById("cartcontainer");
                    var containerVal = $("#cartcontainer").html();
                    var storeQuantity = parseInt(containerVal) + parseInt(data.prodQuantity);
                    displayCartQuantity.innerHTML = storeQuantity;

                    var finalPrice = $("#finalPrice");
                    displayCartTotal = document.getElementById("carttotal");
                    var containerTotal = $("#carttotal").html();
                    var storeTotal = parseFloat(containerTotal) + parseFloat(data.prodPrice);
                    displayCartTotal.innerHTML = storeTotal;
    
                    var displayFooterCartTotal = document.getElementById("cartbadge");
                    var footerCartTotal = $("#cartbadge").html();
                    var footerTotal = parseInt(footerCartTotal) + parseInt(data.prodQuantity);
                    displayFooterCartTotal.innerHTML = footerTotal;
                    if (footerTotal !== 0){
                        $("#cartbadge").css("color", "#ffb556");
                    }

                    var displayCartDescription = document.getElementById("cartDescription");
                    var hiddenCheckoutField = $('#hiddencheckout');
                    displayCartDescription.innerHTML += "<p>" + data.prodQuantity + "<strong> X </strong> " + data.prodName + ", Price: " + data.prodPrice + ".00 €</p><hr>";
    
                    finalQuantity.val(storeQuantity);
                    finalPrice.val(storeTotal);

                    cartItems.push( [data.prodName, data.prodQuantity, data.prodPrice] );
                    $('#hiddencheckout').val(JSON.stringify(cartItems));
                    $("#product-box").css("display","none");
                }
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
    var resetCartQuantity = document.getElementById("cartcontainer");
    var resetCartTotal = document.getElementById("carttotal");
    finalPriceField.val(0);
    finalQuantityField.val(0);
    resetCartQuantity.innerHTML = 0;
    resetCartTotal.innerHTML = 0;
}
