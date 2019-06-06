<?php
session_start();

if($_SESSION['token'] != $_POST['addToken']){
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

    if(time() >= $_SESSION['token_expire'] && time() >= $_SESSION['iddle_state']){
        session_unset();
        session_destroy();
        header('Location: login.php');
    	exit();
    } else{
        $_SESSION['iddle_state'] = time() + 600;

        $checkQuantity = htmlspecialchars(htmlspecialchars($_POST['prodQuantity'], ENT_QUOTES));
        $checkPrice = htmlspecialchars(htmlspecialchars($_POST['prodPrice'], ENT_QUOTES));
        $checkName = htmlspecialchars(htmlspecialchars($_POST['prodName'], ENT_QUOTES));
        $checkSKU = htmlspecialchars(htmlspecialchars($_POST['prodSku'], ENT_QUOTES));

        if(!empty($checkQuantity)){
            if($checkQuantity <= "0" || $checkQuantity >= "101"){
                $itemToCart = array(
                    "prodQuantity" => "false",
                    "prodPrice"    => "false",
                    "prodName"     => "false",
                    "prodSku"      => "false"
                );
                header('Content-Type: application/json');
                echo json_encode($itemToCart);
            } else {
                $prodQuantity = (int)$checkQuantity;
                $prodPrice = (float)$checkPrice;
                $prodName = $checkName;
                $prodSku = $checkSKU;
                $itemToCart = array(
                    "prodQuantity" => $prodQuantity,
                    "prodPrice"    => $prodPrice,
                    "prodName"     => $prodName,
                    "prodSku"      => $prodSku
                );
                header('Content-Type: application/json');
                echo json_encode($itemToCart);
            }
        } elseif(empty($checkQuantity)){
          $itemToCart = array(
              "prodQuantity" => "false",
              "prodPrice"    => "false",
              "prodName"     => "false",
              "prodSku"      => "false"
          );
          header('Content-Type: application/json');
          echo json_encode($itemToCart);
        }
    }
}
?>
