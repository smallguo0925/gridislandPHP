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
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gridisland";

    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);

    // 準備 SQL 指令，根據 mem_id 查詢會員資料
    $sql = "SELECT * FROM mem WHERE mem_id = :mem_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':mem_id', $mem_id, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $e) {
    // 處理異常
    echo "Error: " . $e->getMessage();
}
echo json_encode($result);


?>