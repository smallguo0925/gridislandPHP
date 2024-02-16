<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
ini_set("display_errors", "On");

try {
	//連線
    require_once("../connectGridIsland.php");

    //準備sql指令
	$sql = "SELECT * FROM news";

    // 建立PDO Statement
    $news = $pdo->query($sql);

    $newsRows = $news->fetchAll(PDO::FETCH_ASSOC);
	$result = ["error" => false, "msg" => "", "news" => $newsRows];
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}
echo json_encode($result);

?>