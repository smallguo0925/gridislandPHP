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
    // 全部消息的數量
    $sql = "SELECT COUNT(*) as newsCount  FROM news";
    $allNews = $pdo->query($sql);
    $allNewsRows = $allNews->fetch(PDO::FETCH_ASSOC)["newsCount"];

    // 優惠消息的數量
    $sql = "SELECT COUNT(*) as promotion  FROM news WHERE news_category = '優惠'";
    $promotion = $pdo->query($sql);
    $promotionRows = $promotion->fetch(PDO::FETCH_ASSOC)["promotion"];

    // 活動消息的數量
    $sql = "SELECT COUNT(*) as activity  FROM news WHERE news_category = '活動'";
    $activity = $pdo->query($sql);
    $activityRows = $activity->fetch(PDO::FETCH_ASSOC)["activity"];

    // 桌遊消息的數量
    $sql = "SELECT COUNT(*) as boardGame  FROM news WHERE news_category = '桌遊'";
    $boardGame = $pdo->query($sql);
    $boardGameRows = $boardGame->fetch(PDO::FETCH_ASSOC)["boardGame"];

	$result = ["error" => false, "msg" => "", "news" => $newsRows , "allNewsCount"=>$allNewsRows,"promotionCount"=>$promotionRows,"activityCount"=>$activityRows,"boardGameCount"=>$boardGameRows];
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}
echo json_encode($result);

?>