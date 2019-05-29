<?php
session_start();

if($_SESSION['token'] != $_POST['token']){
    session_unset();
    session_destroy();
    header('Location: login.php');
	exit();
}
else{

    if (!isset($_SESSION['useron'])) {
        session_unset();
        session_destroy();
    	header('Location: login.php');
    	exit();
    }
    /*
     * SKUs = 'LPN45', 'LPX230U', 'MBP2019U', 'HPP12U'
     */

    if(time() >= $_SESSION['token_expire'] && time() >= $_SESSION['iddle_state']){
        session_unset();
        session_destroy();
        header('Location: login.php');
    	exit();
    } else{
        $_SESSION['iddle_state'] = time() + 600;
        require_once("_inc/controller.php");

        $db_handle = new SecureDB;

        if($_POST["searchbar"]){

          if($_POST["searchbar"] != null && $_POST["item"] == null){
            $searchInput = $_POST["searchbar"];
            $searchFilter = $_POST["filter"];
            $productByCode = $db_handle->fetchProducts($searchInput, $searchFilter);
          }
          unset($_POST["searchbar"]);
          header('Content-Type: application/json');
          echo json_encode($productByCode);
        }
        if($_POST["prodQuantity"] != null && $_POST["prodPrice"] != null){
            if($_POST["prodQuantity"] == 0 || $_POST["prodQuantity"] < 0 || $_POST["prodQuantity"] > 100){
                $itemToCart = false;
                echo $itemToCart;
            } else {
                $prodQuantity = (int)$_POST["prodQuantity"];
                $prodPrice = (float)$_POST["prodPrice"];
                $prodName = $_POST["prodName"];
                $prodSku = $_POST["prodSku"];
                $itemToCart = array(
                    "prodQuantity" => $prodQuantity,
                    "prodPrice"    => $prodPrice,
                    "prodName"     => $prodName,
                    "prodSku"      => $prodSku
                );
                header('Content-Type: application/json');
                echo json_encode($itemToCart);
            }
        }
    }
}
?>
