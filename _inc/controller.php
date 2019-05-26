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
      die ('Failed to connect to MySQL: ' . mysqli_connect_error());
    }

    if ( !isset($usr, $pswd) ) {
      die ('Sorry! You must enter both with username and password. Thanks!!!');
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
        echo 'Invalid credentials!';
      }
    } else {
      echo 'Invalid credentials!';
    }
    $stmt->close();
  }

	function fetchProducts($item){
		$query = 'SELECT * FROM products WHERE code LIKE "%' .$item. '%"';
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
}
?>
