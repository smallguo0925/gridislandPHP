<?php
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");


try {
    //連線到demo資料庫
    require_once("../connectGridIsland.php");
    
    $sql = "insert into book(mem_id, book_date, book_time, book_start_time, book_end_time, book_people, tables_type) values (:mem_id,:book_date,:book_time, :book_start_time, :book_end_time, :book_people,:tables_type)";

    // 編譯sql指令
    $mem = $pdo->prepare($sql);

    $mem->bindValue(":mem_id",$_POST["mem_id"]);
    $mem->bindValue(":book_date",$_POST["book_date"]);
    $mem->bindValue(":book_time",$_POST["book_time"]);
    $mem->bindValue(":book_start_time",$_POST["book_start_time"]);
    $mem->bindValue(":book_end_time",$_POST["book_end_time"]);
    $mem->bindValue(":book_people",$_POST["book_people"]);
    $mem->bindValue(":tables_type",$_POST["tables_type"]);
    $mem->execute();


    //update tables裡的資料
    // count(*)
    $sql= "update tables set tables_am_booked = (select count(*) from book
        where book_date=:book_date AND tables_type = :tables_type AND book_time = '上午')
        where tables_date=:book_date AND tables_type = :tables_type;
        update tables set tables_af_booked = (select count(*) from book
        where book_date=:book_date AND tables_type = :tables_type AND book_time = '下午')
        where tables_date=:book_date AND tables_type = :tables_type;
        update tables set tables_eve_booked = (select count(*) from book
        where book_date=:book_date AND tables_type = :tables_type AND book_time = '傍晚')
        where tables_date=:book_date AND tables_type = :tables_type;
        update tables set tables_pm_booked = (select count(*) from book
        where book_date=:book_date AND tables_type = :tables_type AND book_time = '晚上')
        where tables_date=:book_date AND tables_type = :tables_type";

    $bookUpdate = $pdo->prepare($sql);
    $bookUpdate->bindValue(":book_date",$_POST["book_date"]);
    $bookUpdate->bindValue(":tables_type",$_POST["tables_type"]);
    $bookUpdate->execute();
    $result = ["error" => false, "msg" => "success"];

    
} catch (PDOException $e) {
	
    $result = ["error" => true, "msg" => $e->getMessage()];

}
echo json_encode($result);

// mem_id: this.userData.mem_id,
// book_date: this.dateChosen,
// book_time: this.timeChosen,
// book_people: this.count,
// tables_type: this.tableChosen,
// book_state: this.bookState
?>