<?php
//跨域的設定

$checkData = json_decode(file_get_contents("php://input"),true);

try {
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
  //連線到connect檔
  require_once("../../GridIsland/connectGridIsland.php");

  $sql = "update report set report_check =:report_check where report_id =:report_id ";

  $report = $pdo->prepare($sql);
  //用來執行不會取得result set的指令，如insert、update、delete
  $report->bindValue(':report_check',$checkData["report_check"]);
  $report->bindValue(':report_id',$checkData["report_id"]);
  $report->execute();

  $result = ["error"=>false, "msg"=> "成功修改"];
} catch (PDOException $e) {
    $result = ["error" => true, "errorMessage" => $e->getMessage()];
  }
echo json_encode($result);

?>