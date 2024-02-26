<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"),true);
try {
    require_once("../connectGridIsland.php");
    $empId = $data["empId"];
    $isChecked = $data["isChecked"];
    $sql = "UPDATE emp SET emp_state = :isChecked WHERE emp_id = :empId";
    $ord = $pdo->prepare($sql);

    $ord->bindValue(":isChecked",$isChecked);
    $ord->bindValue(":empId",$empId);

    $ord->execute();
    $result = ["error" => false,"msg"=>"成功更改員工狀態"];
    
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}

echo json_encode($result);
?>
