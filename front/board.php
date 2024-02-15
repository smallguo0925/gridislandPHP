<?php
//跨域的設定
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");


try {
	//連線到剛建立的connect檔
  require_once("../../GridIsland/connectGridIsland.php");

  $sql = "SELECT 
  ms.msg_id, ms.mem_id, ms.msg_content, ms.msg_state, ms.msg_datetime,
  me.mem_name, me.mem_nickname, 
  (
    select
      JSON_ARRAYAGG(
        JSON_OBJECT(
        'reply_id', re.reply_id,
        'reply_content', re.reply_content,
        'reply_time', re.reply_time,
              'reply_memName', me.mem_name
        )
          )
          from reply re
          join mem me 
          on me.mem_id = re.mem_id
          where re.msg_id = ms.msg_id
          )as replies
  from msg ms 
  join mem me on ms.mem_id = me.mem_id;";

  // 建立PDO Statement，原本的寫法會是$pdoStatement = $pdo->query($sql);
  $board = $pdo->query($sql);
  //沒有未知數(前台傳進來的資料)用query就好

  $boardRows = $board->fetchAll(PDO::FETCH_ASSOC);

  // 對於每條消息的回覆數據進行解析
  // foreach ($boardRows as &$row) {
  //   $row['replies'] = json_decode($row['replies'], true);
  // }

	$result = ["error" => false, "errorMessage" => "", "board" => $boardRows];
} catch (PDOException $e) {
    $result = ["error" => true, "errorMessage" => $e->getMessage()];
}
echo json_encode($result);

?>