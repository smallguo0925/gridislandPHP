<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");  
header("Content-Type: application/json"); 

$data = json_decode(file_get_contents("php://input"),true);

try {
    require_once("../connectGridIsland.php");
    $newsId = $data["newsId"];
    $isChecked = $data["isChecked"];
    $sql ="UPDATE news SET
            news_state = :isChecked
            WHERE news_id = :news_id";

    $news = $pdo->prepare($sql);//使用prepare，避免隱碼攻擊

    $news->bindValue(':isChecked', $isChecked);
    $news->bindValue(':news_id', $newsId);

    $news->execute(); //執行

    $result = ["error" => false,"msg"=>"成功更改最新消息狀態"];

} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}

echo json_encode($result);
?>



