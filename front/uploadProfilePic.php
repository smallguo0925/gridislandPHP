<?php
// 開啟錯誤顯示，對於開發環境有用，生產環境應該關閉
// ini_set('display_errors', 0);
// error_reporting(E_ALL);

require_once("../header.php");
header('Content-Type: application/json'); // 設定回應類型為JSON
// header("Access-Control-Allow-Origin: *"); // 允許所有來源
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // 允許的HTTP方法
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // 允許的標頭

try {
    // 引入資料庫連接設定檔案
    require_once("../connectGridIsland.php"); // 引入資料庫連接設定

    // 檢查是否收到圖片數據和用戶ID
    if (isset($_POST['profile_pic']) && isset($_POST['user_id'])) {
        // 解碼Base64的圖片數據
        $profilePic = $_POST['profile_pic'];  // 從POST請求中獲取圖片數據
        $userId = $_POST['user_id']; // 從POST請求中獲取用戶ID

        // 將圖片數據從Base64格式轉換回二進制數據
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $profilePic)); // 標準格式，移除圖片數據前綴
        // $imageData就是圖片的原始二進制數據，準備好被寫入檔案系統成為一個實際的圖片檔案。

        // 指定圖片儲存的目錄
        $directory = "images/mem/";
        // 如果目錄不存在，則創建它
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }


        // 生成圖片文件的路徑
        $timestamp = time(); // 獲取當前時間戳記
        $fileName = "{$userId}_{$timestamp}.png"; // 生成包含時間戳記的文件名
        $filePath = $directory . $fileName; // 完整路徑

        // 將圖片二進制數據寫入文件
        file_put_contents($filePath, $imageData);

        $relativeFilePath = $fileName;

        $sql = "UPDATE mem SET mem_profile = :mem_profile WHERE mem_id = :mem_id";
        $stmt = $pdo->prepare($sql);
        !$stmt->execute([':mem_profile' => $relativeFilePath, ':mem_id' => $userId]);

        // 構建成功的回應數據
        $result = ["error" => false, "msg" => "Profile picture updated successfully."];
    } else {
        // 如果沒有收到必要的數據，拋出異常
        throw new Exception("Missing data.");
    }
} catch (Exception $e) {
    // 處理錯誤情況
    $result = ["error" => true, "msg" => $e->getMessage()];
    error_log($e->getMessage()); // 將錯誤記錄到日誌
}
// 將結果以JSON格式回應給前端
echo json_encode($result);

?>