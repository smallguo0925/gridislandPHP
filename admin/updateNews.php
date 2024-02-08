<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: multipart/form-data"); //上傳內容包含圖片。故使用form

try {
    require_once("../connectGridIsland.php");
    $ordId = $data["ordId"];
    $isChecked = $data["isChecked"];
    $sql = "UPDATE ord SET ord_state = :isChecked WHERE ord_id = :ordId";
    $ord = $pdo->prepare($sql);

    $ord->bindValue(":isChecked",$isChecked);
    $ord->bindValue(":ordId",$ordId);

    $ord->execute();
    $result = ["error" => false,"msg"=>"成功更改訂單狀態"];
    
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}

echo json_encode($result);
?>
