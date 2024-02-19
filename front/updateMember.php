<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
try {
    

    // 連線到資料庫
    require_once("../connectGridIsland.php");

    $sql="UPDATE mem 
    SET mem_name = :mem_name ,
        mem_nickname = :mem_nickname,
        mem_tel = :mem_tel,
        mem_gender = :mem_gender,
        mem_birthday = :mem_birthday,
        mem_addr = :mem_addr
    WHERE mem_id = :mem_id;
        ";
    $updateData = $pdo->prepare($sql);
    
    $updateData->bindValue(":mem_id",$_POST["mem_id"]);
    $updateData->bindValue(":mem_name",$_POST["mem_name"]);
    $updateData->bindValue(":mem_nickname",$_POST["mem_nickname"]);
    $updateData->bindValue(":mem_tel",$_POST["mem_tel"]);
    $updateData->bindValue(":mem_gender",$_POST["mem_gender"]);
    $updateData->bindValue(":mem_birthday",$_POST["mem_birthday"]);
    $updateData->bindValue(":mem_addr",$_POST["mem_addr"]);

    // 執行 SQL 語句
    $updateData->execute();

    $result = ["error" => false,"msg"=>"成功更改"];



} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage(), "line" => $e->getLine()];
}

echo json_encode($result);


















?>