<?php
require_once("../header.php"); // 允許所有來源
header("Content-Type: application/json; charset=UTF-8");


try {
    //連線到demo資料庫
    // require_once("../../GridIsland/connectGridIsland.php");
    require_once("../connectGridIsland.php");
    
    $sql = "insert into msg
    (msg_id, mem_id, msg_content, msg_datetime) values 
    (:msg_id,:mem_id,:msg_content,NOW())";

    // 編譯sql指令
    $msg = $pdo->prepare($sql);

    $msg->bindValue(":msg_id",$_POST["msg_id"]);
    $msg->bindValue(":mem_id",$_POST["mem_id"]);
    $msg->bindValue(":msg_content",$_POST["msg_content"]);
    $msg->execute();

    $result = ["error" => false, "msg" => "success"];
} catch (PDOException $e) {
	
    $result = ["error" => true, "msg" => $e->getMessage()];

}
echo json_encode($result);

?>