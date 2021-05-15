<?php
class Database{
	private static $host;
	private static $db_name;
	private static $username;
	private static $password;
	
	public function __construct(){
		// specify your own database credentials
// 		echo $_SERVER['SERVER_ADDR'];
		if($_SERVER['SERVER_ADDR'] == "127.0.0.1"){
			self::$host = "localhost";
			self::$db_name = "ebcpc187_ebcp";
			self::$username = "root";
			self::$password = "";
		} else if ($_SERVER['SERVER_ADDR'] == "192.168.0.37") { //dados do servidor
			self::$host = "localhost";
			self::$db_name = "ebcpc187_ebcp";
			self::$username = "root";
			self::$password = "";
		} else {
			self::$host = "54.164.120.103";
			self::$db_name = "ebcp";
			self::$username = "ebcp";
			self::$password = "McTi1402";
// 			echo "teste";
		}
	}
	
	// get the database connection
	public static function getConnection(){	
		$conn = null;
		try{
// 			$pdo = "mysql:host=" . self::$host . ";dbname=" . self::$db_name ."," . self::$username . "," . self::$password;
// 			echo "[" . $pdo . "]";
// 			$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$db_name . ";charset=utf8", self::$username, self::$password);//funciona para versoes php 5.3.6 ou superior
			$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$db_name, self::$username, self::$password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			echo "Connection error: " . $e->getMessage();
		}
		
		return $conn;
	}
	
}
?>