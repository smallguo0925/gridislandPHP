<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

try {
    // 連線到資料庫
    require_once("../connectGridIsland.php");
    $sql="UPDATE book
    SET book_state=0
    where book_id=:book_id;
        ";

    $update = $pdo->prepare($sql);
    $update ->bindValue(":book_id",$_POST["book_id"]);

    // 執行 SQL 語句
    $update->execute();
    $result = ["error" => false,"msg"=>"成功更改"];


} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage(), "line" => $e->getLine()];
}

echo json_encode($result);

?>