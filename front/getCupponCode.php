<?php
require_once("../header.php");

header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
try {
    require_once("../connectGridIsland.php");
	$sql = "SELECT * from promo where marquee_state = 1 and promo_state = 1";
    $promos = $pdo->query($sql);
    $promosRow = $promos->fetchAll(PDO::FETCH_ASSOC);
	$result = ["error" => false, "msg" => "成功取得優惠碼資料", "promos" => $promosRow];
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}
echo json_encode($result);
?>