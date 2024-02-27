<?php
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
$tables = json_decode(file_get_contents("php://input"),true);

try {
    require_once("../connectGridIsland.php");
    // $table_type_id = $data["table_type_id"]; //
    // $table_amount = $data["table_amount"]; //桌數，從 $data 陣列中取得鍵為 "table_amount" 的值，存放在 $table_amount 變數中。
    
    $sql = "UPDATE table_type SET table_amount = :table_amount WHERE table_type_id = :table_type_id";
    //update table_type這個表裡面的table_amount這個欄位，查詢條件是table_id=table_id
    $stmt = $pdo->prepare($sql); //

    foreach($tables as $i => $table) { //i=index
        $stmt->bindValue(":table_amount", $table["table_amount"]); 
        //修改桌數，將 $table_amount 變數的值綁定到 SQL 語句中的 :table_amount 參數。
        $stmt->bindValue(":table_type_id", $table["table_type_id"]); 
        // 绑定 table_type_id
        $stmt->execute();//執行 SQL 語句，實際更新資料庫中的記錄。

    }

    $result = ["error" => false,"msg"=>"成功更改桌數"];
    
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage(), "line" => $e->getLine()];
}

echo json_encode($result);
?>
