<?php
require_once("../header.php");

header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: multipart/form-data"); //上傳內容包含圖片。故使用form

try {
    require_once("../connectGridIsland.php");

    $sql ="insert into promo (promo_id, promo_code, promo_detail, promo_amount, promo_start_date, promo_end_date, marquee_state, promo_pub_start, promo_pub_end) 
    values (null, :promo_code, :promo_detail, :promo_amount, :promo_start_date, :promo_end_date,:marquee_state, :promo_pub_start_date, :promo_pub_end_date)";

    $news = $pdo->prepare( $sql );//使用prepare，避免隱碼攻擊

    $news->bindValue(":promo_code", $_POST["promo_code"]);
    $news->bindValue(":promo_detail", $_POST["promo_detail"]);
    $news->bindValue(":promo_amount", $_POST["promo_amount"]);
    $news->bindValue(":promo_start_date", $_POST["promo_start_date"]);
    $news->bindValue(":promo_end_date", $_POST["promo_end_date"]);
    $news->bindValue(":marquee_state", $_POST["marquee_state"]);
    $news->bindValue(":promo_pub_start_date", $_POST["promo_pub_start_date"]);
    $news->bindValue(":promo_pub_end_date", $_POST["promo_pub_end_date"]);


    $news->execute(); //執行

    $result = ["error" => false,"msg"=>"成功上傳優惠碼"];
    
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}

echo json_encode($result);
?>
