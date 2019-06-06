<?php
session_start();
require_once("_inc/token.php");
if($_SESSION['token'] != $_POST['token']){
    session_unset();
    session_destroy();
    header( "refresh:3;url=login.php" );
    echo "Invalid Token";
	exit();
}
else{

    if (!isset($_SESSION['useron'])) {
      header( "refresh:3;url=login.php" );
      echo "Your session has expired. You will have to login again.";
    	exit();
    }

    if(time() >= $_SESSION['token_expire'] && time() >= $_SESSION['iddle_state']){
      header( "refresh:3;url=login.php" );
      echo "Your session has expired. You will have to login again.";
    	exit();
    } else{
        if(Token::cartTokenValidity($_POST['cartToken'])){
            $_SESSION['iddle_state'] = time() + 600;

            require_once("_inc/controller.php");
        
            $db_handle = new SecureDB;
            if($_POST['flag'] == "default"){
                if($_POST["products"]){
                  $productByCode = $db_handle->fetchAllProducts();
                  header('Content-Type: application/json');
                  echo json_encode($productByCode);
                }
            }
            if($_POST['flag'] == "filtered"){
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
            }
        }
    }
}
?>
