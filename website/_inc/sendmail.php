<?php
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
$from = "tester@swsec-eshop.local";
$to = "gbkaragiannidis@gmail.com, alcaeusdim@gmail.com";
$subject = "Software Security e-shop";
$message = "";
$i=0;
foreach($completedOrder as $cx){
  foreach($cx as $key => $val){
    switch ($key) {
      case "id":
          $message .= "Order ID: " . $val . "\n\n";
          break;
      case "order_description":
          $message .= "Order Description: " . $val . "\n\n";
          break;
      case "cust_address":
          $message .= "Customer Address: " . $val . "\n\n";
          break;
      case "status":
          if($val == "2"){
            $status = "Completed";
          }
          $message .= "Order Status: " . $status . "\n\n";
          break;
      case "order_session":
          $message .= "Order Session ID: " . $val ."\n\n";
          break;
      case "order_datetime":
          $message .= "Order Placement Date: " . $val ."\n";
          break;
      default:
          $message .= "\n";
      }
    //$message .= $cy ."\n";
  }
}
$headers = "From:" . $from;
mail($to,$subject,$message, $headers);
echo "Email sent successfully.";
?>
