<?php
//跨域的設定
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

header("Content-Type: application/json");

session_start(); //用session來記錄登入的狀況


try {
	//連線到剛建立的connect檔
    require_once("../connectGridIsland.php");

  //準備sql指令，要拿table_type裡全部的資料。
	$sql = "SELECT * FROM `mem`";

  // 建立PDO Statement，原本的寫法會是
    //   $pdoStatement = $pdo->query($sql);
	//為方便使用閱讀，將$pdoStatement設定為$mem
  $mem = $pdo->query($sql);
	//因為前面用select，這裡用query即可

  $memRows = $mem->fetchAll(PDO::FETCH_ASSOC);
	$result = ["error" => false, "msg" => "", "mem" => $memRows];
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}
echo json_encode($result);





















?>