<?php
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
ini_set("display_errors", "On");

try {
    require_once("../connectGridIsland.php");
	$sql = "SELECT news_id, news_content, news_title, news_date, news_image, news_category from news WHERE news_state = 1 ORDER BY news_date DESC";
    $news = $pdo->query($sql);

    $newsRows = $news->fetchAll(PDO::FETCH_ASSOC);
	$result = ["error" => false, "msg" => "", "news" => $newsRows];
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}
echo json_encode($result);
?>