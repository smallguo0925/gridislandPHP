<?php
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"),true);
try {
    require_once("../connectGridIsland.php");
    $ordId = $data["ordId"];
    $ordNote = $data["ordNote"];
    $sql = "UPDATE ord SET ord_note = :ordNote WHERE ord_id = :ordId";
    $ord = $pdo->prepare($sql);

    $ord->bindValue(":ordNote", $ordNote);
    $ord->bindValue(":ordId",$ordId);

    $ord->execute();
    $result = ["error" => false,"msg"=>"成功更改訂單備註"];
    
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}

echo json_encode($result);
?>
