<?php
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

ini_set("display_errors", "On");

session_start();

try{
  require_once("../connectGridIsland.php");
  // $sql = "select mem_id, mem_name, mem_addr, mem_email, mem_tel, mem_game_state, mem_bug_state, mem_profile, mem_nickname, mem_gender, mem_birthday from mem where mem_email=:memEmail"; 
  $sql = "select mem_id, mem_name, mem_addr, mem_email, mem_tel, mem_game_state, mem_bug_state, mem_profile, mem_nickname, mem_gender, mem_birthday, mem_state from mem where mem_email = :memEmail";
  
  $member = $pdo->prepare($sql);

  $member->bindValue(":memEmail", $_POST["mem_account"]);

  $member->execute();

  if ( $member->rowCount()=== 0) { //查無此信箱, 新增資料
    // $res = array('code' => 0);
    $sql = "insert into mem (mem_name, mem_email) values (:memName, :memEmail)";
    $addMember = $pdo->prepare($sql);
    $addMember->bindValue(":memEmail", $_POST["mem_account"]);
    $addMember->bindValue(":memName", $_POST["mem_name"]);
    $addMember->execute();

    $sql = "select mem_id, mem_name, mem_addr, mem_email, mem_tel, mem_game_state, mem_bug_state, mem_profile, mem_nickname, mem_gender, mem_birthday, mem_state from mem where mem_email=:memEmail";
    $newMember = $pdo->prepare($sql);
    $newMember->bindValue(":memEmail", $_POST["mem_account"]);
    $newMember->execute();
    $newMemRow = $newMember->fetch(PDO::FETCH_ASSOC);
    $res = array(
      'session_id' => session_id(), // 这里可以使用您自己的会话管理
      'memInfo' => $newMemRow // 将用户信息返回给前端
    );
  } else { //登入成功
    //自資料庫中取回資料
    $memRow = $member->fetch(PDO::FETCH_ASSOC);
    //送出登入者的姓名資料
    $res = array(
      'session_id' => session_id(), // 这里可以使用您自己的会话管理
      'memInfo' => $memRow // 将用户信息返回给前端
    );

  }
} catch (PDOException $e) {
  $res = ["msg"=>$e->getMessage()];
  
}
// $conn->close();
echo json_encode($res);
?>