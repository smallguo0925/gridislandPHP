<?php
//跨域的設定
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

session_start(); //用session來記錄登入的狀況
$data = json_decode(file_get_contents('php://input'), true);

try {
    // 從前端接收 mem_id
    $mem_id = $data['mem_id'];
    // 連線到資料庫
    require_once("../connectGridIsland.php");


    // 準備 SQL 指令，根據 mem_id 查詢會員資料
    $sql = "SELECT * FROM mem WHERE mem_id = :mem_id";
    $memData = $pdo->prepare($sql);
    $memData->bindParam(':mem_id', $mem_id, PDO::PARAM_INT);
    $memData->execute();


    if ($memData->rowCount()==0) {
        //查無部門資料
        //準備要回傳給前端的資料
        $result = ["error" => false,"msg"=>"查無此會員","memberData"=>[]];
    }else{
        //屬於這個會員的會員資料
        $memDataRow = $memData->fetchAll(PDO::FETCH_ASSOC);

        //屬於這個會員的預約資料
        $sql="SELECT * FROM book WHERE mem_id = :mem_id";
        $bookInfo=$pdo->prepare($sql);
        $bookInfo->bindParam(':mem_id', $mem_id, PDO::PARAM_INT);
        $bookInfo->execute();
        $bookInfoRow = $bookInfo->fetchAll(PDO::FETCH_ASSOC);

        //屬於這個會員的訂單資料
        $sql="SELECT * from ord where mem_id = :mem_id";
        $orderInfo=$pdo->prepare($sql);
        $orderInfo->bindParam(':mem_id', $mem_id, PDO::PARAM_INT);
        $orderInfo->execute();
        $orderInfoRow = $orderInfo->fetchAll(PDO::FETCH_ASSOC);

        //屬於這個會員的未完成訂單(要在會員中心首頁顯示)
        $sql = "SELECT count(*) from ord where 
        mem_id = :mem_id and ord_state = 0 " ;
        $undoneOrder=$pdo->prepare($sql);
        $undoneOrder->bindParam(':mem_id', $mem_id, PDO::PARAM_INT);
        $undoneOrder->execute();
        $undoneOrderRow = $undoneOrder->fetchAll(PDO::FETCH_ASSOC);

        //屬於這個會員的已完成訂單(要在會員中心首頁顯示)
        $sql = "SELECT count(*) from ord where 
        mem_id = :mem_id and ord_state = 1 " ;
        $completedOrder=$pdo->prepare($sql);
        $completedOrder->bindParam(':mem_id', $mem_id, PDO::PARAM_INT);
        $completedOrder->execute();
        $completedOrderRow = $completedOrder->fetchAll(PDO::FETCH_ASSOC);


        //屬於這個會員的訂單明細資料
        //這怎麼抓我要想一下嗚嗚嗚


        $result = [
            "error" => false,
            "msg"=>"成功取得訂單資料",
            "memberData"=>$memDataRow,
            "bookInfo"=>$bookInfoRow,
            "orderInfo"=>$orderInfoRow,
            "undoneOrder"=>$undoneOrderRow,
            "completedOrder"=>$completedOrderRow,
            // "orderListInfo"=>$orderListInfoRow,
        ];

    }
    echo json_encode($result);
} catch (PDOException $e) {
    // 處理異常
    echo "Error: " . $e->getMessage();
}




?>