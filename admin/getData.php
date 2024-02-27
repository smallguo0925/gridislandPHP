<?php
require_once("../header.php");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
    try{
        require_once("../connectGridIsland.php");
        //準備好sql指令
        //ord
        $sql = "select count(*) as ordCount from ord where ord_state = 0";
        $ord = $pdo->query($sql);
        $ordRows = $ord->fetch(PDO::FETCH_ASSOC)["ordCount"];
        //prod
        $sql = "select count(*) as prodCount from prod";
        $prod = $pdo->query($sql);
        $prodRows = $prod->fetch(PDO::FETCH_ASSOC)["prodCount"];
       //book
       $sql = "select count(*) as bookCount from book";
       $book = $pdo->query($sql);
       $bookRows = $book->fetch(PDO::FETCH_ASSOC)["bookCount"];
       //news
       $sql = "select count(*) as newsCount from news";
       $news = $pdo->query($sql);
       $newsRows = $news->fetch(PDO::FETCH_ASSOC)["newsCount"];
       //report
       $sql = "select count(*) as reportCount from report";
       $report = $pdo->query($sql);
       $reportRows = $report->fetch(PDO::FETCH_ASSOC)["reportCount"];
       //member
       $sql = "select count(*) as memCount from mem";
       $mem = $pdo->query($sql);
       $memRows = $mem->fetch(PDO::FETCH_ASSOC)["memCount"];

       $result = ["order"=>$ordRows,"product"=>$prodRows,"book"=>$bookRows,"news"=>$newsRows,"report"=>$reportRows,"member"=>$memRows];
      } catch (PDOException $e) {
        //準備要回傳給前端的資料
        $result = ["error" => true, "msg" => $e->getLine()];
      }
      echo json_encode($result);
      
?>