<?php
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
    try{
        require_once("../connectGridIsland.php");
        //準備好sql指令
        
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
          $result = ["error" => false,"msg"=>"成功取得訂單資料","ords"=>[],"allOrd"=>$allOrdCount,"pOrd"=>$pOrdCount,"upOrd"=>$upOrdCount];

          $sql = "select o.*, oi.ord_item_qty, oi.ord_item_price, p.prod_name from ord o join ord_item oi on(o.ord_id = oi.ord_id) join prod p on (oi.prod_id = p.prod_id)";
        $orders = $pdo->query($sql);
        if ($orders->rowCount()==0) { //查無部門資料
          //準備要回傳給前端的資料
        } else { //取回所有部門資料
          while($row = $orders->fetch(PDO::FETCH_ASSOC)){
            $orderId = $row['ord_id'];
            $memId = $row['mem_id'];
            $orderDate = $row['ord_date'];
            $orderSum = $row['ord_sum'];
            $orderPromo = $row['ord_promo'];
            $orderDelivery = $row['ord_delivery'];
            $orderPay = $row['ord_pay'];
            $orderState = $row['ord_state'];
            $orderAddr = $row['ord_addr'];
            $orderName = $row['ord_name'];
            $orderTel = $row['ord_tel'];
            $promoId = $row['promo_id'];
            $orderNote = $row['ord_note'];
            $orderPayment = $row['ord_payment'];
    
            if (!isset($result['ords'][$orderId])) {
                $result['ords'][$orderId] = [
                    'ord_id' => $orderId,
                    'mem_id' => $memId,
                    'ord_date' => $orderDate,
                    'ord_sum' => $orderSum,
                    'ord_promo' => $orderPromo,
                    'ord_delivery' => $orderDelivery,
                    'ord_pay' => $orderPay,
                    'ord_state' => $orderState,
                    'ord_addr' => $orderAddr,
                    'ord_name' => $orderName,
                    'ord_tel' => $orderTel,
                    'promo_id' => $promoId,
                    'ord_note' => $orderNote,
                    'ord_payment' => $orderPayment,
                    'items' => []  // 初始化標籤陣列
                ];
            }
            
            $prod_name = $row['prod_name'];
            $item_price =$row['ord_item_price'];
            $item_qty = $row['ord_item_qty'];
            if (!empty($prod_name)) {
                $result['ords'][$orderId]['items'][] = [
                  'prod_name'=>$prod_name,
                  'ord_item_price'=>$item_price,
                  'ord_item_qty'=>$item_qty,
                ];
            }
        }
        $result['ords'] = array_values($result['ords']);
        }
      } catch (PDOException $e) {
        //準備要回傳給前端的資料
        $result = ["error" => true, "msg" => $e->getLine()];
      }
      echo json_encode($result);
      
?>