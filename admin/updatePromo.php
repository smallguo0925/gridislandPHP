<?php
require_once("../header.php");

header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: multipart/form-data"); //上傳內容包含圖片。故使用form

try {
    require_once("../connectGridIsland.php");

    $sql ="UPDATE promo SET promo_code = :promo_code,
                            promo_detail = :promo_detail,
                            promo_amount = :promo_amount,
                            promo_start_date = :promo_start_date,
                            promo_end_date = :promo_end_date,
                            marquee_state = :marquee_state,
                            promo_pub_start = :promo_pub_start_date,
                            promo_pub_end = :promo_pub_end_date
            WHERE promo_id = :promo_id";

    $promos = $pdo->prepare( $sql );//使用prepare，避免隱碼攻擊

    $promos->bindValue(":promo_code", $_POST["promo_code"]);
    $promos->bindValue(":promo_detail", $_POST["promo_detail"]);
    $promos->bindValue(":promo_amount", $_POST["promo_amount"]);
    $promos->bindValue(":promo_start_date", $_POST["promo_start_date"]);
    $promos->bindValue(":promo_end_date", $_POST["promo_end_date"]);
    $promos->bindValue(":marquee_state", $_POST["marquee_state"]);
    $promos->bindValue(":promo_pub_start_date", $_POST["promo_pub_start_date"] == "null"?null:$_POST["promo_pub_start_date"]);
    $promos->bindValue(":promo_pub_end_date", $_POST["promo_pub_end_date"]== "null"?null:$_POST["promo_pub_end_date"]);
    $promos->bindValue(":promo_id", $_POST["promo_id"]);


    $promos->execute(); //執行

    $result = ["error" => false,"msg"=>"成功修改優惠碼","test"=>$_POST["promo_pub_end_date"]];
    
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}

echo json_encode($result);
?>
