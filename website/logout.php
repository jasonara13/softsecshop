<?php
session_start();
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
header('Location: login.php');
?>
