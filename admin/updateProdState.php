<?php
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"),true);
try {
    require_once("../connectGridIsland.php");
    $prodId = $data["prodId"];
    $isChecked = $data["isChecked"];
    $sql = "UPDATE prod SET prod_state = :isChecked WHERE prod_id = :prodId";
    $ord = $pdo->prepare($sql);

    $ord->bindValue(":isChecked",$isChecked);
    $ord->bindValue(":prodId",$prodId);

    $ord->execute();
    $result = ["error" => false,"msg"=>"成功更改商品狀態"];
    
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}

echo json_encode($result);
?>
