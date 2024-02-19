<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"),true);
try {
    require_once("../connectGridIsland.php");
    $ordId = $data["promoId"];
    $isChecked = $data["isChecked"];
    $sql = "UPDATE promo SET promo_state = :isChecked WHERE promo_id = :promoId";
    $ord = $pdo->prepare($sql);

    $ord->bindValue(":isChecked",$isChecked);
    $ord->bindValue(":promoId",$promoId);

    $ord->execute();
    $result = ["error" => false,"msg"=>"成功更改優惠碼狀態"];
    
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}

echo json_encode($result);
?>
