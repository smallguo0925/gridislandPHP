<?php
//跨域的設定
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

try {
  require_once("../connectGridIsland.php");

  $sql = " SELECT mem_profile FROM mem ;"

  // 建立PDO Statement
  $board = $pdo->query($sql);

  $boardRows = $board->fetchAll(PDO::FETCH_ASSOC);

	$result = ["error" => false, "errorMessage" => "", "board" => $boardRows];
} catch (PDOException $e) {
    $result = ["error" => true, "errorMessage" => $e->getMessage()];
}
echo json_encode($result);

?>