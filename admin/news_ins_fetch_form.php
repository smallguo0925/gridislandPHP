<?
//要請問老師，上線後是否把該句註解
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");


try {
	//連線
    require_once("../connectGridIsland.php");

    //準備sql指令
	$sql = "insert into news (news_title, news_content, news_category, news_img)
    value (:news_title, :news_content, :news_category, :news_img)";

    // 建立PDO Statement，上面有別人送出的未知資料，所以用prepare，避免隱碼攻擊
    $news = $pdo->prepare($sql);

    $news->bindValue(':news_title',$_POST["news_title"]);
    $news->bindValue(':news_content',$_POST["news_content"]);
    $news->bindValue(':news_category',$_POST["news_category"]);
    $news->bindValue(':news_img',$_POST["news_img"]);

    //將資料放入並執行之
    $news->execute();

    //準備要回傳給前端的資料
    $result = ["error"=> false, "msg"=>"成功新增消息"];


    $newsRows = $news->fetchAll(PDO::FETCH_ASSOC);
	$result = ["error" => false, "msg" => "", "news" => $newsRows];
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}
echo json_encode($result);

?>