$("#searchForm").submit(function(){
    $("#result").empty();
    event.preventDefault();
    var term = $("input#searchbar").val();
    if (term === "" || term === " "){
        term = "all";
    }
    var filter = $("select#sel1").val();
    var token = $("input#token").val();
    var cartToken = $("input#cartToken").val();
    var product = "";
    var flag = "filtered";
    $.ajax({
        url:"/products.php ",
        method:"POST",
        data:{
            searchbar: term,
            filter: filter,
            token: token,
            cartToken: cartToken,
            flag: flag
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
                                  "<input type='hidden' id='prodsku" + i + "' value='" + data[i].code + "'>" +
                                  "<input type='hidden' id='addToken" + i + "' value='" + token + "'>" +
                                  "<input type='submit' class='btn btn-danger' onclick='addToCart(addToCart" + i + ",prodquantity" + i + ", price" + i + ", prodname" + i + ", prodsku" + i + ", addToken" + i + ")' value='Add to Cart' /></form>";
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

window.addEventListener('DOMContentLoaded', (event) => {
    $("#result").empty();
    var products = true;
    var token = $("input#token").val();
    var cartToken = $("input#cartToken").val();
    var flag = "default";
    $.ajax({
        url:"/products.php ",
        method:"POST",
        data:{
            products: products,
            token: token,
            cartToken: cartToken,
            flag: flag
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
                                  "<input type='hidden' id='prodsku" + i + "' value='" + data[i].code + "'>" +
                                  "<input type='hidden' id='addToken" + i + "' value='" + token + "'>" +
                                  "<input type='submit' class='btn btn-danger' onclick='addToCart(addToCart" + i + ",prodquantity" + i + ", price" + i + ", prodname" + i + ", prodsku" + i + ", addToken" + i + ")' value='Add to Cart' /></form>";
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

function addToCart(formid,quantity,price,product,sku,token){
    $(formid).one( "submit", function() {
        event.preventDefault();
        prodQuantity = $(quantity).val();
        itemPrice = $(price).val();
        prodPrice = parseInt(prodQuantity) * parseInt(itemPrice);
        prodName = $(product).val();
        prodSku = $(sku).val();
        addToken = $(token).val();
        var displayCartQuantity = "";
        var displayCartTotal = "";

        $.ajax({
            url:"/addtocart.php ",
            method:"POST",
            data:{
                prodQuantity: prodQuantity,
                prodPrice: prodPrice,
                prodName: prodName,
                prodSku: prodSku,
                addToken: addToken
            },
            success:function(data) {
                if(data.prodQuantity === "false"){
                    var displayInvalidMsg = document.getElementById("product-box");
                    displayCartQuantity = document.getElementById("cartcontainer");
                    displayCartTotal = document.getElementById("carttotal");
                    var cartBadge = document.getElementById("cartbadge");
                    var resetToZero = 0;
                    displayCartQuantity.innerHTML = resetToZero;
                    displayCartTotal.innerHTML = resetToZero;
                    cartBadge.innerText = resetToZero;
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
                    var storeTotal = parseFloat(c
