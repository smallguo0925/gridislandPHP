<?php

$reportData = json_decode(file_get_contents("php://input"),true);

try {
  //跨域的設定
  // 允許所有來源訪問
  header("Access-Control-Allow-Origin: *");
  // 允許特定的 HTTP 方法進行跨域請求
  header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
  // 允許特定的 HTTP 首部進行跨域請求
  header("Access-Control-Allow-Headers: Content-Type");
  // 返回 JSON 格式的數據
  header("Content-Type: application/json");
  // 允許使用 cookies 進行跨域請求
  header("Access-Control-Allow-Credentials: true");
  //連線到剛建立的connect檔
  // require_once("../../GridIsland/connectGridIsland.php");
  require_once("../connectGridIsland.php");

  $sql = "
  UPDATE report 
  JOIN msg ON report.msg_id = msg.msg_id
  SET report.report_state = :report_state
  WHERE msg.msg_id = :msg_id
  ";
  
  $report = $pdo->prepare($sql);
  $report->bindValue(':report_state', $reportData["report_state"]);
  $report->bindValue(':msg_id', $reportData["msg_id"]);
  $report->execute();
  $result = ["error"=>false, "msg"=> "成功修改"];
} catch (PDOException $e) {
  $result = ["error" => true, "errorMessage" => $e->getMessage()];
}
echo json_encode($result);

?>