<?php
//跨域的設定
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");


try {
    require_once("../../GridIsland/connectGridIsland.php");

    $sql = "SELECT mem_id, mem_name, mem_state, mem_profile, mem_nickname FROM mem";

    $header = $pdo->query($sql);

    $headerRows = $header->fetchAll(PDO::FETCH_ASSOC);
	$result = ["error" => false, "errorMessage" => "", "header" => $headerRows];
} catch (PDOException $e) {
    $result = ["error" => true, "errorMessage" => $e->getMessage()];
}
echo json_encode($result);

?>