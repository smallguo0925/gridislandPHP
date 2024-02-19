<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: multipart/form-data"); //上傳內容包含圖片。故使用form

require_once("../connectGridIsland.php");


try {
    $filename = ''; //初始化 $filename
    //--------------取得上傳檔案
    if ($_FILES["news_image"]["error"] === 0) {
        $dir = "../images/news/"; //確認路徑
        if ( !file_exists($dir) ) { //確認目錄已存在
            mkdir($dir);
        }
        $fileExt = pathinfo($_FILES["news_image"]["name"], PATHINFO_EXTENSION);

        //取得已存在的檔案數量
        $existingFiles = glob($dir . "news_img*.png"); //調整檔案類型和前綴
        $fileCount = count($existingFiles) + 1;

        //產生新檔案名稱
        $filename = "news_img" . $fileCount . ".png";


        $to = "$dir{$filename}";
        copy($from, $to); //把傳到暫存區的檔案，拷貝出來。

         //檢查檔案類型
        $allowedFileTypes = ['jpg', 'jpeg', 'png'];
        if (!in_array(strtolower($fileExt), $allowedFileTypes)) {
            $result = ["error" => true, "msg" => "不支援該檔案類型"];
            echo json_encode($result);
            exit;
        }

    //     //檢查檔案大小
    //     $maxFileSize = 5 * 1024 * 1024; //最大5MB
    //     if ($_FILES["news_image"]["size"] > $maxFileSize) {
    //         $result = ["error" => true, "msg" => "檔案大小超過限制"];
    //         echo json_encode($result);
    //         exit;
    //     }

    } else {
        $result = ["error" => true, "msg" => "檔案上傳失敗"];
        echo json_encode($result);
        exit;


        // 檢查是否有 POST 資料
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 印出 POST 資料
            echo "Received POST data:\n";
            print_r($_POST);
        } else {
            // 如果不是 POST 資料，回應錯誤訊息
            echo "No POST data received.";
        }
    }


    $input = json_decode(file_get_contents("php://input"), true);

    $newsId = $input["news_id"];
    $newsTitle = $input["news_title"];
    $newsContent = $input["news_content"];
    $newsDate = $input["news_date"];
    $newsCategory = $input["news_category"];
    $newsState = $input["news_state"];
    $newsImage = $_FILES["news_image"];
    
    //圖片覆蓋
    $img ="SELECT news_image FROM news WHERE news_id = :news_id"; 

    $getFileName = $pdo->prepare( $img );
    $getFileName->bindValue(':news_id', $newsId, PDO::PARAM_INT);
    $getFileName->execute();
    $oldFileName = $getFileName->fetchColumn();

    // 如果有新的圖片上傳，更新圖片並覆蓋原檔案
    if (!empty($newsImage)) {
        $imagePath = "../images/news/";
        $newImagePath = $imagePath . $newsImage["name"];
        $oldImagePath = $imagePath . $oldFileName;

        if (file_exists($oldImagePath) && is_writable($oldImagePath)) {
            copy($newImagePath, $oldImagePath);
            unlink($newImagePath);
        }
    }

    $sql ="UPDATE news SET
            news_title = :news_title,
            news_date = :news_date, 
            news_content = :news_content,
            news_category = :news_category,
            news_image = :news_image
            WHERE news_id = :news_id";

    $news = $pdo->prepare( $sql );

    $news->bindValue(':news_id', $newsId, PDO::PARAM_INT);
    $news->bindParam(':news_title', $newsTitle, PDO::PARAM_STR);
    $news->bindParam(':news_date', $newsDate, PDO::PARAM_STR);
    $news->bindParam(':news_content', $newsContent, PDO::PARAM_STR);
    $news->bindParam(':news_category', $newsCategory, PDO::PARAM_STR);
    $news->bindParam(":news_image", $newsImage["name"]);

    $news->execute(); //執行

    $result = ["error" => false,"msg"=>"成功修改最新消息"];
	// "image"=>$filename];

    
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}

echo json_encode($result);
?>
