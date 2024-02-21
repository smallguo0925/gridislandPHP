<?php 
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

try {
    // 連線到資料庫
    require_once("../connectGridIsland.php");
    $sql="UPDATE mem
    set mem_state=:switch_change
    where mem_id=:mem_id";

    $update = $pdo->prepare($sql);
    $update ->bindValue(":mem_id",$_POST["mem_id"]);
    $update ->bindValue("switch_change",$_POST["switch_change"]);

    // 執行 SQL 語句
    $update->execute();
    $result = ["error" => false,"msg"=>"成功更改"];


} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage(), "line" => $e->getLine()];
}

echo json_encode($result);

?>