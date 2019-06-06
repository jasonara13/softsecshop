<?php
class SecureDB {


    private $host;
    private $user;
    private $password;
    private $database;
    private $trace;
    private $settings;
    private $conn;

    function __construct() {
    $this->trace = $this->getIniPath();
    $this->settings = $this->setPrivates();
    $this->conn = $this->connectDB();
    }

    function connectDB() {
    	$conn = mysqli_connect($this->host,$this->user,$this->password,$this->database);
    	return $conn;
    }

    function getIniPath(){
    $myPath = str_replace($_SERVER['SERVER_NAME'],"", dirname(__DIR__));
    return $myPath;
    }

    function setPrivates(){
    $config = parse_ini_file($this->trace . "db.ini");
    $this->host = $config["host"];
    $this->user = $config["user"];
    $this->password = $config["pswd"];
    $this->database = $config["database"];
    }

    function usrLogin($usr, $pswd){
        if ( mysqli_connect_errno() ) {
          header( "refresh:3;url=login.php" );
          die ('There was an error while connecting. Please try again later.');
        }

        if ( !isset($usr, $pswd) ) {
          header( "refresh:3;url=login.php" );
          die ('Sorry! You must enter both username and password. Thanks!!!');
        }

        if($usr == "adm1n" && $pswd == "d3vs3cur1ty"){
          header( "refresh:3;url=login.php" );
          die ('Next time, I will block your IP!');
        }

        if ($stmt = $this->conn->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
          $stmt->bind_param('s', $usr);
          $stmt->execute();
          $stmt->store_result();
        }

        if ($stmt->num_rows > 0) {
          $stmt->bind_result($id, $password);
          $stmt->fetch();

          if (password_verify($pswd, $password)) {
            session_regenerate_id();
            $_SESSION['useron'] = TRUE;
            $_SESSION['name'] = $usr;
            $_SESSION['id'] = $id;
            header('Location: index.php');
          } else {
            header( "refresh:3;url=login.php" );
            echo 'Invalid credentials! You will be redirected soon...';
          }
        } else {
          header( "refresh:3;url=login.php" );
          echo 'Invalid credentials! You will be redirected soon...';
        }
        $stmt->close();
    }

	function fetchProducts($item, $filter){
    switch ($filter) {
    case 1:
        if($item == null || $item == " " || $item == "all"){
          $query = 'SELECT * FROM products';
        } else {
          $query = 'SELECT * FROM products WHERE name LIKE "%' .$item. '%"';
        }
        break;
    case 2:
        if($item == null || $item == " " || $item == "all"){
          $query = 'SELECT * FROM products';
        } else {
          $query = 'SELECT * FROM products WHERE code LIKE "%' .$item. '%"';
        }
        break;
    case 3:
        if($item == null || $item == " " || $item == "all"){
            $query = 'SELECT * FROM products ORDER BY price ASC';
        } else {
            $query = 'SELECT * FROM products WHERE name LIKE "%' .$item. '%" ORDER BY price ASC';
        }
        break;
    case 4:
        if($item == null || $item == " " || $item == "all"){
            $query = 'SELECT * FROM products ORDER BY price DESC';
        } else {
            $query = 'SELECT * FROM products WHERE name LIKE "%' .$item. '%" ORDER BY price DESC';
        }
        break;
    case 5:
        if($item == null || $item == " " || $item == "all"){
            $query = 'SELECT * FROM products ORDER BY price ASC';
        } else {
            $query = 'SELECT * FROM products WHERE code LIKE "%' .$item. '%" ORDER BY price ASC';
        }
        break;
    case 6:
        if($item == null || $item == " " || $item == "all"){
            $query = 'SELECT * FROM products ORDER BY price DESC';
        } else {
            $query = 'SELECT * FROM products WHERE code LIKE "%' .$item. '%" ORDER BY price DESC';
        }
        break;
    default:
        $results = null;
        break;
      }
		$result = mysqli_query($this->conn,$query);
		while($row=mysqli_fetch_assoc($result)) {
			$results[] = $row;
		}
		return $results;
	}

	function fetchAllProducts(){
	    $query = 'SELECT * FROM products';
		$result = mysqli_query($this->conn,$query);
		while($row=mysqli_fetch_assoc($result)) {
			$results[] = $row;
		}
		return $results;
	}

  function onAfterConfirm($items){
    $total = 0;
    foreach($items as $item){
      $query = 'SELECT name, code, price FROM products WHERE code = "' . $item[1] . '"';
      $result = mysqli_query($this->conn, $query);
      while($row = mysqli_fetch_assoc($result)) {
        $results[] = $row;
        $totalPrice = "Total Price: " . $item[3];
        $totalQuantity = "Total Quantity: " . $item[2];
        $total = $total + (int)$item[3];
        array_push($results,$totalPrice,$totalQuantity);
      }
    }
    $orderTotal = "Order Total Price: " . $total;
    array_push($results,$orderTotal);
    return $results;
    $this->conn->close();
  }

  function createOrder($items, $address, $validation, $session){
    $dbItems = json_encode($items);
    $sql = "INSERT INTO orders (order_description, cust_address, status, order_session, order_datetime)
    VALUES ('".$dbItems."', '".$address."','".$validation."','".$session."','".date("Y-m-d H:i:s")."')";

    if ($this->conn->query($sql) === TRUE) {
      return true;
    } else {
      return false;
    }
    $this->conn->close();
  }

  function fetchOrder($session){
    $query = 'SELECT id FROM orders WHERE order_session = "' . $session . '"';
    $result = mysqli_query($this->conn, $query);
    while($row = mysqli_fetch_assoc($result)) {
      $results[] = $row;
    }

    $sql = 'UPDATE orders SET status="2" WHERE id="'.$results[0][id].'"';
    if ($this->conn->query($sql) === TRUE) {
      $query = 'SELECT order_description, cust_address, status, order_session, order_datetime FROM orders WHERE order_session = "' . $session . '"';
      $result = mysqli_query($this->conn, $query);
      while($row = mysqli_fetch_assoc($result)) {
        $results[] = $row;
      }
      return $results;
    } else {
      return false;
    }
    $this->conn->close();
  }
}
?>
