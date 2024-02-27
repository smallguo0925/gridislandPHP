<?php
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

try {
	//連線到剛建立的connect檔
  require_once("../connectGridIsland.php");

  //準備sql指令，我要拿news裡全部的資料，到時候有帳號密碼的檔案不能這樣拿。
    $sql = "SELECT b.book_id, b.mem_id, b.book_date, b.book_time, b.book_people, b.tables_type, b.book_state, m.mem_name, ty.table_type_name
    from book b join mem m on b.mem_id = m.mem_id
                join table_type ty on b.tables_type = ty.table_type_id
    order by b.book_date desc;";

  // 建立PDO Statement，原本的寫法會是$pdoStatement = $pdo->query($sql);
	//為方便使用閱讀，將$pdoStatement設定為$news
    $books = $pdo->query($sql);
	//因為前面用select，這裡用query即可

    $booksRows = $books->fetchAll(PDO::FETCH_ASSOC);
    $result = ["error" => false, "msg" => "", "books" => $booksRows];
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}
echo json_encode($result);

?>