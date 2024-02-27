<?php
//需請教董老師，上線後是否把以下註解
//跨域的設定
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
//好像是檔案接受的格式


try {
	//連線到剛建立的connect檔
    require_once("../connectGridIsland.php");

  //準備sql指令，要拿table_type裡全部的資料。
	$sql = "SELECT * FROM `table_type`";

  // 建立PDO Statement，原本的寫法會是
    //   $pdoStatement = $pdo->query($sql);
	//為方便使用閱讀，將$pdoStatement設定為$table_type
  $table_type = $pdo->query($sql);
	//因為前面用select，這裡用query即可

  $table_typeRows = $table_type->fetchAll(PDO::FETCH_ASSOC);
	$result = ["error" => false, "msg" => "", "table_type" => $table_typeRows];
} catch (PDOException $e) {
    $result = ["error" => true, "msg" => $e->getMessage()];
}
echo json_encode($result);

?>