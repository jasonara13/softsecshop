<?php
session_start();
require_once("_inc/token.php");
if($_SESSION['token'] != $_POST['token']){
    session_unset($_SESSION['useron']);
    session_unset($_SESSION['token']);
    session_unset($_SESSION['token_expire']);
    session_unset($_SESSION['iddle_state']);
    session_unset($_SESSION['cart-token']);
    session_unset($_SESSION['cart-checkout']);
    session_unset($_SESSION['name']);
    session_unset($_SESSION['id']);
    session_unset();
    session_destroy();
    header( "refresh:3;url=login.php" );
    echo "Invalid Token";
    exit();
}

//if ($_SESSION['token']==$_POST['token']) {
if(Token::tokenValidity($_POST['token'])){
  if (time() >= $_SESSION['token_expire'] || time() >= $_SESSION['iddle_state']) {
    session_unset($_SESSION['useron']);
    session_unset($_SESSION['token']);
    session_unset($_SESSION['token_expire']);
    session_unset($_SESSION['iddle_state']);
    session_unset($_SESSION['cart-token']);
    session_unset($_SESSION['cart-checkout']);
    session_unset($_SESSION['name']);
    session_unset($_SESSION['id']);
    session_unset();
    session_destroy();
    header( "refresh:3;url=login.php" );
    echo "Your session has expired. You will have to login again.";
    exit();
  } else {
        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){
        Token::generateToken();
        $_SESSION['iddle_state'] = time() + 600;
        require_once("_inc/controller.php");
        $db_handle = new SecureDB;
        $userName = htmlspecialchars(htmlspecialchars($_POST['username'], ENT_QUOTES));
        $userPswd = htmlspecialchars(htmlspecialchars($_POST['password'], ENT_QUOTES));
        $db_handle->usrLogin($userName, $userPswd);
        } else {
                session_unset();
                session_destroy();
                header("refresh:1;url=login.php");
                echo "Wrong Captcha";
        }
    }
}
?>
