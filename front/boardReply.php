<?php
require_once("../header.php");
header("Content-Type: application/json; charset=UTF-8");


try {
    //連線到demo資料庫
    // require_once("../../GridIsland/connectGridIsland.php");
    require_once("../connectGridIsland.php");
    
    $sql = "insert into reply
    (msg_id, mem_id, reply_content, reply_time) values 
    (:msg_id,:mem_id,:reply_content,NOW())";

    // 編譯sql指令
    $reply = $pdo->prepare($sql);

    $reply->bindValue(":msg_id",$_POST["msg_id"]);
    $reply->bindValue(":mem_id",$_POST["mem_id"]);
    $reply->bindValue(":reply_content",$_POST["reply_content"]);
    $reply->execute();

    $result = ["error" => false, "msg" => "success"];
} catch (PDOException $e) {
	
    $result = ["error" => true, "msg" => $e->getMessage()];

}
echo json_encode($result);

?>