<?php

try {
  //跨域的設定
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type");
  header("Content-Type: application/json");
  //連線到connect檔
  // require_once("../../GridIsland/connectGridIsland.php");
  require_once("../connectGridIsland.php");

  $sql = "select r.report_id, m.msg_id, r.report_reason, m.msg_content, r.report_state, r.report_check 
    FROM report r join msg m on r.msg_id = m.msg_id
    ORDER BY msg_id desc";

  // 建立PDO Statement，原本的寫法會是$pdoStatement = $pdo->query($sql);
  $report = $pdo->query($sql);

  $reportRows = $report->fetchAll(PDO::FETCH_ASSOC);
	$result = ["error" => false, "errorMessage" => "", "Report" => $reportRows];
} catch (PDOException $e) {
    // $result = ["error" => true, "errorMessage" => $e->getMessage()];
    // echo "SQL 錯誤： " . $e->getMessage();
    $result = ["error" => true, "errorMessage" => $e->getMessage()];
    echo "SQL 錯誤： " . $e->getMessage();
}
echo json_encode($result);

?>