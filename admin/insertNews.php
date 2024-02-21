<?php
require_once("../header.php");

header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: multipart/form-data"); //上傳內容包含圖片。故使用form

try {
    $filename = ''; //初始化 $filename
    //--------------取得上傳檔案
    // 用UK當圖片主檔名，避免檔案名稱相同被覆蓋
    if ($_FILES["news_image"]["error"] === 0) {
        $dir = "../../image/news/"; //確認路徑
        if ( !file_exists($dir) ) { //確認目錄已存在
            mkdir($dir);
        }
        $fileExt = pathinfo($_FILES["news_image"]["name"], PATHINFO_EXTENSION);//假設是sara.png，用pathingo取出附檔名。

        //取得已存在的檔案數量
        $existingFiles = glob($dir . "news_img*.png"); //調整檔案類型和前綴
        $fileCount = count($existingFiles) + 1;

        //產生新檔案名稱
        $filename = "news_img" . $fileCount . ".png";

        // $filename = uniqid() . ".$fileExt"; //再用UK串起來，"."雙引號裡面小數點是檔案類型?
        $from = $_FILES["news_image"]["tmp_name"];

        $to = "$dir{$filename}";
        copy($from, $to); //把傳到暫存區的檔案，拷貝出來。

         //檢查檔案類型
        $allowedFileTypes = ['jpg', 'jpeg', 'png'];
        if (!in_array(strtolower($fileExt), $allowedFileTypes)) {
            $result = ["error" => true, "msg" => "不支援該檔案類型"];
            echo json_encode($result);
            exit;
        }

        //檢查檔案大小
        $maxFileSize = 5 * 1024 * 1024; //最大5MB
        if ($_FILES["news_image"]["size"] > $maxFileSize) {
            $result = ["error" => true, "msg" => "檔案大小超過限制"];
            echo json_encode($result);
            exit;
        }

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

    require_once("../connectGridIsland.php");

    $sql ="insert into news (news_id, news_title, news_date, news_content, news_category, news_image, news_state) values (null, :news_title, :news_date, :news_content, :news_category, :news_image, :news_state)";

    $news = $pdo->prepare( $sql );//使用prepare，避免隱碼攻擊

    $news_state = isset($_POST["news_state"]) ? $_POST["news_state"] : null; //將狀態設預設為0


    $news->bindValue(":news_title", $_POST["news_title"]);
    $news->bindValue(":news_content", $_POST["news_content"]);
    $news->bindValue(":news_date", $_POST["news_date"]);
    $news->bindValue(":news_category", $_POST["news_category"]);
    $news->bindValue(":news_image", $filename);
    $news->bindValue(":news_state", $news_state);


    $news->execute(); //執行

    $result = ["error" => false,"msg"=>"成功上傳最新消息"];
    
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}

echo json_encode($result);
?>
