<?php
//跨域資源共享設定，允許所有來源訪問
header("Access-Control-Allow-Origin: *");
//允許使用的 HTTP 方法
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
//允許的請求標頭
header("Access-Control-Allow-Headers: Content-Type");
//設定回應類型為 JSON
header("Content-Type: application/json");

ini_set("display_errors", "On"); //上線檢查用

try {
    // 引入資料庫連接設定檔案
    require_once("../connectGridIsland.php");
    // 準備從 mem 表中選擇所有記錄的 SQL 查詢
    $sql = "select * from mem";
    // 執行 SQL 查詢，返回 PDOStatement 物件
    $mem = $pdo->query($sql);
    // 從 PDOStatement 物件中取得所有記錄，以關聯陣列格式
    $memRows = $mem->fetchAll(PDO::FETCH_ASSOC);
    // 準備要回傳的數據，包括錯誤狀態、訊息和會員資料
    $result = ["error" => false, "msg" => "", "mem" => $memRows];
} catch (PDOException $e) {
    // 捕捉到 PDOException，設置錯誤狀態和錯誤訊息
    $result = ["error" => true, "msg" => $e->getMessage()];
}
// 將結果編碼為 JSON 格式並輸出
echo json_encode($result);
?> 
