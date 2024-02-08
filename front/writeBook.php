<?php
header("Access-Control-Allow-Origin: *"); // 允許所有來源
header("Content-Type: application/json; charset=UTF-8");


try {
    //連線到demo資料庫
    require_once("../../GridIsland/connectGridIsland.php");
    
    $sql = "insert into book(mem_id, book_date, book_time, book_people, tables_type, book_state) values (:mem_id,:book_date,:book_time,:book_people,:tables_type,:book_state)";

    // 編譯sql指令
    $mem = $pdo->prepare($sql);

    $mem->bindValue(":mem_id",$_POST["mem_id"]);
    $mem->bindValue(":book_date",$_POST["book_date"]);
    $mem->bindValue(":book_time",$_POST["book_time"]);
    $mem->bindValue(":book_people",$_POST["book_people"]);
    $mem->bindValue(":tables_type",$_POST["tables_type"]);
    $mem->bindValue(":book_state",$_POST["book_state"]);
    $mem->execute();

    // $result = ["error" => false, "msg" => "success"];
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