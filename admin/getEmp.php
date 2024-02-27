<?php
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");


try {
	//連線
    require_once("../connectGridIsland.php");

    //準備sql指令
	$sql = "select emp_id, emp_name, emp_acct, emp_permission, emp_state from emp";

    // 建立PDO Statement
    $emp = $pdo->query($sql);

    $empRows = $emp->fetchAll(PDO::FETCH_ASSOC);
	$result = ["error" => false, "msg" => "", "emp" => $empRows];
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}
echo json_encode($result);

?>