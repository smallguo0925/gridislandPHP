<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
    try{
        require_once("../connectGridIsland.php");
        //準備好sql指令
        $sql = "select * from ord";
        $orders = $pdo->query($sql);
        if ($orders->rowCount()==0) { //查無部門資料
          //準備要回傳給前端的資料
          $result = ["error" => false,"msg"=>"查無此訂單","ords"=>[]];
      
        } else { //取回所有部門資料
          $ordRows = $orders->fetchAll(PDO::FETCH_ASSOC);
          //準備要回傳給前端的資料
          // 全訂單數量
          $sql = "select count(*) from ord";
          $allOrd = $pdo->query($sql);
          $allOrdCount = $allOrd->fetch(PDO::FETCH_ASSOC);
          // 已完成訂單數量
          $sql = "select count(*) from ord where ord_state = 1";
          $pOrd = $pdo->query($sql);
          $pOrdCount = $pOrd->fetch(PDO::FETCH_ASSOC);
          // 未完成訂單數量
          $sql = "select count(*) from ord where ord_state = 0";
          $upOrd = $pdo->query($sql);
          $upOrdCount = $upOrd->fetch(PDO::FETCH_ASSOC);
          $result = ["error" => false,"msg"=>"成功取得訂單資料","ords"=>$ordRows,"allOrd"=>$allOrdCount,"pOrd"=>$pOrdCount,"upOrd"=>$upOrdCount];
        }
      } catch (PDOException $e) {
        //準備要回傳給前端的資料
        $result = ["error" => true, "msg" => $e->getLine()];
      }
      echo json_encode($result);
      
?>