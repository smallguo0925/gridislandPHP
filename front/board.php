<?php
//跨域的設定
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

try {
	//連線到剛建立的connect檔
  require_once("../../GridIsland/connectGridIsland.php");

  $sql = "
    SELECT 
    ms.msg_id, ms.mem_id, ms.msg_content, ms.msg_state, ms.msg_datetime,
    me.mem_name, me.mem_nickname, me.mem_profile,
    re.report_state,
    (
      SELECT
        JSON_ARRAYAGG(
          JSON_OBJECT(
            'reply_id', re.reply_id,
            'reply_content', re.reply_content,
            'reply_time', re.reply_time,
            'reply_memName', me.mem_name,
            'reply_nickName', me.mem_nickname,
            'reply_memProfile', me.mem_profile
          )
        )
      FROM reply re
      JOIN mem me ON me.mem_id = re.mem_id
      WHERE re.msg_id = ms.msg_id
      ) AS replies
    FROM msg ms 
    JOIN mem me ON ms.mem_id = me.mem_id
    LEFT JOIN report re ON re.msg_id = ms.msg_id
    ORDER BY ms.msg_id DESC;
  ";

  // 建立PDO Statement
  $board = $pdo->query($sql);

  $boardRows = $board->fetchAll(PDO::FETCH_ASSOC);

	$result = ["error" => false, "errorMessage" => "", "board" => $boardRows];
} catch (PDOException $e) {
    $result = ["error" => true, "errorMessage" => $e->getMessage()];
}
echo json_encode($result);

?>