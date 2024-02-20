<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
try {
	//連線
    require_once("../connectGridIsland.php");

    //準備sql指令
	$sql = "SELECT * from promo where promo_id = 2";

    // 建立PDO Statement
    $promos = $pdo->query($sql);

    $promosRow = $promos->fetchAll(PDO::FETCH_ASSOC);

    // if (!empty($promosRow)) {
    //     $result = [
    //         "error" => false,
    //         "msg" => "成功取得優惠碼資料",
    //         "promo" => $promosRow[0]  // 取數組的第一個元素作為對象
    //     ];
    // } else {
    //     $result = ["error" => false, "msg" => "找不到對應的優惠碼", "promo" => null];
    // }


	$result = ["error" => false, "msg" => "成功取得優惠碼資料", "promos" => $promosRow];
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}
echo json_encode($result);

?>