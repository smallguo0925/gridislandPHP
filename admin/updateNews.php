<?php
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: multipart/form-data");

try {
    $filename = '';
    if ($_FILES){
        if ($_FILES["news_image"]["error"] === 0) {
            $dir = "../../image/news/"; 
            if ( !file_exists($dir) ) {
                mkdir($dir);
            }
            $fileExt = pathinfo($_FILES["news_image"]["name"], PATHINFO_EXTENSION);

            $existingFiles = glob($dir . "news_img*.*"); 
            $fileCount = count($existingFiles) + 1;
            
            $filename = "news_img" . $fileCount . "." . $fileExt;

            $from = $_FILES["news_image"]["tmp_name"];
            $to = "$dir{$filename}";
            copy($from, $to); 
    
            $allowedFileTypes = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array(strtolower($fileExt), $allowedFileTypes)) {
                $result = ["error" => true, "msg" => "不支援該檔案類型"];
                echo json_encode($result);
                exit;
            }
            $maxFileSize = 2 * 1024 * 1024;
            if ($_FILES["news_image"]["size"] > $maxFileSize) {
                $result = ["error" => true, "msg" => "圖片大小超過 2MB 限制"];
                echo json_encode($result);
                exit;
            }
    
        } else {
            $result = ["error" => true, "msg" => "檔案上傳失敗"];
            echo json_encode($result);
            exit;
    
    
            // 檢查是否有 POST 資料
            // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //     // 印出 POST 資料
            //     echo "Received POST data:\n";
            //     print_r($_POST);
            // } else {
            //     // 如果不是 POST 資料，回應錯誤訊息
            //     echo "No POST data received.";
            // }
        }

    }
    require_once("../connectGridIsland.php");

    if ($filename) {
        $sql ="UPDATE news SET
            news_title = :news_title,
            news_date = :news_date, 
            news_content = :news_content,
            news_category = :news_category,
            news_image = :news_image
            WHERE news_id = :news_id";
    $news = $pdo->prepare( $sql );
    $news->bindValue(":news_id", intval($_POST['news_id']), PDO::PARAM_INT);
    $news->bindValue(":news_title", $_POST["news_title"]);
    $news->bindValue(":news_content", $_POST["news_content"]);
    $news->bindValue(":news_date", $_POST["news_date"]);
    $news->bindValue(":news_category", $_POST["news_category"]);
    $news->bindValue(":news_image", $filename);
    } else {
        $sql ="UPDATE news SET
            news_title = :news_title,
            news_date = :news_date, 
            news_content = :news_content,
            news_category = :news_category
            WHERE news_id = :news_id";

    $news = $pdo->prepare( $sql );
    $news->bindValue(":news_id", intval($_POST['news_id']), PDO::PARAM_INT);
    $news->bindValue(":news_title", $_POST["news_title"]);
    $news->bindValue(":news_content", $_POST["news_content"]);
    $news->bindValue(":news_date", $_POST["news_date"]);
    $news->bindValue(":news_category", $_POST["news_category"]);
    }
    $news->execute(); 
    $result = ["error" => false,"msg"=>"成功修改最新消息"];
    
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage(),"test"=>$_POST["news_id"]];
}

echo json_encode($result);
?>
