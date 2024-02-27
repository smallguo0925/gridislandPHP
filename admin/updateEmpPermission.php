<?php
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"),true);
try {
    require_once("../connectGridIsland.php");
    $empId = $data["empId"];
    $empPermission = $data["empPermission"];
    $sql = "UPDATE emp SET emp_permission = :empPermission WHERE emp_id = :empId";
    $ord = $pdo->prepare($sql);

    $ord->bindValue(":empPermission",$empPermission);
    $ord->bindValue(":empId",$empId);

    $ord->execute();
    $result = ["error" => false,"msg"=>"成功更改員工權限"];
    
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}

echo json_encode($result);
?>
