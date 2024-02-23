<?php
require_once("../header.php");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: multipart/form-data"); 
try {
    $filename = ''; 
  
    if ($_FILES["prod_img1"]["error"] === 0 || 
        $_FILES["prod_img2"]["error"] === 0 || 
        $_FILES["prod_img3"]["error"] === 0) {
        $dir = "../../image/prod/"; 

        if ( !file_exists($dir) ) { 
            mkdir($dir);
        }
        $fileExtArray = []; // 副檔名的陣列

        for ($i = 1; $i <= 3; $i++) {
            $inputName = "prod_img" . $i;
            if ($_FILES[$inputName]["error"] === 0) {
                
                $fileInfo = pathinfo($_FILES[$inputName]["name"]);
                $fileExt = $fileInfo['extension'];
        
                // 取得已存在的檔案數量
                $existingFiles = glob($dir . "{$inputName}*.*");
                $fileCount = count($existingFiles) + 1;
        
                // 產生新檔案名稱
                $filename = "{$inputName}_{$fileCount}.{$fileExt}";

                
                $from = $_FILES[$inputName]["tmp_name"];
                $to = "{$dir}{$filename}";
                
                copy($from, $to);

                $fileExtArray[$i - 1] = $fileExt;
            }
        }
         //檢查檔案類型
        $allowedFileTypes = ['jpg', 'jpeg', 'png', 'webp'];
        if (!in_array(strtolower($fileExt), $allowedFileTypes)) {
            $result = ["error" => true, "msg" => "不支援該檔案類型"];
            echo json_encode($result);
            exit;
        }

        $maxFileSize = 3 * 1024 * 1024; 
        if ($_FILES["prod_img1"]["size"] > $maxFileSize ||
            $_FILES["prod_img2"]["size"] > $maxFileSize || 
            $_FILES["prod_img3"]["size"] > $maxFileSize) {
            $result = ["error" => true, "msg" => "上傳圖片大小已超過限制 3 MB"];
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
    $sql ="insert into prod (prod_id, prod_name, prod_price, prod_discount_price, prod_date, prod_intro, prod_desc, prod_brief, prod_img1, prod_img2, prod_img3, prod_state) values (null, :prod_name, :prod_price, :prod_discount_price, :prod_date, :prod_intro, :prod_desc, :prod_brief, :prod_img1, :prod_img2, :prod_img3, :prod_state)";
    $prod = $pdo->prepare( $sql );
    $prod_state = isset($_POST["prod_state"]) ? $_POST["prod_state"] : null; 
    $prod->bindValue(":prod_name", $_POST["prod_name"]);
    $prod->bindValue(":prod_discount_price", $_POST["prod_discount_price"], PDO::PARAM_INT);
    $prod->bindValue(":prod_price", $_POST["prod_price"], PDO::PARAM_INT);
    $prod->bindValue(":prod_date", $_POST["prod_date"]);
    $prod->bindValue(":prod_desc", $_POST["prod_desc"]);
    $prod->bindValue(":prod_intro", $_POST["prod_intro"]);
    $prod->bindValue(":prod_brief", $_POST["prod_brief"]);

    $prod->bindValue(":prod_img1", isset($fileExtArray[0]) ? "prod_img1_" . $fileCount . "." . $fileExtArray[0] : null);
    $prod->bindValue(":prod_img2", isset($fileExtArray[1]) ? "prod_img2_" . $fileCount . "." . $fileExtArray[1] : null);
    $prod->bindValue(":prod_img3", isset($fileExtArray[2]) ? "prod_img3_" . $fileCount . "." . $fileExtArray[2] : null);

    $prod->bindValue(":prod_state", $prod_state);

    $prod->execute(); 
    $prod_id =$pdo->lastInsertId();
    $tags=[$_POST["ppl"],$_POST["diff"],$_POST["category"]];

    $sql = "INSERT INTO prod_tag (prod_id, tag_id)VALUES($prod_id,:tag_id)";
    $prod_tag = $pdo->prepare($sql);
    foreach($tags as $key => $item){
        $prod_tag->bindValue(":tag_id",(int)$item);
        $prod_tag->execute();
    }

    $result = ["error" => false, "msg"=>"成功上傳商品","test"=>$_POST["ppl"]];
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}
echo json_encode($result);
?>
