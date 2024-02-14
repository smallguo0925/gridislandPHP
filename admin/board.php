<?php
//跨域的設定
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");


try {
	//連線到剛建立的connect檔
  require_once("../../GridIsland/connectGridIsland.php");

  $sql = "select * from msg";

  // 建立PDO Statement，原本的寫法會是$pdoStatement = $pdo->query($sql);
  $board = $pdo->query($sql);
  //沒有未知數(前台傳進來的資料)用query就好

  $boardRows = $board->fetchAll(PDO::FETCH_ASSOC);
	$result = ["error" => false, "errorMessage" => "", "board" => $boardRows];
} catch (PDOException $e) {
    $result = ["error" => true, "errorMessage" => $e->getMessage()];
}
echo json_encode($result);

?>