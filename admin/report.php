<?php
//跨域的設定
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");


try {
	//連線到剛建立的connect檔
  require_once("../../GridIsland/connectGridIsland.php");

  $sql = "select r.report_id, m.msg_id, r.report_reason, m.msg_content, r.report_state, r.report_check 
    FROM report r join msg m on r.msg_id = m.msg_id
    ORDER BY msg_id";

  // 建立PDO Statement，原本的寫法會是$pdoStatement = $pdo->query($sql);
  $report = $pdo->query($sql);
  //沒有未知數(前台傳進來的資料)用query就好

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