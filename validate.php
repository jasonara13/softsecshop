<?php
session_start();

if($_SESSION['token'] != $_POST['token']){
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
}

if ($_SESSION['token']==$_POST['token']) {
  if (time() >= $_SESSION['token_expire'] || time() >= $_SESSION['iddle_state']) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit();
  } else {

        $_SESSION['iddle_state'] = time() + 600;

        require_once("_inc/controller.php");

        $db_handle = new SecureDB;
        $db_handle->usrLogin($_POST['username'], $_POST['password']);
    }
}
?>
