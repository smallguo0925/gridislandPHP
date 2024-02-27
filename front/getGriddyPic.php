<?php 
require_once("../header.php");
header('Content-Type: application/json'); // 設定回應類型為JSON
header("Access-Control-Allow-Origin: *"); // 允許所有來源
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // 允許的HTTP方法
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // 允許的標頭

try {
    // 連線到資料庫
    require_once("../connectGridIsland.php");

    // 查詢隨機選取的會員圖片資料
    $sql = "SELECT mem_profile FROM mem ORDER BY RAND() LIMIT 36";

    // 準備 SQL 語句
    $stmt = $pdo->prepare($sql);
    
    // 執行 SQL 語句
    $stmt->execute();

    // 獲取查詢結果
    $memProfiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 封裝結果
    $result = [
        "error" => false,
        "msg" => "圖片加載成功",
        "data" => array_map(function($profile) {
            return $_ENV["VITE_API_URL"] . "/images/mem/" . $profile["mem_profile"]; // 注意：環境變數使用方式根據實際情況調整
        }, $memProfiles)
    ];
} catch (PDOException $e) {
    $result = [
        "error" => true, 
        "msg" => $e->getMessage(), 
        "line" => $e->getLine()
    ];
}

// 返回 JSON 格式的結果
echo json_encode($result);
?>
