<?php
// 开启错误显示，对于开发环境有用，生产环境应该关闭
ini_set('display_errors', 1);
error_reporting(E_ALL);
file_put_contents("test_log.txt", "收到請求，POST數據：" . var_export($_POST, true, FILE_APPEND));

require_once("../header.php");
header('Content-Type: application/json');
// header("Access-Control-Allow-Origin: *"); // 允許所有來源
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // 允許的HTTP方法
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // 允許的標頭

try {
    // 引入資料庫連接設定檔案
    require_once("../connectGridIsland.php");


    // 檢查是否收到圖片數據和用戶ID
    if (isset($_POST['profile_pic']) && isset($_POST['user_id'])) {
        $profilePic = $_POST['profile_pic']; // Base64編碼的圖片數據
        $userId = $_POST['user_id']; // 用戶ID

        // 解碼Base64圖片數據
        $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $profilePic));
        // 检查解码后的数据
        if ($imageData === false) {
            throw new Exception("Base64 decode failed.");
        }

        $directory = "../images/mem/"; // 示例路径
        if (!file_exists($directory)) {
            if (!mkdir($directory, 0755, true)) {
                throw new Exception("Failed to create directory.");
            }
        }
        $filePath = $directory . "{$userId}.png"; // 構建最終的文件路徑

        if (file_put_contents($filePath, $imageData) === false) {
            throw new Exception("Failed to save the image.");
        }

        $relativeFilePath = "images/mem/{$userId}.png";

        $sql = "UPDATE mem SET mem_profile = :mem_profile WHERE mem_id = :mem_id";
        $stmt = $pdo->prepare($sql);
        if (!$stmt->execute([':mem_profile' => $relativeFilePath, ':mem_id' => $userId])) {
            throw new Exception("Failed to update database.");
        }

        if ($stmt->rowCount() == 0) {
            throw new Exception("No record updated. User not found?");
        }

        $result = ["error" => false, "msg" => "Profile picture updated successfully."];
    } else {
        throw new Exception("Missing data.");
    }
} catch (Exception $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
    // 记录异常信息到日志
    error_log($e->getMessage());
}

echo json_encode($result);
?>