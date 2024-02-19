<?php 
	$dbname = 'gridisland';//資料庫名稱
	$user = "root";//使用者帳號
	$password = "";//使用者密碼
	// $password = "password";//使用者密碼for Mac
	$port = 3306;//連接的port
	$dsn = "mysql:host=localhost;port=$port;dbname=$dbname;charset=utf8";

<<<<<<< HEAD
	

	// $dbname = "tibamefe_chd104g5";
	// $user = "tibamefe_since2021";
	// $password = "vwRBSb.j&K#E";
	// $port = 3306;
	// $dsn = "mysql:host=localhost;port=$port;dbname=$dbname;charset=utf8";

	
	
=======
>>>>>>> 4c43863c6f0c444fbf76e0f452e823b5b66ae0e2
	$options = array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_CASE=>PDO::CASE_NATURAL);

	//建立pdo物件
	$pdo = new PDO($dsn, $user, $password, $options);
?>