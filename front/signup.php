<?php
header("Access-Control-Allow-Origin: *"); // 允許所有來源
header("Content-Type: application/json; charset=UTF-8");


try {
    //連線到demo資料庫
    require_once("../../GridIsland/connectGridIsland.php");
    
    $sql = "insert into mem(mem_name, mem_email, mem_psw) values (:mem_name,:mem_email,:mem_psw)";
    // 編譯sql指令
$mem = $pdo->prepare($sql);

    $mem->bindValue(":mem_name",$_POST["mem_name"]);
    $mem->bindValue(":mem_email",$_POST["mem_email"]);
    $mem->bindValue(":mem_psw",$_POST["mem_psw"]);
    $mem->execute();

    $result = ["error" => false, "msg" => "success"];
} catch (PDOException $e) {
	
    $result = ["error" => true, "msg" => $e->getMessage()];

}
echo json_encode($result);
?>