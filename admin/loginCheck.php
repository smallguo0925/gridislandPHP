<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

ini_set("display_errors", "On");

session_start();

try{
  require_once("../connectGridIsland.php");
  $sql = "select emp_id, emp_acct, emp_name, emp_permission, emp_state from emp where emp_acct = :empAcct and emp_psw = :empPsw";
  $emp = $pdo->prepare($sql);

  $emp->bindValue(":empAcct", $_POST["emp_account"]);
  $emp->bindValue(":empPsw", $_POST["emp_psw"]);

  $emp->execute();

  if ( $emp->rowCount()=== 0) { //查無此人, 帳密錯誤
    $res = array('code' => 0);
  } else { //登入成功
    //自資料庫中取回資料
    $empRow = $emp->fetch(PDO::FETCH_ASSOC);
    //送出登入者的姓名資料
    $res = array(
      'code' => 1,
      'session_id' => session_id(), // 这里可以使用您自己的会话管理
      'empInfo' => $empRow // 将用户信息返回给前端
    );

  }
} catch (PDOException $e) {
  $res = ["msg"=>$e->getMessage()];
  
}
// $conn->close();
echo json_encode($res);
?>