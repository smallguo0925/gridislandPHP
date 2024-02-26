<?php
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

ini_set("display_errors", "On");



try{
    // 建立15日內的資料
    require_once("../connectGridIsland.php");
    $sql = "INSERT INTO tables(tables_type, tables_date, tables_total)
        WITH RECURSIVE dates (v_date) AS
        (
            SELECT CURDATE()
            UNION ALL
            SELECT v_date + INTERVAL 1 DAY 
            FROM dates
            WHERE v_date + INTERVAL 1 DAY <= ADDDATE(CURDATE(), INTERVAL 15 DAY)
        ) 
        SELECT tt.table_type_id, d.v_date, tt.table_amount 
        FROM dates d 
        CROSS JOIN table_type tt
        LEFT JOIN tables t on (d.v_date = t.tables_date AND tt.table_type_id = t.tables_type)
        WHERE t.tables_date IS NULL
        ORDER BY 2,1;"; 

    $buildTables = $pdo->query($sql);
    $buildTables->execute();

    // 建立準備回傳data的sql指令
    $sql = "select * from tables where tables_date between adddate(curdate(), interval 1 day) and adddate(curdate(), interval 15 day)";
    // 建立PDO物件
    $returnTables = $pdo->query($sql);
    // 取得前端傳來的選擇日期
    // $returnTables->bindValue(":bookDate", $_POST["book_date"]);

    $returnTables->execute();

    $tablesRow = $returnTables->fetchAll(PDO::FETCH_ASSOC);
    $result = ["error" => false, "msg" => "", "returnTables" => $tablesRow];


} catch (PDOException $e) {
    $result = ["msg"=>$e->getMessage()];
}
echo json_encode($result);
?>