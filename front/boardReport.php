<?php
header("Access-Control-Allow-Origin: *"); // 允許所有來源
header("Content-Type: application/json; charset=UTF-8");


try {
    //連線到demo資料庫
    // require_once("../../GridIsland/connectGridIsland.php");
    require_once("../connectGridIsland.php");
    
    $sql = "insert into report
    (msg_id, report_reason, report_time) values 
    (:msg_id,:report_reason,NOW())";

    // 編譯sql指令
    $reply = $pdo->prepare($sql);

    $reply->bindValue(":msg_id",$_POST["msg_id"]);
    $reply->bindValue(":report_reason",$_POST["report_reason"]);
    $reply->execute();

    $result = ["error" => false, "msg" => "success"];
} catch (PDOException $e) {
	
    $result = ["error" => true, "msg" => $e->getMessage()];

}
echo json_encode($result);

?>