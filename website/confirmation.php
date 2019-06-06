<?php
session_start();

if($_SESSION['token'] != $_POST['token']){
    session_unset();
    session_destroy();
    header( "refresh:3;url=login.php" );
    echo "Your session has expired. You will have to login again.";
	exit();
}
else{

    if (!isset($_SESSION['useron'])) {
    	session_unset();
        session_destroy();
        header( "refresh:3;url=login.php" );
        echo "Your session has expired. You will have to login again.";
    	exit();
    }

    if(time() >= $_SESSION['token_expire'] && time() >= $_SESSION['iddle_state']){
        session_unset();
        session_destroy();
        header( "refresh:3;url=login.php" );
        echo "Your session has expired. You will have to login again.";
    	exit();
    } else{

        $_SESSION['iddle_state'] = time() + 600;
              $checkConfirmation = htmlspecialchars(htmlspecialchars($_POST['confirmation'], ENT_QUOTES));
              if(!empty($checkConfirmation) && $checkConfirmation == "1") {
                    $cartItems = strip_tags($_POST['cartDescription']);
                    $checkAddress = strip_tags(htmlspecialchars(htmlspecialchars($_POST['customerAddress'], ENT_QUOTES)));
                    $checkSession = strip_tags(htmlspecialchars(htmlspecialchars($_POST['orderSession'], ENT_QUOTES)));
                    require_once("_inc/controller.php");

                    $db_handle = new SecureDB;

                    $cartItems = json_decode($cartItems);
                    $confirmedItems[] = $db_handle->onAfterConfirm($cartItems);
                    $dbItems = json_encode($confirmedItems);
                    $confirmation = (int)$checkConfirmation;
                    $orderResult = $db_handle->createOrder($confirmedItems, $checkAddress, $confirmation, $checkSession);
                    if($orderResult){
                      $orderConfirmation = array(
                          "confirmed" => "yes",
                          "sent"      => "no"
                      );
                    } else{
                      $orderConfirmation = array(
                          "confirmed" => "no",
                          "sent"      => "no"
                      );
                    }
                    header('Content-Type: application/json');
                    echo json_encode($orderConfirmation);
                }
                if(!empty($checkConfirmation) && $checkConfirmation == "2") {
                  require_once("_inc/token.php");
                  if(Token::checkoutTokenValidity($_POST['checkout'])){
                    require_once("_inc/controller.php");
                    $checkSession = strip_tags(htmlspecialchars(htmlspecialchars($_POST['orderSession'], ENT_QUOTES)));
                    $checkAddress = strip_tags(htmlspecialchars(htmlspecialchars($_POST['customerAddress'], ENT_QUOTES)));
                    $db_handle = new SecureDB;
                    $completedOrder = $db_handle->fetchOrder($checkSession);
                    if(!$completedOrder){
                      $orderConfirmation = array(
                          "approved" => "yes",
                          "sent"      => "no"
                      );
                    } else{
                      require_once("_inc/sendmail.php");
                      $orderConfirmation = array(
                          "approved" => "yes",
                          "sent"      => "yes"
                      );
                    }
                    header('Content-Type: application/json');
                    echo json_encode($orderConfirmation);
                  }
                }
    }
}
?>
