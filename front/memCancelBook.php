<?php 
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

try {
    // 連線到資料庫
    require_once("../connectGridIsland.php");
    $sql="UPDATE book
    SET book_state=0
    where book_id=:book_id;
        ";

    $update = $pdo->prepare($sql);
    $update ->bindValue(":book_id",$_POST["book_id"]);

    // 執行 SQL 語句
    $update->execute();

   //update tables裡的資料
    // count(*)
    $sql= "update tables set tables_am_booked = (select count(*) from book
    where book_date=:book_date AND tables_type = :tables_type AND book_time = '上午' AND book_state != 0)
    where tables_date=:book_date AND tables_type = :tables_type;
    update tables set tables_af_booked = (select count(*) from book
    where book_date=:book_date AND tables_type = :tables_type AND book_time = '下午' AND book_state != 0)
    where tables_date=:book_date AND tables_type = :tables_type;
    update tables set tables_eve_booked = (select count(*) from book
    where book_date=:book_date AND tables_type = :tables_type AND book_time = '傍晚' AND book_state != 0)
    where tables_date=:book_date AND tables_type = :tables_type;
    update tables set tables_pm_booked = (select count(*) from book
    where book_date=:book_date AND tables_type = :tables_type AND book_time = '晚上' AND book_state != 0)
    where tables_date=:book_date AND tables_type = :tables_type";

    $bookUpdate = $pdo->prepare($sql);
    $bookUpdate->bindValue(":book_date",$_POST["book_date"]);
    $bookUpdate->bindValue(":tables_type",$_POST["tables_type"]);
    $bookUpdate->execute();    // $result = ["error" => false, "msg" => "success"];



    $result = ["error" => false,"msg"=>"成功更改"];


} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage(), "line" => $e->getLine()];
}

echo json_encode($result);

?>