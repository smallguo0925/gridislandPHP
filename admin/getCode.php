<?php
//要請問老師，上線後是否把該句註解
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
try {
	//連線
    require_once("../connectGridIsland.php");

    //準備sql指令
	$sql = "select * from promo";

    // 建立PDO Statement
    $promos = $pdo->query($sql);

    $promosRow = $promos->fetchAll(PDO::FETCH_ASSOC);
	$result = ["error" => false, "msg" => "", "promos" => $promosRow];
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}
echo json_encode($result);

?>