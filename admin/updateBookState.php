<?php 
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

try {
    // 連線到資料庫
    require_once("../connectGridIsland.php");
    $sql="UPDATE book
    SET book_state=:book_state
    where book_id=:book_id;
        ";

    $update = $pdo->prepare($sql);
    $update ->bindValue(":book_id",$_POST["book_id"]);
    $update ->bindValue(":book_state",$_POST["book_state"]);

    // 執行 SQL 語句
    $update->execute();
    $result = ["error" => false,"msg"=>"成功更改"];


} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage(), "line" => $e->getLine()];
}

echo json_encode($result);

?>