<?php
ini_set("display_errors", "On");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);
$orderData = json_decode($data["jsonData"]);
$cart = $orderData->cart;
$cartArray = (array) $cart;

try {
    require_once("../connectGridIsland.php"); 
    $pdo->beginTransaction();
    $mem_id = $orderData->order->mem_id;
    $ord_sum = $orderData->order->ord_sum;
    $ord_promo = $orderData->order->ord_promo;
    $ord_delivery = $orderData->order->ord_delivery;
    $ord_pay = $orderData->order->ord_pay;
    $ord_addr = $orderData->order->ord_addr;
    $ord_name = $orderData->order->ord_name;
    $ord_tel = $orderData->order->ord_tel;
    $promo = $orderData->order->promo;
    $ord_payment = $orderData->order->ord_payment;
    // 將訂單資訊傳進資料庫
    $sql = "INSERT INTO ord(ord_id,mem_id,ord_date,ord_sum,ord_promo,ord_delivery,ord_pay,ord_addr,ord_name,ord_tel,promo_id,ord_payment)
            VALUES (null,:mem_id,now(),:ord_sum,:ord_promo,:ord_delivery,:ord_pay,:ord_addr,:ord_name,:ord_tel,:promo,:ord_payment)";
    $ord = $pdo->prepare($sql);

    $ord->bindValue(":mem_id", $mem_id);
    $ord->bindValue(":ord_sum", $ord_sum);
    $ord->bindValue(":ord_promo", $ord_promo);
    $ord->bindValue(":ord_delivery", $ord_delivery);
    $ord->bindValue(":ord_pay", $ord_pay);
    $ord->bindValue(":ord_addr", $ord_addr);
    $ord->bindValue(":ord_name", $ord_name);
    $ord->bindValue(":ord_tel", $ord_tel);
    $ord->bindValue(":promo", $promo);
    $ord->bindValue(":ord_payment", $ord_payment);
    
    $ord->execute();
    $ordId = $pdo->lastInsertId(); // 上一個傳進資料庫的訂單 ID
    // 將訂單項目傳進資料庫
    $sql = "INSERT INTO ord_item values($ordId,:prod_id,:quantity,:item_price)";
    $ord_item = $pdo->prepare($sql);
    foreach($cartArray as $key =>$item){
        $ord_item->bindValue(":prod_id",$item->id);
        $ord_item->bindValue(":quantity",$item->count);
        $ord_item->bindValue(":item_price",$item->price);
        $ord_item->execute();
    }

    // 優惠碼使用紀錄傳進資料庫
    if($promo != null){
        $sql="INSERT INTO promo_record (promo_id,mem_id,promo_record) values(:promo_id,:mem_id,now())";
        $promoRecord = $pdo->prepare($sql);
        $promoRecord->bindValue(":promo_id",$promo);
        $promoRecord->bindValue(":mem_id",$mem_id);
        $promoRecord->execute();
    }
    $pdo->commit();
    $result = ["error" => false,"msg"=>"成功更改訂單備註","test"=> $ordId];
    
} catch (PDOException $e) {
    $pdo->rollBack();
    $result = ["error" => true, "msg" => $e->getMessage()];
}

echo json_encode($result);
?>
